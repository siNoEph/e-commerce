<style>
	
</style>

<template>
		<!-- start:popular-product -->
		<div class="popular-product">
			<div class="container">
				<div class="popular-product-title">
					<img src="../assets/images/icon/bookmark.svg" alt="">
	            	<h4>Popular Products</h4>
	            </div>

	        	<div class="slick-popular-product">
				    <div>
					    <h3><a href="#"><img src="../assets/images/hijab2.jpg" alt=""></a></h3>
				    </div>
				    <div>
					    <h3><a href="#"><img src="../assets/images/hijab2.jpg" alt=""></a></h3>
				    </div>
				    <div>
					    <h3><a href="#"><img src="../assets/images/hijab2.jpg" alt=""></a></h3>
				    </div>
				    <div>
					    <h3><a href="#"><img src="../assets/images/hijab2.jpg" alt=""></a></h3>
				    </div>
				    <div>
					    <h3><a href="#"><img src="../assets/images/hijab2.jpg" alt=""></a></h3>
				    </div>
				    <div>
					    <h3><a href="#"><img src="../assets/images/hijab2.jpg" alt=""></a></h3>
				    </div>
				</div>
	        </div>
		</div>
		<!-- end:/popular-product -->
</template>

<script lang="es6">
    var _ = require('underscore');

    module.exports = {
    	data() {
			return {
				popular_products: null,
			}
		},

    	methods: {
    		show() {
    			this.$http.get('products').then(function (response) {
					this.popular_products = response.data.products
					for (var i = 0; i<popular_products.length; i++) {
						console.log(popular_products[i].images[0])
					}
				}, function (response) {
					console.log(response)
					// Error proccess API
				});
    		}
    	},

    	ready() {
    		this.show()
    	}
    }
</script>

