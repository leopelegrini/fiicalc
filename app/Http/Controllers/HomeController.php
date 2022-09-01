<?php

namespace App\Http\Controllers;

use App\Models\Ativo;

class HomeController extends Controller
{
	public function index()
	{
		$ativos = Ativo::where('qtd', '>', 0)
			->orderBy('codigo', 'asc')
			->get();

		return view('index', compact('ativos'));
	}
}
