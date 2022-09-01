<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class NegociacaoRepository
{
	public function consultar()
	{
		$negociacoes = DB::table('negociacoes')
			->whereNull('deleted_at')
			->orderBy('data', 'desc')
			->orderBy('created_at', 'desc')
			->paginate(30);

		return [
			'status' => 'success',
			'data' => [
				'negociacoes' => $negociacoes
			]
		];
	}
}
