<style>
	
</style>

<template>

		<div class="panel panel-flat">
			<div class="panel-heading">
				<legend class="text-bold panel-title">Products</legend>
			</div>
			<div class="panel-body">
				<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_add"><i class="icon-plus22 position-right"></i> Add Product</button>

				<table class="table datatable-basic">
					<thead>
						<tr>
							<th><strong>No</strong></th>
							<th><strong>Code</strong></th>
							<th><strong>Product Name</strong></th>
							<th><strong>Product Image</strong></th>
							<th><strong>Description</strong></th>
							<th><strong>Price</strong></th>
							<th class="text-center"><strong>Actions</strong></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="product in products">
							<td>{{ product.id }}</td>
							<td>{{ product.code }}</td>
							<td>{{ product.name }}</td>
							<td>
								<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal_newImages" v-on:click="newUploadImage(product)">Tambah Gambar</button>
								<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal_viewImages" v-on:click="viewImages(product)">Lihat Gambar</button>
							</td>
							<td>{{ product.description }}</td>
							<td>{{ product.price | currency }}</td>
							<td class="text-center">
								<a v-on:click="editProduct(product)" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal_edit" data-popup="tooltip" title="" data-original-title="Edit" title="Edit"><i class="fa fa-pencil"></i></a>
								<a v-on:click="delete(product)" class="btn btn-default btn-xs" data-popup="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Modal Add Images Product -->
		<div id="modal_viewImages" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" v-on:click="closeViewImages()" data-dismiss="modal">&times;</button>
						<h3 class="modal-title">Images Product</h3>
					</div>

					<div class="modal-body">
						<div class="row">
							<div v-for="image in images_product" class="col-lg-3 col-sm-6">
								<div class="thumbnail">
									<div class="thumb">
										<img src="{{ image_url }}/{{image.name}}" alt="">
										<div class="caption-overflow">
											<span>
												<a href="{{ image_url }}/{{image.name}}" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-eye4"></i></a>
												<a href="#" v-on:click="deleteImage(image)" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-trash" title="delete"></i></a>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="pull-right">
							<button type="button" v-on:click="deleteAllImages(product)" class="btn btn-primary">Delete All Images</button>
						</div>
					</div>

					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>
		<!-- /Modal Add Images Product -->

		<!-- Modal Add Images Product -->
		<div id="modal_newImages" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title">Add Images Product</h3>
					</div>

					<div class="modal-body">
						<form class="form-horizontal" v-on:submit.prevent="uploadImages(product)">
							<fieldset class="content-group">
								
								<div class="form-group">
			                    	<label class="control-label col-lg-4">Images</label>
									<div class="col-lg-8">
										<input class="btn btn-default btn-xs" type="file" id="images" name="images[]" v-el="fileInput" v-model="" multiple/>
									</div>
			                    </div>

							</fieldset>

							<div class="pull-right">
								<button type="reset" class="btn btn-default">Reset</button>
								<button type="submit" class="btn btn-primary">Add Images Product</button>
							</div>
						</form>
					</div>

					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>
		<!-- /Modal Add Images Product -->

		<!-- Modal Add Products -->
		<div id="modal_add" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title">Add Product</h3>
					</div>

					<div class="modal-body">
						<form class="form-horizontal" v-on:submit.prevent="addProduct()">
							<fieldset class="content-group">
								
								<div class="form-group">
			                    	<label class="control-label col-lg-4">Code</label>
									<div class="col-lg-8">
										<input v-model="newProduct.code" required type="text" class="form-control">
									</div>
			                    </div>

			                    <div class="form-group">
			                    	<label class="control-label col-lg-4">Category</label>
			                    	<div class="col-lg-8">
			                            <select v-model="newProduct.category_id" required name="select" class="form-control">
			                                <option value="" selected>--- None ---</option>
			                                <option v-for="category in categories" value="{{ category.id }}">{{ category.name }}</option>
			                            </select>
			                        </div>
			                    </div>

								<div class="form-group">
									<label class="control-label col-lg-4">Products Name</label>
									<div class="col-lg-8">
										<input v-model="newProduct.name" required type="text" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-lg-4">Price</label>
									<div class="col-lg-8">
										<input v-model="newProduct.price" required type="text" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-lg-4">Stock</label>
									<div class="col-lg-8">
										<input v-model="newProduct.stock" required type="number" min="1" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-lg-4">Description</label>
									<div class="col-lg-8">
										<textarea v-model="newProduct.description" required class="form-control" rows="5" cols="5"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-lg-4">Tags</label>
									<div class="col-lg-8">
										<input v-model="newProduct.tags" type="text" class="form-control">
									</div>
								</div>

							</fieldset>

							<div class="pull-right">
								<button type="reset" class="btn btn-default">Reset</button>
								<button type="submit" class="btn btn-primary">Add Products</button>
							</div>
						</form>
					</div>

					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>
		<!-- /Modal Add Products -->

		<!-- Modal Edit Products -->
		<div id="modal_edit" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" v-on:click="cancelEdit(editedProduct)" data-dismiss="modal">&times;</button>
						<h3 class="modal-title">Edit Products</h3>
					</div>

					<div class="modal-body">
						<form class="form-horizontal" v-on:submit.prevent="updateProduct(editedProduct)">
							<fieldset class="content-group">
								
								<div class="form-group">
			                    	<label class="control-label col-lg-4">Code</label>
									<div class="col-lg-8">
										<input v-model="editedProduct.code" required type="text" class="form-control">
									</div>
			                    </div>

			                    <div class="form-group">
			                    	<label class="control-label col-lg-4">Category</label>
			                    	<div class="col-lg-8">
			                            <select v-model="editedProduct.category_id" required name="select" class="form-control">
			                                <option value="" selected>--- None ---</option>
			                                <option v-for="category in categories" value="{{ category.id }}">{{ category.name }}</option>
			                            </select>
			                        </div>
			                    </div>

								<div class="form-group">
									<label class="control-label col-lg-4">Products Name</label>
									<div class="col-lg-8">
										<input v-model="editedProduct.name" required type="text" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-lg-4">Price</label>
									<div class="col-lg-8">
										<input v-model="editedProduct.price | currency" required type="text" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-lg-4">Stock</label>
									<div class="col-lg-8">
										<input v-model="editedProduct.stock" required type="number" min="1" class="form-control">
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-lg-4">Description</label>
									<div class="col-lg-8">
										<textarea v-model="editedProduct.description" required class="form-control" rows="5" cols="5"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-lg-4">Tags</label>
									<div class="col-lg-8">
										<input v-model="editedProduct.tags | pluck" type="text" class="form-control">
									</div>
								</div>

							</fieldset>

							<div class="pull-right">
								<button type="reset" class="btn btn-default">Reset</button>
								<button type="submit" class="btn btn-primary">Update Products</button>
							</div>	

						</form>
					</div>

					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>
		<!-- /Modal Add Products -->

