Vue.component('nv-currency', {
	props: ['value', 'places'],
	template: '<input v-bind:value="value" v-on:input="$emit(\'input\', $event.target.value)" v-on:keypress="filterNumbers" v-on:focus="selectAll" v-on:blur="formatValue">',
	methods: {
		filterNumbers: function(evt){

			evt = (evt) ? evt : window.event;

			let charCode = (evt.which) ? evt.which : evt.keyCode;

			if(charCode == 13){
				this.formatValue();
				return true;
			}
			else if((charCode >= 48 && charCode <= 57) || (charCode >= 44 && charCode <= 45)){
				return true;
			}

			evt.preventDefault();
		},
		formatValue: function(){

			if(this.value === ''){
				return;
			}

			let value = accounting.unformat(this.value, ",");

			let n = accounting.formatNumber(value, this.decimals, ".", ",");

			this.$emit('input', n);
		},
		selectAll: function(event){
			setTimeout(function(){
				event.target.select()
			}, 0)
		}
	},
	computed: {
		decimals: function(){
			return typeof this.places != 'undefined' && this.places !== null ? this.places : 2;
		}
	}
});
