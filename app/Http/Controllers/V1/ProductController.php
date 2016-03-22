<?php 

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use App\Models\UserSupplier;
use App\Repositories\ProductRepository;
use App\Models\Product;
use App\Supports\Contracts\Supplierable;
use Illuminate\Http\Request;
use Auth;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Untuk menampilkan semua data product
     * 
     * @return [type] [description]
     */
    public function index()
    {
        return [
            'status'    => 'success',
            'products'  => Product::orderBy('id', 'desc')->get(),
        ];
    }

    /**
     * Untuk menyimpan data product
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request, Supplierable $supplier)
    {
        $this->validate($request, [
            'code'          => 'required',  
            'name'          => 'required',
            'description'   => 'required',
            'price'         => 'required',
            'stock'         => 'required',
            'category_id'   => 'required'
        ]);

        $data = $request->only(
            'code',
            'name',
            'description',
            'price',
            'stock',
            'category_id'
        );

        if (is_string($request->get('tags'))) {
            $tags = explode(',', $request->get('tags'));
        } else {
            $tags = $request->get('tags', []);
        }

        $product = new Product;
        $product->fill($data);
        $product->supplier()->associate($supplier);
        $product->save();

        $this->productRepository->saveTags($product, $tags);

        if ($product) {
            return [
                'status'    => 'success',
                'message'   => 'Product has successfully added.',
                'product'   => $product->load('tags')
            ];
        } else {
            return [
                'status'    => 'failed',
                'message'   => 'Product has failed to be added.'
            ];
        }
    }

    /**
     * Untuk menampilkan product berdasarkan id
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function show($id)
    {
        $product    = Product::find($id);

        if($product){
            return [
                'status'    => 'success',
                'product'   => $product
            ];
        }else{
            return [
                'status'    => 'failed',
                'message'   => 'Product not found.'
            ];
        }
    }

    public function showBySupplier()
    {    
        $config         = config('amtekcommerce.product_image');
        $user           = Auth::user();
        $supplier_id    = $user->supplier->first()->id;
        $products       = Product::where('supplier_id', $supplier_id)->get();
        $image_url      = $config['url'] . '/sup_' . $supplier_id;

        if ($products) {
            return [
                'status'    => 'success',
                'message'   => 'Produk tersedia.',
                'products'  => $products,
                'image_url' => $image_url
            ];
        } else {
            return [
                'status'    => 'failed',
                'message'   => 'Produk tidak tersedia.',
                'products'  => null,
                'image_url' => null
            ];
        }
    }

    /**
     * Untuk update data product
     * 
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code'          => 'required',  
            'name'          => 'required',
            'description'   => 'required',
            'price'         => 'required',
            'category_id'   => 'required'
        ]);

        $product    = Product::find($id);

        if($product){
            $product->code          = $request->input('code');
            $product->name          = $request->input('name');
            $product->description   = $request->input('description');
            $product->price         = $request->input('price');
            $product->stock         = $request->input('stock');
            $product->category_id   = $request->input('category_id');

            if($product->save()){

                if (is_string($request->get('tags'))) {
                    $tags = explode(',', $request->get('tags'));
                } else {
                    $tags = $request->get('tags', []);
                }

                $this->productRepository->saveTags($product, $tags);
                return [
                    'status'    => 'success',
                    'message'   => 'Product has been updated.',
                    'product'   => $product
                ];
            }else{
                return [
                    'status'    => 'success',
                    'message'   => 'Product has failed to be update.'
                ];
            }
        }else{
            return [
                'status'    => 'failed',
                'message'   => 'Product not found.'
            ];
        }
    }

    /**
     * Untuk menghapus data product
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $product    = Product::find($id);

        $clearTags = $this->productRepository->clearTags($product);

        if($product){
            if($product->delete()){
                return [
                    'status'    => 'success',
                    'message'   => 'Product has successfully deleted.'
                ];
            }else{
                return [
                    'status'    => 'failed',
                    'message'   => 'Product has failed to be delete.'
                ];
            }
        }else{
            return [
                'status'    => 'failed',
                'message'   => 'Product not found.'
            ];
        }
    }

    public function incrementStock(Request $request, $id)
    {
        $this->validate($request, [
            'count'          => 'required', 
        ]);

        $product    = Product::find($id);

        if ($product) {
            
                $product->stock     = $product->stock + $request->input('count');

                if ($product->save()) {
                    return [
                        'status'    => 'success',
                        'message'   => 'Stock barang berhasil ditambah.',
                        'product'   => $product
                    ];
                } else {
                    return [
                        'status'    => 'failed',
                        'message'   => 'Proses penambahan stock barang tidak berhasil.',
                        'product'   => $product
                    ];
                }

        } else {
            return [
                'status'    => 'failed',
                'message'   => 'Produk tidak tersedia.',
                'product'   => null
            ];
        }
    }

    public function decrementStock(Request $request, $id)
    {
        $this->validate($request, [
            'count'          => 'required', 
        ]);

        $product    = Product::find($id);

        if ($product) {
            if ($product->stock > 0) {
                $product->stock     = $product->stock - $request->input('count');

                if ($product->save()) {
                    return [
                        'status'    => 'success',
                        'message'   => 'Stock barang berhasil dikurangi.',
                        'product'   => $product
                    ];
                } else {
                    return [
                        'status'    => 'failed',
                        'message'   => 'Proses pengurangan stock barang tidak berhasil.',
                        'product'   => $product
                    ];
                }

            } else {
                return [
                    'status'    => 'failed',
                    'message'   => 'Maaf, stock untuk barang ini sudah habis.',
                    'product'   => $product
                ];
            }

        } else {
            return [
                'status'    => 'failed',
                'message'   => 'Produk tidak tersedia.',
                'product'   => null
            ];
        }
    }
}