</template>

<script lang="es6">
    var _ = require('underscore');

	var objProduct = function () {
		return {
			id: 0,
			code: '',
			name: '',
			description: '',
			price: '',
			stock: 0,
			tags: '',
			category_id: 0,
			created_at: null,
			updated_at: null
		}
	}

	var objImageProduct = function () {
		return {
			base64_image: '',
			product_id: ''
		}
	}

	module.exports = {
		data() {
			return {
				products: null,
				newProduct: new objProduct(),
				editedProduct: new objProduct(),
				product: new objProduct(),
				images_product: null,
				image_url: null,
				newImageProduct: new objImageProduct(),
				editedImageProduct: new objImageProduct(),
				categories: null,
			}
		},

		filters: {
			'pluck': require('./../filters/pluck.js'),
			'currency': require('./../filters/currency.js'),
		},

		methods: {
			/**
			 * Mengambil semua data produk supplier dari database
			 * @return {void}
			 */
			fetch() {
				this.$http.get('products/supplier').then(function (response) {
					this.products 		= response.data.products;
					this.image_url		= response.data.image_url;
					this.$http.get('categories').then(function (response) {
						this.categories = response.data.categories;
					}, function (response) {
						// Error proccess API
					});
				}, function (response) {
					// Error proccess API
				});
			},

			/**
			 * Membuat produk baru
			 * @return {void}
			 */
			addProduct() {
				var product = this.newProduct;

				product.price = product.price.split(',').join('').split('.').join('');

				this.$http.post('products', product).then(function (response) {
					var data = response.data;

					if (data.status_code == 422) {
						this.error = data.message;
					} else if (data.status == 'success') {
						$('#modal_add').modal('hide');
						this.products.push(data.product);
						this.resetAdd();
					}
				}, function (response) {
					// Error proccess API
				});
			},

			/**
			 * Menghapus data newProduct
			 * @return {void}
			 */
			resetAdd() {
				this.newProduct = new objProduct();
			},

			/**
			 * Memasukkan produk yang akan di edit ke variabel modify dan beforeModify
			 * @param  {object}  produk Produk yang akan di perbarui
			 * @return {void}
			 */
			editProduct(product) {
				// Sebelum di edit, data disimpan ke dalam cache
				this.beforeEditCache = _.clone(product);

				// Data dimasukkan ke variabel editedProduct
				this.editedProduct = product;			},

			/**
			 * Menghapus data modify menjadi default
			 * @param  {object} product
			 * @return {void}
			 */
			cancelEdit(product) {
				// Mengembalikan data dari cache
				product.code 		= this.beforeEditCache.code;
				product.name 		= this.beforeEditCache.name;
				product.description = this.beforeEditCache.description;
				product.price 		= this.beforeEditCache.price;
				product.stock 		= this.beforeEditCache.stock;
				product.tags 		= this.beforeEditCache.tags;
				product.category_id = this.beforeEditCache.category_id;

				this.beforeEditCache 	= new objProduct();
				this.editedProduct 		= new objProduct();
			},

			/**
			 * Memperbaui produk
			 * @param  {object} product
			 * @return {void}
			 */
			updateProduct(product) {
				this.$http.put('products/' + product.id, product).then(function (response) {
					this.beforeEditCache 	= new objProduct();
					this.editedProduct 		= new objProduct();
					$('#modal_edit').modal('hide');
				}, function (response) {
					// Error proccess API
				})
			},

			/**
			 * Menghapus produk
			 * @param  {object} product
			 * @return {void}
			 */
			delete(product) {
				this.$http.delete('products/' + product.id).then(function (response) {
					this.products.$remove(product);
				}, function (response) {
					// 
				})
			},

			newUploadImage(product){
				this.product 	= product
			},

			uploadImages(product) {
				var that 	= this;
				var imgFile = document.getElementById('images');
				for (var i = 0; i<imgFile.files.length; i++){
					if (imgFile.files[i]) {
					    var reader 		= new FileReader();
					    reader.onload = function(event) {
					        var dataUri 		= event.target.result;
					        var productImage 	= {
					        	product_id: product.id,
					        	base64_image: dataUri
					        }
					        that.$http.post('products/image', productImage).then(function (response) {
								console.log(response.data)
								console.log('======================================================')
								productImage 	= null
								dataUri 		= null
								if (response.data.status == 'success') {
									alert("Upload gambar berhasil")
								} else {
									alert ("Upload gambar gagal")
								}
							}, function (response) {
								console.log('error')
								console.log(response)
							})
					   };
					   reader.onerror = function(event) {
					       console.error("File could not be read! Code " + event.target.error.code);
					   };
					   reader.readAsDataURL(imgFile.files[i]);
					}
				}
				$('#modal_newImages').modal('hide');
				document.getElementById('images').value = "";
			},

			viewImages(product) {
				this.$http.get('products/images/' + product.id).then(function (response) {
					this.images_product = response.data.product_images;
					this.product = product
				}, function (response) {
					console.log('error')
					console.log(response)
				})
			},

			closeViewImages() {
				this.product 		= null;
				this.images_product = null;
			},

			deleteImage(image) {
				this.$http.delete('products/image/' + image.id).then(function (response) {
					this.images_product.$remove(image);
				}, function (response) {
					console.log('error')
					console.log(response)
				})
			},

			deleteAllImages(product) {
				this.$http.delete('products/images/' + product.id).then(function (response) {
					this.images_product = null;
				}, function (response) {
					console.log('error')
					console.log(response)
				})
			}
		},

		ready() {
			this.fetch();
		}
	}
</script>