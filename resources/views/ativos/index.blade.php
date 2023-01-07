@extends('inc.app')

@section('content')

	<div class="row nv-page-title-bar">
		<div class="col">
			<h5 class="nv-page-title">Ativos</h5>
		</div>
		<div class="col text-right">
			<a href="{{ route('ativos.create') }}" class="nv-btn nv-btn-primary">Cadastrar ativo</a>
		</div>
	</div>

	@include('inc.alert')

	@if($ativos->count())

		<div class="nv-section">
			<table class="nv-table">
				<thead>
					<tr>
						<th class="collapsed">Código</th>
						<th>Categoria</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($ativos as $ativo)
					<tr>
						<td class="collapsed">
							<a href="{{ route('ativos.show', $ativo->id) }}">
								{{ $ativo->codigo }}
							</a>
						</td>
						<td></td>
						<td></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

	@else

		<div class="nv-panel">
			<div class="nv-panel-pad">
				<div class="alert alert-warning mb-0">Não há dados por enquanto</div>
			</div>
		</div>

	@endif

@endsection
