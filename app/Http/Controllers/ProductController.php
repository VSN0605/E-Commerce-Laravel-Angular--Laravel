<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function storeProduct(Request $request){
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'product_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'product_category' => 'required|string',
            'product_company' => 'required|string',
            // 'created_by' => 'required|string',
        ]);

        $imageName = null;

        if($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        }

        Product::create([
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'product_price' => $request->product_price,
            'product_image' => $imageName,
            'product_category' => $request->product_category,
            'product_company' => $request->product_company,
            'created_by' => 'admin',
        ]);

        return response()->json([
            'message' => 'Your product created successfully'
        ], 201);
    }

    // function to get all products
    public function getProducts() {
        $products = Product::select(
            'id',
            'product_name',
            'product_description',
            'product_price',
            'product_image',
            'product_category',
            'product_company',
            'created_by',
        )->get();

        return response()->json($products, 200);
    }

    // to get count of products
    public function categoryCount()
    {
        $productCount = Product::count();

        return response()->json([
            'productCount' => $productCount
        ], 200);
    }

    // to delete product
    public function deleteProduct($id) {
        Product::findOrFail($id)->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    // to get data in form while editing
    public function showProduct($id) {
        return Product::findOrFail($id);
    }

    // function to udpate product
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string',
            'product_description' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'product_category' => 'required|string',
            'product_company' => 'required|string',
        ]);

        $imageName = $product->product_image;

        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $imageName = time() . '.' . $file->extension();
            $file->move(public_path('images'), $imageName);
        }

        $product->update([
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'product_price' => $request->product_price,
            'product_image' => $imageName,
            'product_category' => $request->product_category,
            'product_company' => $request->product_company,
        ]);

        return response()->json(['message' => 'Product updated successfully']);
    }
}
