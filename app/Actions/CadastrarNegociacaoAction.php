<?php

namespace App\Actions;

use App\Exceptions\ArrayValidationException;
use App\Exceptions\ExceptionHandler;
use App\Models\Ativo;
use App\Models\Lancamento;
use App\Models\Negociacao;
use Exception;
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

			$ativos = Ativo::whereIn('id', array_column($this->data['ativos'], 'id'))->get();

			DB::beginTransaction();

			$negociacao = new Negociacao();

			$negociacao->ano = date('y');

			$negociacao->numero = $this->gerarNumero();

			$negociacao->codigo = $negociacao->ano . '.' . str_pad($negociacao->numero, '3', '0', STR_PAD_LEFT);

			$negociacao->data = $this->data['data'];

			$negociacao->taxas = $this->data['taxas'];

			$negociacao->valor = $negociacao->taxas;

			foreach($this->data['ativos'] as $ativo){
				$negociacao->valor = bcadd($negociacao->valor, $ativo['preco_total_bruto'], 8);
			}

			$negociacao->save();

			foreach($this->data['ativos'] as $ativo){

				$ativo_posicao = $ativos->where('id', '=', $ativo['id'])->first();

				if($ativo_posicao == null){
					throw new Exception('Ativo inválido');
				}

				$ultimo_lancamento = Lancamento::where('ativo_id', $ativo)->orderBy('id', 'desc')->first();

				$percentual = bcdiv(bcmul($ativo['preco_total_bruto'], 100, 8), $negociacao->valor, 8);

				$lancamento = new Lancamento();

				$lancamento->data = $negociacao->data;

				$lancamento->operacao = bccomp($ativo['qtd'], 0, 1) === 1 ? 0 : 1;

				$lancamento->qtd = $ativo['qtd'];

				$lancamento->preco_unitario_bruto = $ativo['preco_unitario_bruto'];

				$lancamento->preco_total_bruto = $ativo['preco_total_bruto'];

				$lancamento->taxas = bcdiv(bcmul($percentual, $this->data['taxas'], 8), 100, 8);

				$taxas_valor_unitario = bcdiv($lancamento->taxas, $lancamento->qtd, 8);

				if($lancamento->operacao == 0){

					// para compras, acrescentar taxas no preço

					$lancamento->preco_unitario_liquido = bcadd($lancamento->preco_unitario_bruto, $taxas_valor_unitario, 8);

					$lancamento->preco_total_liquido = bcadd($lancamento->preco_total_bruto, $lancamento->taxas, 8);

					if($ultimo_lancamento){

						$lancamento->qtd_acumulada = bcadd($ultimo_lancamento->qtd_acumulada, $lancamento->qtd, 0);

						$valor_em_estoque = bcmul($ultimo_lancamento->preco_unitario_mp, $ultimo_lancamento->qtd_acumulada, 8);

						$valor_adquirido = $lancamento->preco_total_liquido;

						$lancamento->preco_unitario_mp = bcdiv(bcadd($valor_em_estoque, $valor_adquirido, 8), $lancamento->qtd_acumulada, 8);

					} else {

						$lancamento->preco_unitario_mp = $lancamento->preco_unitario_liquido;

						$lancamento->qtd_acumulada = $lancamento->qtd;
					}
				}
				else {

					// para vendas, subtrair taxas do preço

					$lancamento->preco_unitario_liquido = bcsub($lancamento->preco_unitario_bruto, $taxas_valor_unitario, 8);

					$lancamento->preco_total_liquido = bcsub($lancamento->preco_total_bruto, $lancamento->taxas, 8);

					$lancamento->preco_unitario_mp = $ultimo_lancamento->preco_unitario_mp;

					$lancamento->qtd_acumulada = bcadd($ultimo_lancamento->qtd_acumulada, $lancamento->qtd, 0);

					$lancamento->saldo_venda = bcmul(bcsub($lancamento->preco_unitario_liquido, $lancamento->preco_unitario_mp, 8), $lancamento->qtd, 8);
				}

				$lancamento->negociacao_id = $negociacao->id;

				$lancamento->ativo_id = $ativo['id'];

				$lancamento->save();

				$ativo_posicao->preco_medio = $lancamento->preco_unitario_mp;

				$ativo_posicao->qtd = $lancamento->qtd_acumulada;

				$ativo_posicao->save();
			}

			DB::commit();

			return [
				'status' => 'success',
				'message' => 'Negociação lançada com sucesso'
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

			$this->data['ativos'][$key]['preco_unitario_bruto'] = realToDollar($ativo['preco']);

			$this->data['ativos'][$key]['preco_total_bruto'] = bcmul($this->data['ativos'][$key]['qtd'], $this->data['ativos'][$key]['preco_unitario_bruto'], 8);
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
