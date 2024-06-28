<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStore;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryWidthBrandResource;
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

    public function index(Request $request, Product $product)
    {
        $params = $this->validate($request, [
            'category_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',
            'name' => 'nullable|string'
        ]);

        $query = $product->newQuery();

        if (!empty($params['category_id'])) {
            $query->where('category_id', $params['category_id']);
        }

        if (!empty($params['brand_id'])) {
            $query->where('brand_id', $params['brand_id']);
        }

        if (!empty($params['name'])) {
            // Mahsulot nomi, kategoriya va brendlarni qidirish uchun yagona where bo'lishi uchun function ichida query yaratish
            $query = $query->where(function ($q) use ($params) {
                $categoryIds = Category::where('name', 'like', '%' . $params['name'] . '%')->pluck('id')->toArray();
                $brandIds = Brand::where('name', 'like', '%' . $params['name'] . '%')->pluck('id')->toArray();

                $q->where('title', 'like', '%' . $params['name'] . '%')
                    ->orWhereIn('category_id', $categoryIds)
                    ->orWhereIn('brand_id', $brandIds);
            });
        }

        return new ProductCollection($query->latest()->paginate(12));
    }


    public function store(ProductStore $request)
    {
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('product-photos', 'public');
        }

        $brand = Brand::find($request->brand_id);

        if ($request->category_id !== $brand->category_id) {
            return response()->errorJson('Tanlangan brand bu kategoriyaga tegishli emas!', 404);
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


    public function show($id){
        $product = Product::find($id);

        if ($product) {
            return response()->successJson(new ProductResource($product));
        } else {
            return response()->errorJson('Not Found', 404);
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

        return response()->json("Muvaffaqqiyatli o'chirildi");
    }

    public function getCategoriesWidthBrands()
    {
        $categories = Category::with('brands')->get();
        $categoriesWidthBrands = CategoryWidthBrandResource::collection($categories);
        return response()->successJson($categoriesWidthBrands);
    }

    public function getCategories(){
        $categories = CategoryResource::collection(Category::all());

        return response()->successJson($categories);
    }

    public function getBrands($id){

        $category = Category::find($id);

        if ($category){
            return response()->successJson(new BrandResource($category->brands));
        } else {
            return response()->errorJson('Not Found', 404);
        }
    }
}
