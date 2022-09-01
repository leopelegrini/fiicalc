<?php

namespace App\Http\Controllers;

use App\Actions\CadastrarApuracaoAction;
use App\Actions\ExcluirApuracaoAction;
use App\Models\Apuracao;
use App\Repositories\ApuracaoRepository;
use Illuminate\Http\Request;

class ApuracaoController extends Controller
{
	private $repository;

	public function __construct(ApuracaoRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index()
	{
		$saldo_aberto = $this->repository->consultarSaldoAberto();

		$apuracoes = Apuracao::orderBy('id', 'desc')->paginate(30);

		return view('apuracoes.index', [
			'saldo_aberto' => $saldo_aberto,
			'apuracoes' => $apuracoes
		]);
	}

	public function create()
	{
		$response = $this->repository->consultarDadosParaApuracao();

		return view('apuracoes.create', $response);
	}

	public function store(Request $request)
	{
		$action = new CadastrarApuracaoAction($request->all());

		$response = $action->execute();

		if($response['status'] != 'success'){
			return redirect()->back()->with('alert', $response);
		}

		return redirect()->route('apuracoes.show', $response['data']->id)->with('alert', $response);
	}

	public function show($id)
	{
		$apuracao = Apuracao::with('lancamentos.ativo', 'lancamentos.negociacao')->where('id', $id)->firstOrFail();

		return view('apuracoes.show', compact('apuracao'));
	}

	public function destroy($id)
	{
		$action = new ExcluirApuracaoAction();

		$response = $action->execute($id);

		if($response['status'] != 'success'){
			return redirect()->back()->with('alert', $response);
		}

		return redirect()->route('apuracoes.index')->with('alert', $response);
	}
}
