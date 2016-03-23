<style>
	
</style>

<template>
	<div class="cart pull-right">
		<a href="#collapseChart" data-toggle="collapse">
			<img v-bind:src="cart_image" alt="">
			<p>{{ count_shopping_cart }}</p>
		</a>
	</div>
</template>

<script lang="es6">
    var cookie 	= require('./../helpers/cookie.js');
	var config 	= require('./../config.js');

	module.exports = {
    	data() {
    		return {
    			cart_image: config.SITE_URL + '/assets/images/icon/chart.svg',
    			count_shopping_cart: 0,
    			shopping_cart: null,
    		}
    	},

    	methods: {
    		getCart() {
    			var get_cart 		= cookie.get('shopping_cart')

    			if (get_cart == "" || get_cart == undefined) {
    				this.count_shopping_cart = 0
    			} else {
    				var searchComma = get_cart.search(',')

    				if (searchComma > 0) {
	    				var shopping_cart 	= get_cart.split(',') 
		    			for (var i = 0; i < shopping_cart.length; i++) {
		    				this.count_shopping_cart = i
		    			}
    				} else {
    					this.count_shopping_cart = 1
    				}
    			}
    		}
    	},

    	ready() {
    		// cookie.forget('shopping_cart')
    		this.getCart()
    	}
    }
</script>