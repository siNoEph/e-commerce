<style>
	
</style>

<template>
	<button type="button" v-on:click="addToCart" class="btn btn-primary btn-block"><i class="fa fa-cart-arrow-down"></i> Add to cart</button>
</template>

<script lang="es6">
    var _ 		= require('underscore');
    var cookie 	= require('./../helpers/cookie.js');
    var shopping_cart = require('./../components/ShoppingCart.vue');

    module.exports = {
    	data() {
    		return {
    			cart: '',
    			count_shopping_cart: 0,
    		}
    	},

    	methods: {
    		addToCart() {
    			var cart = cookie.get('shopping_cart')
    			if (cart == "" || cart == undefined) {
    				this.cart 	= cart + '1'
    			} else {
    				this.cart 	= cart + ',' + '1'
    			}
    			cookie.set('shopping_cart', this.cart)

    			// shopping_cart.count_shopping_cart = shopping_cart.count_shopping_cart + 1

    			var item 	= cookie.get('shopping_cart')
    			console.log(item)
    		},

    		showCart() {
    			return cookie.get('shopping_cart')
    		}
    	},

    	ready() {
    		console.log(this.showCart())
    		console.log(shopping_cart.count_shopping_cart)
    	}
    }
</script>