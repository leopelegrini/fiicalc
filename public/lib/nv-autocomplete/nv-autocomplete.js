Vue.directive('click-outside', {
	bind: function (el, binding, vnode) {
		el.clickOutsideEvent = function (event) {
			if (!(el == event.target || el.contains(event.target))) {
				vnode.context[binding.expression](event);
			}
		};
		document.body.addEventListener('click', el.clickOutsideEvent);
	},
	unbind: function (el) {
		document.body.removeEventListener('click', el.clickOutsideEvent);
	}
});

Vue.component('nv-autocomplete', {
	props: {
		value: {
			type: Object,
			default: () => {}
		},
		mode: {
			type: String,
			default: () => 'server'
		},
		selectedText: {
			type: String,
			default: () => ''
		},
		placeholderText: {
			type: String,
			default: () => ''
		},
		url: {
			type: String,
			default: () => ''
		},
		searchLength: {
			type: Number,
			default: () => 3
		},
		clearButton: {
			type: Boolean,
			default: () => true
		},
		onlyNumbers: {
			type: Boolean,
			default: () => false
		},
		options: {
			type: Array,
			default: () => []
		}
	},
	data: function(){
		return {
			searchTerm: '',
			dataset: [],
			isOpen: false,
			loading: false,
			arrowCounter: -1
		}
	},
	methods: {
		selectItem: function(item){
			this.$emit('input', item);
			this.$emit('update:selectedText', item.text);
			this.closeSearch();
			if(this.mode == 'server'){
				this.searchTerm = '';
				this.dataset = [];
			}
		},
		clearItem: function(){
			this.$emit('input', {
				id: null,
				text: null
			});
			this.$emit('update:selectedText', '');
		},
		openSearch: function(){

			this.isOpen = true;

			let vm = this;

			setTimeout(function(){
				vm.$refs.refInputSearchTerm.focus();
			}, 100);
		},
		closeSearch: function(){
			this.isOpen = false;
		},
		search: function(){

			if(this.onlyNumbers && this.searchTerm){
				this.searchTerm = this.searchTerm.replace(/\D/g, '');
			}

			if(this.mode == 'server'){
				if(this.searchTerm.length >= this.searchLength){
					this.loading = true;
					this.requestData();
				}
				else {
					this.loading = false;
				}
			}
			else {
				this.searchByText();
			}
		},
		requestData: _.debounce(function(){

			let vm = this;

			axios.get(this.url, {
				params: {
					term: vm.searchTerm
				}
			})
			.then(function(response){
				vm.dataset = response.data.results;
			})
			.catch(function(error){
				console.log(error);
			})
			.then(function(){
				vm.loading = false;
			});
		}, 500),
		searchByText() {
			if(this.searchTerm){
				this.dataset = this.options.filter((item) => {
					return this.searchTerm.split(' ').every(v => item.text.toLowerCase().includes(v))
				});
			}
			else{
				this.dataset = this.options;
			}
		},
		onArrowDown() {

			if(this.dataset.length == 0){
				return;
			}

			if (this.arrowCounter < this.dataset.length - 1) {
				this.arrowCounter = this.arrowCounter + 1;
			}
			this.syncScroll();
		},
		onArrowUp() {

			if(this.dataset.length == 0){
				return;
			}

			if (this.arrowCounter > 0) {
				this.arrowCounter = this.arrowCounter - 1;
			}
			this.syncScroll();
		},
		onEnter() {

			event.stopPropagation();

			if(this.arrowCounter > -1){
				this.selectItem(this.dataset[this.arrowCounter]);
				this.closeSearch();
				this.arrowCounter = -1;
			}
		},
		onEsc() {
			this.closeSearch();
		},
		onTab() {
			this.closeSearch();
		},
		syncScroll() {

			const $container = this.$refs.refResults;

			const $item = this.$refs['refItem' + this.arrowCounter];

			$container.scrollTop = $item[0] ? $item[0]['offsetTop'] : 0;
		}
	},
	computed: {
		message: function(){
			if(this.searchTerm.length <= 2){
				return 'Digite 2 ou mais caracteres para pesquisar';
			}
			else if(this.loading){
				return 'Pesquisando...';
			}
			else if(this.dataset.length == 0){
				return 'Nenhum resultado encontrado'
			}
		}
	},
	mounted: function(){
		if(this.mode === 'select'){
			this.dataset = this.options;
		}
	},
	template: `
		<div class="nv-autocomplete" v-click-outside="closeSearch">
			<div class="nv-autocomplete-display">
				<input type="text" class="form-input nv-autocomplete-display-input" :value="selectedText" :placeholder="placeholderText" @click="openSearch" @focus="openSearch" v-on:keyup.enter="openSearch" readonly>
				<button type="button" class="nv-autocomplete-clear-button" title="Limpar campo" v-if="value.id && clearButton" @click="clearItem">&times;</button>
			</div>
			<div class="nv-autocomplete-dropdown" v-show="isOpen">
				<div class="nv-autocomplete-search-input-container">
					<input type="text" class="form-input nv-autocomplete-form-input"
						v-model="searchTerm"
						@input="search"
						@keydown.down="onArrowDown"
						@keydown.up="onArrowUp"
						@keydown.enter.prevent="onEnter"
						@keydown.esc="onEsc"
						@keydown.tab="onTab"
						ref="refInputSearchTerm">
				</div>
				<ul class="nv-autocomplete-results" v-if="dataset.length > 0" ref="refResults">
					<li v-for="(item, i) in dataset" :key="i" @click="selectItem(item)" :class="{'is-active': i === arrowCounter}" :ref="'refItem' + i">
						{{ item.text }}
					</li>
				</ul>
				<div class="nv-autocomplete-message" v-if="dataset.length == 0">
					{{ message }}
				</div>
			</div>
		</div>
	`
});
