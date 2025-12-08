<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller implements HasMiddleware
{


    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum',  except: ['index' , 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $fields = $request->validated();

        $category = $request->admin()->category()->create($fields);

        return ["category" => $category];
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return ["category" => $category];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        Gate::authorize("modify" , $category);
        $fields = $request->validated();
        $category -> update($fields);
        return ["category" => $category];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize("modify" , $category);
        $category -> delete();
        return [
            "message" => "The Category Was Deleted"
        ];
    }

    public function deleteAllCategories(){

        $categories = Category::all();

        $categories->each->delete();

        return [
            "message" => "All Categories have been Deleted"
        ];
    }
}
