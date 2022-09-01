<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lancamento extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'lancamentos';

	public function lancamento_anterior()
	{
		return $this->hasOne(Lancamento::class, 'ativo_id', 'ativo_id')
			->where('id', '!=', $this->id)
			->orderBy('id', 'desc');
	}
}
