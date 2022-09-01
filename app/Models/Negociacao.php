<?php

namespace App\Models;

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
}
