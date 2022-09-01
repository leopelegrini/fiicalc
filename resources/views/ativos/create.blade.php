@extends('inc.app')

@section('content')

	<a href="{{ route('ativos.index') }}" class="nv-btn nv-btn-light">
		Voltar
	</a>

	<div class="nv-panel">
		<div class="nv-panel-pad">

			@include('inc.alert')

			{{ Form::open(['route' => 'ativos.store', 'method' => 'POST']) }}

			<div class="nv-form-row">
				<div class="col-label">
					{{ Form::label('codigo', 'Código', ['class' => 'form-label']) }}
				</div>
				<div>
					<div class="col-input">
						{{ Form::text('codigo', null, ['class' => 'form-input']) }}
					</div>
					@error('codigo')
						<div class="help-block help-block-danger">{{ $message }}</div>
					@enderror
				</div>
			</div>

			<hr>

			<div class="nv-form-row">
				<div class="col-offset">
					<button type="submit" class="nv-btn nv-btn-primary">
						Gravar
					</button>
				</div>
			</div>

			{{ Form::close() }}
		</div>
	</div>

@endsection
