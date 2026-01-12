<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Log;

class ProductController extends Controller
{
    public function storeProduct(Request $request){
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'product_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'product_quantity' => 'required|integer|min:1',
            'category_id' => 'required|integer',
            'product_company' => 'required|string',
            'created_by' => 'required|string',
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
            'product_quantity' => $request->product_quantity,
            'category_id' => $request->category_id,
            'product_company' => $request->product_company,
            'created_by' => $request->created_by,
        ]);

        Log::create([
            'model' => 'Product',
            'name' => $request->product_name,
            'actions' => 'Create',
            'performed_by' => $request->created_by,
        ]);

        return response()->json([
            'message' => 'Your product created successfully'
        ], 201);
    }

    // function to get all products
    public function getProducts(Request $request) {
        $role = $request->query('role');

        if($role === 'admin') {
            $products = Product::with('category')->select(
                'id',
                'product_name',
                'product_description',
                'product_price',
                'product_image',
                'product_quantity',
                'category_id',
                'product_company',
                'created_by',
            )->get();
        } else {
            $products = Product::with('category')->select(
                'id',
                'product_name',
                'product_description',
                'product_price',
                'product_image',
                'product_quantity',
                'category_id',
                'product_company',
                'created_by',
            )->where('created_by', 'user')->get();
        }

        return response()->json($products, 200);
    }

    public function getProductDetail($id) {
        $product = Product::with('category') // optional
        ->where('id', $id)
        ->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json($product);
    }

    // function to get category in dropdown
    public function getDrodownCat() {
        $categories = Category::select(
            'id',
            'category_name',
            
        )->get();

        return response()->json($categories, 200);
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
    public function deleteProduct(Request $request, $id) {
        $product = Product::findOrFail($id);
        $role = $request->query('role');
        $productName = $product->product_name;
        // $performedBy = $product->created_by;
        
        $product->delete();

        Log::create([
            'model' => 'Product',
            'name' => $productName,
            'actions' => 'Delete',
            'performed_by' => $role,
        ]);

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
            'category_id' => 'required|string',
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
            'category_id' => $request->category_id,
            'product_company' => $request->product_company,
        ]);

        Log::create([
            'model' => 'Product',
            'name' => $request->product_name,
            'actions' => 'Update',
            'performed_by' => $request->created_by,
        ]);

        return response()->json(['message' => 'Product updated successfully']);
    }
}
