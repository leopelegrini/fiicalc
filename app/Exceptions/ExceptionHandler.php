<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class ExceptionHandler
{
	public static function getMessageByClass($exception)
	{
		switch(get_class($exception)){
			case ValidationException::class:
				$error = [
					'status'  => 'danger',
					'message' => 'Verifique as informações preenchidas',
					'errors'  => $exception->validator->getMessageBag()
				];
				break;
			case ArrayValidationException::class:
				$error = [
					'status' => 'danger',
					'message' => 'Verifique as informações preenchidas',
					'errors' => json_decode($exception->getMessage())
				];
				break;
			default:
				$error = [
					'status' => 'danger',
					'message' => $exception->getMessage()
				];
		}

		if(!isset($error['errors'])){
			$error['errors'] = [];
		}

		report($exception);

		return $error;
	}
}
