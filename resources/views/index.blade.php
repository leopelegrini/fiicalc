@extends('inc.app')

@section('content')

	<div class="row nv-page-title-bar">
		<div class="col">
			<h5 class="nv-page-title">Posição atual</h5>
		</div>
	</div>

	@if(true)

		<div class="nv-section">

			<table class="nv-table">
				<thead>
					<tr>
						<th class="collapsed">Horário</th>
						<th>Ação</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="collapsed">item.data_hora</td>
						<td>item.status</td>
					</tr>
				</tbody>
			</table>

		</div>

	@else

		<div class="alert alert-warning mb-0">Não há dados por enquanto</div>

	@endif

@endsection
