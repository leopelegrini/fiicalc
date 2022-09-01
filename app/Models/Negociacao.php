<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Negociacao extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'negociacoes';

	public function lancamentos()
	{
		return $this->hasMany(Lancamento::class, 'negociacao_id', 'id');
	}

	public function dataPresenter()
	{
		return Carbon::createFromFormat('Y-m-d', $this->data)->format('d/m/y');
	}

	public function valorPresenter()
	{
		return dollarToReal($this->valor);
	}
}
