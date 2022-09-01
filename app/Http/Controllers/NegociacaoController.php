<?php

namespace App\Http\Controllers;

use App\Actions\CadastrarNegociacaoAction;
use App\Models\Ativo;
use App\Repositories\NegociacaoRepository;
use Illuminate\Http\Request;

class NegociacaoController extends Controller
{
	private $repository;

	public function __construct(NegociacaoRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index()
	{
		$response = $this->repository->consultar();

		return view('negociacoes.index', $response['data']);
	}

	public function create()
	{
		$lista_ativos = Ativo::select('id', 'codigo')->orderBy('codigo')->get()->keyBy('id');

		return view('negociacoes.create', compact('lista_ativos'));
	}

	public function store(Request $request)
	{
		$action = new CadastrarNegociacaoAction($request->all());

		return $this->respond($action->execute());
	}
}
