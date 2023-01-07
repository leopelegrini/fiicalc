<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	public function index()
	{
		$valor_total = DB::table('ativos')->select([
			DB::raw('SUM(qtd * preco_medio) as valor_total')
		])
		->where('qtd', '>', 0)
		->first()
		->valor_total;

		$ativos = Ativo::select([
			'id',
			'codigo',
			'qtd',
			'preco_medio',
			DB::raw('qtd * preco_medio AS valor')
		])
		->where('qtd', '>', 0)
		->orderBy('codigo', 'asc')
		->get()
		->each(function($ativo) use($valor_total) {
			$ativo->alocacao = dollarToReal(
				bcdiv(bcmul($ativo->valor, 100, 8), $valor_total, 8)
			);
		});

		return view('index', [
			'ativos' => $ativos,
			'valor_total' => dollarToReal($valor_total)
		]);
	}
}
