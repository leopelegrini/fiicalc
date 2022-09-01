<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	private $repository;

	public function __construct()
	{
	}

	public function index()
	{
		/*
		DB::table('ativos')
			->where('qtd', '>', 0)
			->orderBy('id', 'asc')
			->get();
		*/

		return view('index');
	}
}
