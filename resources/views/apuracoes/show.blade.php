@extends('inc.app')

@section('content')

	<div class="row nv-page-title-bar">
		<div class="col">
			<h5 class="nv-page-title">Apuração {{ $apuracao->codigo }}</h5>
		</div>
	</div>

	<div class="nv-section d-flex">
		<a href="{{ route('apuracoes.index') }}" class="nv-btn nv-btn-light mr-2">
			<span class="la la-angle-left"></span>
		</a>
		{{ Form::open(['route' => ['apuracoes.destroy', $apuracao->id], 'method' => 'DELETE']) }}
			<button type="submit" class="nv-btn nv-btn-light">
				<span class="la la-trash btn-icon-left"></span>Excluir
			</button>
		{{ Form::close() }}
	</div>

	@include('inc.alert')

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
				@foreach($apuracao->lancamentos as $lancamento)
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
					<td colspan="7" class="text-right">Total: R$ {{ $apuracao->baseCalculoPresenter() }}</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right">Valor apurado ({{ $apuracao->aliquotaPresenter() }}): <b>R$ {{ $apuracao->valorApuradoPresenter() }}</b></td>
				</tr>
			</tbody>
		</table>
	</div>

@endsection
