<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apuracao extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'apuracoes';

	public function lancamentos()
	{
		return $this->hasMany(Lancamento::class, 'apuracao_id', 'id');
	}

	public function baseCalculoPresenter()
	{
		return dollarToReal($this->base_calculo);
	}

	public function aliquotaPresenter()
	{
		return dollarToReal(bcmul($this->aliquota, 100, 0), 0) . '%';
	}

	public function valorApuradoPresenter()
	{
		return dollarToReal($this->valor_apurado);
	}
}
