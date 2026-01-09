<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // function to add category
    public function storeCategory(Request $request) {
        $request->validate([
            'category_name' => 'required|string',
            'category_details' => 'required|string',
            // 'created_by' => 'required|string',
        ]);

        Category::create([
            'category_name' => $request->category_name,
            'category_details' => $request->category_details,
            // 'created_by' => $request->user()->user_role,
        ]);

        return response()->json([
            'message' => 'Your category created successfully'
        ], 201);
    }

    // function to get all catgories
    public function getCategories(Request $request) {
        $categories = Category::select(
            'id',
            'category_name',
            'category_details',
            'created_by',
            'created_at',
        )->get();

        $role = $request->query('role');

        if($role === 'admin') {
            $categories = Category::select(
                'id',
                'category_name',
                'category_details',
                'created_by',
                'created_at',
            )->get();
        }else {
            $categories = Category::select(
                'id',
                'category_name',
                'category_details',
                'created_by',
                'created_at',
            )->where('created_by', 'user')->get();
        }

        return response()->json($categories, 200);
    }

    // to get count of categories
    public function categoryCount()
    {
        $categoryCount = Category::count();

        return response()->json([
            'categoryCount' => $categoryCount
        ], 200);
    }

    // to get data in form
    public function editCategory($id) {
        return Category::findOrFail($id);
    }

    // function to update category
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string',
            'category_details' => 'required|string',
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'category_details' => $request->category_details,
        ]);

        return response()->json(['message' => 'Category updated successfully']);
    }

    // to delete category
    public function deleteCategory($id) {
        Category::findOrFail($id)->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
