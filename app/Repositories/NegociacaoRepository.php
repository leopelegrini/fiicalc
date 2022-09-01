<?php

namespace App\Repositories;

use App\Models\Negociacao;
use Illuminate\Support\Facades\DB;

class NegociacaoRepository
{
	public function consultar()
	{
		$negociacoes = Negociacao::orderBy('data', 'desc')
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
