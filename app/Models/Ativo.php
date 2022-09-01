<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ativo extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'ativos';

	public function precoMedioPresenter()
	{
		return dollarToReal($this->preco_medio);
	}
}
