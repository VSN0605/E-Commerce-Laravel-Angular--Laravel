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
            'created_by' => 'required|string',
        ]);

        Category::create([
            'category_name' => $request->category_name,
            'category_details' => $request->category_details,
            'created_by' => $request->created_by,
        ]);

        Log::create([
            'model' => 'Category',
            'name' => $request->category_name,
            'actions' => 'Create',
            'performed_by' => $request->created_by,
        ]);

        return response()->json([
            'message' => 'Your category created successfully'
        ], 201);
    }

    // function to get all catgories
    public function getCategories(Request $request) {
        
        $role = $request->query('role');

        $category_query = Category::select();

        if($role != 'admin') {
            $category_query->where('created_by', 'user');
        }

        $categories = $category_query->get();

        // if($role === 'admin') {
        //     $categories = Category::select(
        //         'id',
        //         'category_name',
        //         'category_details',
        //         'created_by',
        //         'created_at',
        //     )->get();
        // }else {
        //     $categories = Category::select(
        //         'id',
        //         'category_name',
        //         'category_details',
        //         'created_by',
        //         'created_at',
        //     )->where('created_by', 'user')->get();
        // }

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

        Log::create([
            'model' => 'Category',
            'name' => $request->category_name,
            'actions' => 'Update',
            'performed_by' => $request->created_by,
        ]);

        return response()->json(['message' => 'Category updated successfully']);
    }

    // to delete category
    public function deleteCategory(Request $request, $id) {
        $category = Category::findOrFail($id);
        $role = $request->query('role');
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
