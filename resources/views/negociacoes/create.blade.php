@extends('inc.app')

@section('content')

	<a href="{{ route('negociacoes.index') }}" class="nv-btn nv-btn-light">
		Voltar
	</a>

	<div class="nv-panel">
		<div class="nv-panel-pad">

			{{ Form::open(['route' => 'ativos.store', 'method' => 'POST']) }}

			<div class="form-row">
				<div class="col-md-3">
					<label class="form-label">Data</label>
					<input type="date" class="form-input" placeholder="__/__/____" v-model="data">
				</div>
				<div class="col-md-3">
					<label class="form-label">Taxas (R$)</label>
					<nv-currency v-model="taxas" class="form-input text-right"></nv-currency>
				</div>
			</div>

			<hr>

			<div class="form-row align-items-end">
				<div class="col-md-3">
					<label for="" class="form-label">Ativo</label>
					<select class="form-input" v-model="ativo_aux.id" ref="input_ativo">
						<option value="">- selecione</option>
						@foreach($lista_ativos as $ativo)
						<option value="{{ $ativo->id }}">{{ $ativo->codigo }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3">
					<label for="" class="form-label">Quantidade</label>
					<nv-currency v-model="ativo_aux.qtd" class="form-input text-right" :places="0"></nv-currency>
				</div>
				<div class="col-md-3">
					<label for="" class="form-label">Preço</label>
					<nv-currency v-model="ativo_aux.preco" class="form-input text-right" :places="2"></nv-currency>
				</div>
				<div class="col-md-3">
					<button type="button" class="nv-btn nv-btn-primary nv-btn-block" @click="adicionarAtivo(ativo_aux)" :disabled="!ativo_aux.id || !ativo_aux.qtd || !ativo_aux.preco">
						Adicionar
					</button>
				</div>
			</div>

			<hr>

			<div v-if="ativos.length" class="mt-3">
				<table class="nv-table">
					<thead>
						<tr>
							<th>Ativo</th>
							<th class="text-right" style="width:120px;">Quantidade</th>
							<th class="text-right" style="width:120px;">Preço</th>
							<th class="collapsed"></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(ativo, index) in ativos">
							<td>
								<div style="width:200px;">
									<b>@{{ ativo.codigo }}</b>
								</div>
							</td>
							<td>
								<nv-currency v-model="ativo.qtd" class="form-input text-right" :places="0"></nv-currency>
								<div class="help-block help-block-danger" v-if="errors.ativos && errors.ativos[index] && errors.ativos[index].qtd">
									@{{ errors.ativos[index].qtd[0] }}
								</div>
							</td>
							<td>
								<nv-currency v-model="ativo.preco" class="form-input text-right" :places="2"></nv-currency>
								<div class="help-block help-block-danger" v-if="errors.ativos && errors.ativos[index] && errors.ativos[index].preco">
									@{{ errors.ativos[index].preco[0] }}
								</div>
							</td>
							<td class="collapsed">
								<button type="button" class="nv-btn nv-btn-light" @click="removerAtivo(index)">X</button>
							</td>
						</tr>
					</tbody>
				</table>
				<hr>
			</div>

			<button type="button" class="nv-btn nv-btn-primary" @click="salvar" :disabled="loading" :class="{'is-loading': loading}">
				Salvar
			</button>

			{{ Form::close() }}
		</div>
	</div>

@endsection

@section('scripts')
<script type="text/javascript">
	new Vue({
		el: '#vue-app',
		data: {
			lista_ativos: @json($lista_ativos),
			ativo_aux: {
				id: '',
				text: '',
				qtd: '',
				preco: ''
			},
			data: '{{ date("Y-m-d") }}',
			taxas: '0,00',
			ativos: [],
			loading: false,
			errors: []
		},
		methods: {
			adicionarAtivo: function(ativo){

				this.ativos.push({
					id: ativo.id,
					codigo: this.lista_ativos[ativo.id].codigo,
					qtd: ativo.qtd,
					preco: ativo.preco
				});

				this.ativo_aux.id = '';
				this.ativo_aux.text = '';
				this.ativo_aux.qtd = '';
				this.ativo_aux.preco = '';

				this.$refs.input_ativo.focus();
			},
			removerAtivo: function(index){
				this.ativos.splice(index, 1);
			},
			salvar: function(){

				let vm = this;

				vm.errors = [];

				//vm.loading = true;

				axios.post('{{ route("negociacoes.store") }}', {
					data: vm.data,
					taxas: vm.taxas,
					ativos: vm.ativos
				})
				.then(function(response){
					//window.location.href = '/negociacoes/' + response.data.data.negociacao.id;
				})
				.catch(function(error){

					vm.errors = error.response.data.hasOwnProperty('errors') ? error.response.data.errors : [];

					if(error.response.data.hasOwnProperty('message')){
						toastr.error(error.response.data.message);
					}

					vm.loading = false;
				});
			}
		}
	});
</script>
@endsection
