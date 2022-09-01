<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ config('app.name', 'FII Calc') }}</title>
	<link rel="stylesheet" href="{{ url('lib/bootstrap/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ url('css/app.css') }}?v={!! filemtime('css/app.css') !!}">
</head>
<body>

	<div class="app-container">
    	<header class="app-header">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-light text-decoration-none">
                    FII CALC
                </a>
                <ul class="nav nav-pills ml-auto">
                    <li><a href="{{ route('home') }}" class="nav-link px-2">Início</a></li>
                    <li><a href="{{ route('ativos.index') }}" class="nav-link px-2">Ativos</a></li>
                    <li><a href="{{ route('negociacoes.index') }}" class="nav-link px-2">Negociações</a></li>
                    {{--
                    <li class="ms-2">
                        <a href="/logout" class="btn btn-outline-primary me-2">Sair</a>
                    </li>
                    --}}
                </ul>
            </div>
    	</header>
	</div>

	<div class="app-stage" id="vue-app">
		<div class="app-container">

			@yield('content')

		</div>
	</div>

	<script type="text/javascript" src="{{ asset('lib/jquery/jquery-3.4.1.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/bootstrap/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/popper/popper.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/axios/axios.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/accounting/accounting.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/vue/vue.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/lodash/lodash.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/nv-autocomplete/nv-autocomplete.js') }}"></script>
	<script type="text/javascript" src="{{ asset('lib/nv-currency/nv-currency.js') }}"></script>

	@yield('scripts')

</body>
</html>