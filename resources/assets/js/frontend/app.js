var Loading = require('./components/Loading.vue');
var Content = require('./components/Content.vue');
var config = require('./components/Config.vue');
var PopularProducts = require('./components/PopularProducts.vue');
var AddToCart   = require('./components/AddToCart.vue');
var ShoppingCart = require('./components/ShoppingCart.vue');

module.exports = {
	http: {
		root: config['api_root'],
		headers: config['headers']
	},
    components: {
        'loading': Loading,
        'content': Content,
        'popular': PopularProducts,
        'add-to-cart': AddToCart,
        'shopping-cart': ShoppingCart
    },
    methods: {
    	loading(type, timeout) {
    		if (typeof type == 'undefined') {
    			type = 'show';
    		}

    		if (type == 'show') {
    			this.$broadcast('loading.show', {timeout: timeout});
    		} else if (type == 'hide') {
    			this.$broadcast('loading.hide');
    		}
    	},
    	loaded() {
    		this.$broadcast('loading.hide');
    	}
    }
}