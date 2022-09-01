@extends('inc.app')

@section('content')

	<div class="row nv-page-title-bar">
		<div class="col">
			<h5 class="nv-page-title">Posição atual</h5>
		</div>
	</div>

	@if($ativos->count())

		<div class="nv-section">

			<table class="nv-table">
				<thead>
					<tr>
						<th class="collapsed">Ativo</th>
						<th class="text-right">Quantidade</th>
						<th class="text-right">Preço médio (R$)</th>
					</tr>
				</thead>
				<tbody>
					@foreach($ativos as $ativo)
					<tr>
						<td class="collapsed">{{ $ativo->codigo }}</td>
						<td class="text-right">{{ $ativo->qtd }}</td>
						<td class="text-right">{{ $ativo->precoMedioPresenter() }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>

		</div>

	@else

		<div class="nv-panel">
			<div class="nv-panel-pad">
				<div class="alert alert-warning mb-0">Não há dados por enquanto.</div>
			</div>
		</div>

	@endif

@endsection
