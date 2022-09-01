<?php

namespace App\Actions;

use App\Exceptions\ArrayValidationException;
use App\Exceptions\ExceptionHandler;
use App\Models\Lancamento;
use App\Models\Negociacao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CadastrarNegociacaoAction
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function execute()
	{
		try {

			$this->validate();

			$this->format();

			DB::beginTransaction();

			$negociacao = new Negociacao();

			$negociacao->ano = date('y');

			$negociacao->numero = $this->gerarNumero();

			$negociacao->codigo = $negociacao->ano . '.' . str_pad($negociacao->numero, '3', '0', STR_PAD_LEFT);

			$negociacao->data = $this->data['data'];

			$negociacao->taxas = $this->data['taxas'];

			$negociacao->valor = $negociacao->taxas;

			foreach($this->data['ativos'] as $ativo){
				$negociacao->valor = bcadd($negociacao->valor, $ativo['total_sem_corretagem'], 8);
			}

			$negociacao->save();

			foreach($this->data['ativos'] as $ativo){

				$percentual = bcdiv(bcmul($ativo['total_sem_corretagem'], 100, 8), $negociacao->valor, 8);

				$lancamento = new Lancamento();

				$lancamento->qtd = $ativo['qtd'];

				$lancamento->preco = $ativo['preco'];

				$lancamento->total_sem_corretagem = $ativo['total_sem_corretagem'];

				$lancamento->taxas = bcdiv(bcmul($percentual, $this->data['taxas'], 8), 100, 8);

				$lancamento->total_com_corretagem = bcadd($lancamento->total_sem_corretagem, $lancamento->taxas, 8);

				$lancamento->preco_com_corretagem = bcdiv($lancamento->total_com_corretagem, $lancamento->qtd, 8);

				$lancamento->negociacao_id = $negociacao->id;

				$lancamento->ativo_id = $ativo['id'];

				$lancamento->save();
			}

			DB::commit();

			return [
				'status' => 'success',
				'message' => 'Ativo cadastrado com sucesso'
			];
		}
		catch(Exception $e){

			DB::rollBack();

			return ExceptionHandler::getMessageByClass($e);
		}
	}

	private function validate()
	{
		$validator = Validator::make($this->data, [
			'data' => 'required',
			'taxas' => 'required'
		], [
			'data.required' => 'Preencha a data',
			'taxas.required' => 'Preencha o valor de taxas'
		]);

		if($validator->fails()){
			throw new ValidationException($validator);
		}

		$errors = [];

		foreach($this->data['ativos'] as $key => $ativo){

			$validator = Validator::make($ativo, [
				'qtd' => [
					'bail',
					'required',
					'regex:/^\d{1,3}(.\d{3})*(\,\d{2})?$/'
				],
				'preco' => [
					'bail',
					'required',
					'regex:/^\d{1,3}(.\d{3})*(\,\d{2,4})?$/'
				]
			], [
				'qtd.required' => 'Preencha a quantidade',
				'qtd.regex' => 'Preencha uma quantidade válida',
				'preco.required' => 'Preencha o preço',
				'preco.regex' => 'Preencha um preço válido',
			]);

			if($validator->fails()){
				$errors['ativos'][$key] = $validator->getMessageBag();
			}
		}

		if(count($errors)){
			throw new ArrayValidationException(json_encode($errors));
		}
	}

	private function format()
	{
		$this->data['taxas'] = realToDollar($this->data['taxas']);

		foreach($this->data['ativos'] as $key => $ativo){

			$this->data['ativos'][$key]['qtd'] = realToDollar($ativo['qtd']);

			$this->data['ativos'][$key]['preco'] = realToDollar($ativo['preco']);

			$this->data['ativos'][$key]['preco_total'] = bcmul($this->data['ativos'][$key]['qtd'], $this->data['ativos'][$key]['preco'], 8);
		}
	}

	private function gerarNumero()
	{
		$ultimo_numero = DB::table('negociacoes')
			->select('numero')
			->where('ano', '=', date('y'))
			->orderBy('numero', 'desc')
			->first();

		return $ultimo_numero ? $ultimo_numero->numero + 1 : 1;
	}
}
