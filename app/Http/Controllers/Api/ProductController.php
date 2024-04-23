<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStore;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        return new ProductCollection(Product::latest()->paginate(9));
    }


    public function store(ProductStore $request)
    {
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('product-photos', 'public');
        }

        $brand = Brand::find($request->brand_id);

        if ($request->category_id != $brand->category_id) {
            return response()->json("Tanlangan brand bu kategoriyaga tegishli emas");
        }

        return $request;

        $product = Product::create([
            'user_id' => auth()->user()->id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'title' => $request->title,
            'price' => $request->price,
            'content' => $request->content,
            'photo' => $path ?? "",
        ]);


        return response()->json("Created Successfully");
    }


    public function show(Product $product)
    {
        if ($product) {
            return new ProductResource($product);
        } else {
            return response()->json("Product yo'q");
        }
    }


    public function update(ProductStore $request, Product $product)
    {
        $this->authorize('update', $product);

        if ($request->hasFile('photo')) {
            if (isset($product->photo)) {
                Storage::delete($product->photo);
            }

            $path = $request->file('photo')->store('product-photos');
        }

        $brand = Brand::find($request->brand_id);
        if ($request->category_id != $brand->category_id) {
            return response()->json("Tanlangan brand bu kategoriyaga tegishli emas");
        }

        $product->update([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'title' => $request->title,
            'price' => $request->price,
            'content' => $request->content,
            'photo' => $path ?? $product->photo,
        ]);

        return response()->json("Updated successfully");
    }


    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if (isset($product->photo)) {
            Storage::delete($product->photo);
        }

        $product->delete();

        return response()->json("Deleted Successfully");
    }

    public function getProductByCategory(Category $category)
    {

        $products = $category->products()->latest()->paginate(9);
        return new ProductCollection($products);
    }

    public function getProductByBrand(Brand $brand)
    {
        $products = $brand->products()->latest()->paginate(9);

        return new ProductCollection($products);

    }
}
