<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lancamento extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'lancamentos';

	public function ativo()
	{
		return $this->belongsTo(Ativo::class, 'ativo_id', 'id');
	}

	public function negociacao()
	{
		return $this->belongsTo(Negociacao::class, 'negociacao_id', 'id');
	}

	public function dataPresenter()
	{
		return Carbon::createFromFormat('Y-m-d', $this->data)->format('d/m/y');
	}

	public function precoUnitarioPresenter()
	{
		return dollarToReal($this->preco_unitario_bruto);
	}

	public function precoUnitarioLiquidoPresenter()
	{
		return dollarToReal($this->preco_unitario_liquido);
	}

	public function taxasPresenter()
	{
		return dollarToReal($this->taxas);
	}

	public function precoTotalPresenter()
	{
		return dollarToReal($this->preco_total_liquido);
	}

	public function saldoVendaPresenter()
	{
		return dollarToReal($this->saldo_venda);
	}

	public function precoUnitarioMpPresenter()
	{
		return dollarToReal($this->preco_unitario_mp);
	}
}
