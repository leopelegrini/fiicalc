@extends('inc.app')

@section('content')

	<div class="row nv-page-title-bar">
		<div class="col">
			<h5 class="nv-page-title">Negociação {{ $negociacao->numero }}</h5>
		</div>
	</div>

	<div class="nv-section">
		<a href="{{ route('negociacoes.index') }}" class="nv-btn nv-btn-light">
			<span class="la la-angle-left"></span>
		</a>
		<a href="{{ route('negociacoes.edit', $negociacao->id) }}" class="nv-btn nv-btn-light">
			<span class="la la-pencil btn-icon-left"></span>Editar
		</a>
		{{--
		<a href="{{ route('negociacoes.edit', $negociacao->id) }}" class="nv-btn nv-btn-light">
			<span class="la la-trash btn-icon-left"></span>Excluir
		</a>
		--}}
	</div>

	@include('inc.alert')

	<div class="nv-section">
		<table class="nv-table">
			<thead>
				<tr>
					<th class="collapsed">Ativo</th>
					<th class="text-right">Qtd</th>
					<th class="text-right">Unitário (R$)</th>
					<th class="text-right">Taxas (R$)</th>
					<th class="text-right">Total (R$)</th>
				</tr>
			</thead>
			<tbody>
				@foreach($negociacao->lancamentos as $lancamento)
				<tr>
					<td class="collapsed">{{ $lancamento->ativo->codigo }}</td>
					<td class="text-right">{{ $lancamento->qtd }}</td>
					<td class="text-right">{{ $lancamento->precoUnitarioPresenter() }}</td>
					<td class="text-right">{{ $lancamento->taxasPresenter() }}</td>
					<td class="text-right">{{ $lancamento->precoTotalPresenter() }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

@endsection
