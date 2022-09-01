<?php

namespace App\Actions;

use App\Exceptions\ArrayValidationException;
use App\Exceptions\ExceptionHandler;
use App\Models\Apuracao;
use App\Models\Lancamento;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CadastrarApuracaoAction
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function execute()
	{
		try {

			$lancamentos = Lancamento::whereIn('id', $this->data)->get();

			DB::beginTransaction();

			$apuracao = new Apuracao();

			$apuracao->ano = date('y');

			$apuracao->numero = $this->gerarNumero();

			$apuracao->codigo = $apuracao->ano . '.' . str_pad($apuracao->numero, '3', '0', STR_PAD_LEFT);

			$apuracao->base_calculo = $lancamentos->sum('saldo_venda');

			$apuracao->aliquota = 0.2;

			$apuracao->valor_apurado = bcmul($apuracao->base_calculo, $apuracao->aliquota, 8);

			$apuracao->status = 0;

			$apuracao->save();

			DB::table('lancamentos')->whereIn('id', $lancamentos->pluck('id'))->update([
				'apuracao_id' => $apuracao->id
			]);

			DB::commit();

			return [
				'status' => 'success',
				'message' => 'Apuração gerada com sucesso',
				'data' => $apuracao
			];
		}
		catch(Exception $e){

			DB::rollBack();

			return ExceptionHandler::getMessageByClass($e);
		}
	}

	private function gerarNumero()
	{
		$ultimo_numero = DB::table('apuracoes')
			->select('numero')
			->where('ano', '=', date('y'))
			->orderBy('numero', 'desc')
			->first();

		return $ultimo_numero ? $ultimo_numero->numero + 1 : 1;
	}
}
