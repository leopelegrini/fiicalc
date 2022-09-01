@extends('inc.app')

@section('content')

	<a href="{{ route('negociacoes.index') }}" class="nv-btn nv-btn-light">
		Voltar
	</a>

	{{ Form::open(['route' => 'apuracoes.store', 'method' => 'POST']) }}

	<div class="nv-section">

		<table class="nv-table">
			<thead>
				<tr>
					<th>Ativo</th>
					<th>Cód</th>
					<th>Data</th>
					<th class="text-right">Preço venda (R$)</th>
					<th class="text-right">Preço MP (R$)</th>
					<th class="text-right">Qtd</th>
					<th class="text-right">Saldo venda (R$)</th>
				</tr>
			</thead>
			<tbody>
				@foreach($lancamentos as $lancamento)
				<tr>
					<td>
						{{ $lancamento->ativo->codigo }}
						<input type="hidden" name="lancamentos[]" value="{{ $lancamento->id }}">
					</td>
					<td>
						<a href="{{ route('negociacoes.show', $lancamento->negociacao_id) }}">
							{{ $lancamento->negociacao->codigo }}
						</a>
					</td>
					<td>{{ $lancamento->dataPresenter() }}</td>
					<td class="text-right">{{ $lancamento->precoUnitarioLiquidoPresenter() }}</td>
					<td class="text-right">{{ $lancamento->precoUnitarioMpPresenter() }}</td>
					<td class="text-right">{{ $lancamento->qtd }}</td>
					<td class="text-right">{{ $lancamento->saldoVendaPresenter() }}</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="7" class="text-right">Total: R$ {{ $saldo_vendas }}</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right">A pagar (20%): <b>R$ {{ $valor_a_pagar }}</b></td>
				</tr>
			</tbody>
		</table>

		<div class="nv-section">
			<button type="submit" class="nv-btn nv-btn-primary">
				Salvar
			</button>
		</div>

	</div>

	{{ Form::close() }}

@endsection
