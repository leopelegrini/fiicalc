<?php

namespace App\Repositories;

use App\Models\Lancamento;
use Illuminate\Support\Facades\DB;

class ApuracaoRepository
{
	public function consultarSaldoAberto()
	{
		$possui_valor_disponivel = false;

		$saldo_vendas = DB::table('lancamentos')
			->where('operacao', '=', 1)
			->whereNull('apuracao_id')
			->whereNull('deleted_at')
			->sum('saldo_venda');

		$valor_a_pagar = 0;

		if(bccomp($saldo_vendas, 0, 8) === 1){

			$valor_a_pagar = bcmul($saldo_vendas, '0.2', 8);

			if(bccomp($valor_a_pagar, 10, 8) === 1){
				$possui_valor_disponivel = true;
			}
		}

		return [
			'possui_valor_disponivel' => $possui_valor_disponivel,
			'saldo_vendas' => dollarToReal($saldo_vendas),
			'valor_a_pagar' => dollarToReal($valor_a_pagar)
		];
	}

	public function consultarDadosParaApuracao()
	{
		$lancamentos = Lancamento::with('ativo', 'negociacao')
			->where('operacao', '=', 1)
			->whereNull('apuracao_id')
			->orderBy('data', 'asc')
			->get();

		$saldo_vendas = $lancamentos->sum('saldo_venda');

		$valor_a_pagar = bcmul($saldo_vendas, '0.2', 8);

		return [
			'lancamentos' => $lancamentos,
			'saldo_vendas' => dollarToReal($saldo_vendas),
			'valor_a_pagar' => dollarToReal($valor_a_pagar)
		];
	}
}
