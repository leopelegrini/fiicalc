<?php

namespace App\Http\Controllers;

use App\Models\Ativo;
use App\Repositories\AtivoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtivoController extends Controller
{
	private $repository;

	public function __construct(AtivoRepository $repository)
	{
		$this->repository = $repository;
	}

	public function index()
	{
		$response = $this->repository->consultar();

		if($response['status'] != 'success'){
			abort(403);
		}

		return view('ativos.index', $response['data']);
	}

	public function create()
	{
		return view('ativos.create');
	}

	public function store(Request $request)
	{
		$response = $this->repository->create($request->all());

		if($response['status'] != 'success'){
			return redirect()->back()->with('alert', $response)->withErrors($response['errors'])->withInput();
		}

		return redirect()->route('ativos.index')->with('alert', $response);
	}

	public function show($id)
	{
		$ativo = Ativo::findOrFail($id);

		return view('ativos.show', [
			'ativo' => $ativo
		]);
	}

	public function autocomplete(Request $request)
	{
		$keyword = $request->get('term');

		if(!$keyword){
			return response()->json(['results' => []]);
		}

		$results = Ativo::select(['id', DB::raw('codigo as text')])
			->where('codigo', 'like', $keyword . '%')
			->orderBy('codigo')
			->limit(10)
			->get()
			->toArray();

		return response()->json(['results' => $results]);
	}
}
