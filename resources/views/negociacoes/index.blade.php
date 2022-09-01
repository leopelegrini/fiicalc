@extends('inc.app')

@section('content')

	<div class="row nv-page-title-bar">
		<div class="col">
			<h5 class="nv-page-title">Negociações</h5>
		</div>
		<div class="col text-right">
			<a href="{{ route('negociacoes.create') }}" class="nv-btn nv-btn-primary">
				Lançar negociação
			</a>
		</div>
	</div>

	@include('inc.alert')

	@if($negociacoes->count())

		<div class="nv-section">
			<table class="nv-table">
				<thead>
					<tr>
						<th class="collapsed">Código</th>
						<th>Data</th>
						<th class="text-right">Valor (R$)</th>
						<th class="collapsed"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($negociacoes as $n)
					<tr>
						<td class="collapsed">
							<a href="{{ route('negociacoes.show', $n->id) }}">
								{{ $n->codigo }}
							</a>
						</td>
						<td>{{ $n->dataPresenter() }}</td>
						<td class="text-right">{{ $n->valorPresenter() }}</td>
						<td class="collapsed">
							<a href="{{ route('negociacoes.show', $n->id) }}" class="nv-btn nv-btn-light nv-btn-sm">
								<span class="la la-angle-right"></span>
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

	@else

		<div class="nv-panel">
			<div class="nv-panel-pad">
				<div class="alert alert-warning mb-0">Nenhuma negociação por enquanto</div>
			</div>
		</div>

	@endif

@endsection
