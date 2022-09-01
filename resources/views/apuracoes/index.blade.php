@extends('inc.app')

@section('content')

	<div class="row nv-page-title-bar">
		<div class="col">
			<h5 class="nv-page-title">Apurações</h5>
		</div>
		@if($saldo_aberto['possui_valor_disponivel'])
		<div class="col text-right">
			<a href="{{ route('apuracoes.create') }}" class="nv-btn nv-btn-primary">
				Gerar apuração
			</a>
		</div>
		@endif
	</div>

	@include('inc.alert')

	<div class="nv-section">
		@if($saldo_aberto['possui_valor_disponivel'])
			<div class="alert alert-success">
				Valor disponível a apurar: <b>R$ {{ $saldo_aberto['valor_a_pagar'] }}</b>
			</div>
		@else
			<div class="alert alert-info">
				Valor aberto abaixo do mínimo para apuração. Saldo: R$ {{ $saldo_aberto['saldo_vendas'] }}
			</div>
		@endif
	</div>

	@if($apuracoes->count())

		<div class="nv-section">

			<table class="nv-table">
				<thead>
					<tr>
						<th class="collapsed">Ativo</th>
						<th class="text-right">Quantidade</th>
						<th class="text-right">Base cálculo (R$)</th>
						<th class="text-right">Alíquota (R$)</th>
						<th class="text-right">Valor apurado (R$)</th>
					</tr>
				</thead>
				<tbody>
					@foreach($apuracoes as $apuracao)
					<tr>
						<td class="collapsed">{{ $apuracao->codigo }}</td>
						<td class="text-right">{{ $apuracao->created_at->format('d/m/y') }}</td>
						<td class="text-right">{{ $apuracao->baseCalculoPresenter() }}</td>
						<td class="text-right">{{ $apuracao->aliquotaPresenter() }}</td>
						<td class="text-right">{{ $apuracao->valorApuradoPresenter() }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>

		</div>

	@else

		<div class="nv-panel">
			<div class="nv-panel-pad">
				<div class="alert alert-warning mb-0">Nenhuma apuração realizada.</div>
			</div>
		</div>

	@endif

@endsection
