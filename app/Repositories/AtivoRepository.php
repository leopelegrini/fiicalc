<?php

namespace App\Repositories;

use App\Exceptions\ExceptionHandler;
use App\Models\Ativo;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AtivoRepository
{
	public function consultar()
	{
		try {

			$ativos = DB::table('ativos')
				->whereNull('deleted_at')
				->orderBy('codigo')
				->paginate(30);

			return [
				'status' => 'success',
				'data' => [
					'ativos' => $ativos
				]
			];
		}
		catch(Exception $e){
			return ExceptionHandler::getMessageByClass($e);
		}
	}

	public function create($data)
	{
		try {

			$validator = Validator::make($data, [
				'codigo' => [
					'bail',
					'required',
					function($attribute, $value, $fail){
						if(Ativo::where('codigo', $value)->first()){
							$fail("O ativo {$value} já está cadastrado");
						}
					}
				]
			]);

			if($validator->fails()){
				throw new ValidationException($validator);
			}

			$ativo = new Ativo();

			$ativo->codigo = $data['codigo'];

			$ativo->save();

			return [
				'status' => 'success',
				'message' => 'Ativo cadastrado com sucesso'
			];
		}
		catch(Exception $e){
			return ExceptionHandler::getMessageByClass($e);
		}
	}
}
