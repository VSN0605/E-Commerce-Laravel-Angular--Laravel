<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // function to add category
    public function storeCategory(Request $request) {
    
        $request->validate([
            'category_name' => 'required|string',
            'category_details' => 'required|string',
        ]);

        $role = Auth::user()->user_role;

        Category::create([
            'category_name' => $request->category_name,
            'category_details' => $request->category_details,
            'created_by' => $role,
        ]);

        Log::create([
            'model' => 'Category',
            'name' => $request->category_name,
            'actions' => 'Create',
            'performed_by' => $role,
        ]);

        return response()->json([
            'message' => 'Your category created successfully'
        ], 201);
    }

    // function to get all catgories
    public function getCategories(Request $request) {
        
        $role = Auth::user()->user_role;

        $category_query = Category::select();

        if($role != 'admin') {
            $category_query->where('created_by', 'user');
        }

        $categories = $category_query->get();

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
        $role = Auth::user()->user_role;

        $request->validate([
            'category_name' => 'required|string',
            'category_details' => 'required|string',
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'category_details' => $request->category_details,
        ]);

        Log::create([
            'model' => 'Category',
            'name' => $request->category_name,
            'actions' => 'Update',
            'performed_by' => $role,
        ]);

        return response()->json(['message' => 'Category updated successfully']);
    }

    // to delete category
    public function deleteCategory(Request $request, $id) {
        $category = Category::findOrFail($id);
       
        $role = Auth::user()->user_role;
        $categoryName = $category->category_name;

        $category->delete();

        Log::create([
            'model' => 'Category',
            'name' => $categoryName,
            'actions' => 'Delete',
            'performed_by' => $role,
        ]);

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
