<?php

namespace App\Actions;

use App\Exceptions\ExceptionHandler;
use App\Models\Apuracao;
use Exception;
use Illuminate\Support\Facades\DB;

class ExcluirApuracaoAction
{
	public function execute($id)
	{
		try {

			$apuracao = Apuracao::with('lancamentos')->where('id', $id)->firstOrFail();

			DB::beginTransaction();

			DB::table('lancamentos')->whereIn('id', $apuracao->lancamentos->pluck('id'))->update([
				'apuracao_id' => null
			]);

			$apuracao->delete();

			DB::commit();

			return [
				'status' => 'success',
				'message' => 'Apuração excluída com sucesso'
			];
		}
		catch(Exception $e){

			DB::rollBack();

			return ExceptionHandler::getMessageByClass($e);
		}
	}
}
