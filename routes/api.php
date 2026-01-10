<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// to submit the registration form
Route::post('/users', [UserController::class, 'store']);

// for login
Route::post('/login', [AuthController::class, 'login']);

// to submit category form
Route::post('/category', [CategoryController::class, 'storeCategory']);

// to submit product form
Route::post('/product', [ProductController::class, 'storeProduct']);

// to get all users
Route::get('/users', [UserController::class, 'index']);

// to get count of users
Route::get('/users/count', [UserController::class, 'count']);

// to get all categories
Route::get('/category', [CategoryController::class, 'getCategories']);

// to get count of categories
Route::get('/category/count', [CategoryController::class, 'categoryCount']);

// to get count of categories
Route::get('/product', [ProductController::class, 'getProducts']);

// to get count of products
Route::get('/product/count', [ProductController::class, 'categoryCount']);

// to delete product
Route::delete('/product/{id}', [ProductController::class, 'deleteProduct']);

// to get product data in form to edit
Route::get('/product/{id}', [ProductController::class, 'showProduct']);

// to update product
Route::put('/product/{id}', [ProductController::class, 'updateProduct']);

// to get category in dropdown
Route::get('/categories/dropdown', [ProductController::class, 'getDrodownCat']);

// to get category data in form to edit
Route::get('/category/{id}', [CategoryController::class, 'editCategory']);

// to update product
Route::put('/category/{id}', [CategoryController::class, 'updateCategory']);

// to delete category
Route::delete('/category/{id}', [CategoryController::class ,'deleteCategory']);

