<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    // Get all recipes and filter by category
    // GET - api/recipes
    // GET - api/recipes?category= name
    public function index()
    {
        try {
            return Recipe::filter(request(['category']))->paginate(5);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    // GET recipe
    // GET - /api/recipes/id
    public function show($id)
    {
        try {
            $recipe = Recipe::find($id);
            if (!$recipe) {
                return response()->json([
                    'msg' => 'recipe not found',
                    'status' => '404'
                ], 404);
            }
            return $recipe;
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'status' => '500'
            ], 500);
        }
    }

    //delete recipe
    //DELETE api/recipes/id
    public function destory($id)
    {
        try {
            $recipe = Recipe::find($id);
            if (!$recipe) {
                return response()->json([
                    'msg' => 'recipe not found',
                    'status' => '404'
                ], 404);
            }

            $recipe->delete();
            return response()->json([
                'delete recipe' => $recipe,
                'status' => 200
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'status' => '500'
            ], 500);
        }
    }

    //create recipes
    //POST api/recipes
    public function store()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'description' => 'required',
                'photo' => 'required',
                'category_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors(),
                    'status' => 400
                ], 400);
            }

            $recipe = new Recipe();
            $recipe->title = request('title');
            $recipe->description = request('description');
            $recipe->photo = request('photo');
            $recipe->category_id = request('category_id');
            $recipe->save();

            return response()->json($recipe, 201);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'status' => '500'
            ], 500);
        }
    }

    //update recipe
    //PATCH api/recipes/id
    public function update($id)
    {
        try {
            $recipe = Recipe::find($id);
            if (!$recipe) {
                return response()->json([
                    'msg' => 'recipe not found',
                    'status' => '404'
                ], 404);
            }

            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'description' => 'required',
                'photo' => 'required',
                'category_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors(),
                    'status' => 400
                ], 400);
            }

            $recipe->title = request('title');
            $recipe->description = request('description');
            $recipe->photo = request('photo');
            $recipe->category_id = request('category_id');
            $recipe->save();

            return response()->json($recipe, 200);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'status' => '500'
            ], 500);
        }
    }


    //upload photo
    //POST api/recipes/upload
    public function upload()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'photo' => ['required', 'image'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors(),
                    'status' => 400
                ], 400);
            }

            $path = '/storage/' . request('photo')->store('/recipes');
            return response()->json([
                'path' => $path,
                'status' => 200
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'status' => '500'
            ], 500);
        }
    }
}
