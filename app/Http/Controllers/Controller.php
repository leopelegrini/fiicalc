<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function respond($response, $flash = false)
	{
		$status = $response['status'] != 'success' ? 422 : 200;

		if($flash && $status == 200){
			session()->flash('alert', $response);
		}

		return response()->json($response, $status);
	}
}
