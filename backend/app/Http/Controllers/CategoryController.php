<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

  //GET categories
  //GET /api/categories
  public function index()
  {
    try {
      return Category::all();
    } catch (Exception $e) {
      return response()->json([
        'msg' => $e->getMessage(),
        'status' => '500'
      ], 500);
    }
  }

  //filter categories
  //GET /api/categories/id
  public function show($id)
  {
    try {
      return Category::find($id);
    } catch (Exception $e) {
      return response()->json([
        'msg' => $e->getMessage(),
        'status' => '500'
      ], 500);
    }
  }

  //add category
  //POST api/categories
  public function store()
  {
    try {
      $validator = Validator::make(request()->all(), [
        'name' => 'required',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'msg' => $validator->errors(),
          'status' => 400
        ], 400);
      }

      $category = new Category();
      $category->name = request('name');
      $category->save();

      return response()->json($category, 201);
    } catch (Exception $e) {
      return response()->json([
        'msg' => $e->getMessage(),
        'status' => '500'
      ], 500);
    }
  }

  //update category
  //PATCH /api/categories/id
  public function update($id)
  {
    try {
      $category = Category::find($id);
      if (!$category) {
        return response()->json([
          'msg' => 'categpry not found',
          'status' => '404'
        ], 404);
      }

      $validator = Validator::make(request()->all(), [
        'name' => 'required'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'msg' => $validator->errors(),
          'status' => 400
        ], 400);
      }

      $category->name = request('name');
      $category->save();

      return response()->json($category, 201);
    } catch (Exception $e) {
      return response()->json([
        'msg' => $e->getMessage(),
        'status' => '500'
      ], 500);
    }
  }

  // delete category
  //DELETE /api/categories/id
  public function destory($id)
  {
    try {
      $category = Category::find($id);
      if (!$category) {
        return response()->json([
          'msg' => 'category not found',
          'status' => '404'
        ], 404);
      }

      $category->delete();
      return $category;
    } catch (Exception $e) {
      return response()->json([
        'msg' => $e->getMessage(),
        'status' => '500'
      ], 500);
    }
  }
}
