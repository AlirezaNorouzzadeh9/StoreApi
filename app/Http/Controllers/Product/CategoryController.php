<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Category\UpdateRequest;
use App\Http\Requests\Product\Category\StoreRequest;
use App\Http\Resources\Product\CategoryResource;
use App\Models\Product\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;
    public function index(Request $request){
        $categories = Category::query()->with('parent')->get();
        if($categories->isEmpty()){
            return $this->error('No categories found', 404); 
    }
        return $this->success(CategoryResource::collection($categories), 'Categories retrieved successfully');
    }

    public function show(Request $request, $id){
        $category = Category::query()->with('parent')->find($id);
        if(!$category){
            return $this->error('Category not found', 404); 
        }
        return $this->success(new CategoryResource($category), 'Category retrieved successfully');
    }

    public function update(UpdateRequest $request, $id){
        $category = Category::query()->find($id);
        if(!$category){
            return $this->error('Category not found', 404); 
        }
        $category->update($request->validated());
        return $this->success(new CategoryResource($category), 'Category updated successfully');
    }

    public function store(StoreRequest $request){
        $data = $request->validated();
        if(Category::query()->where('slug', $data['slug'])->exists()){
            return $this->error('Category with this slug already exists', 422); 
        }
        $category = Category::query()->create($data);
        return $this->success(new CategoryResource($category), 'Category created successfully', 201);
    }

    public function destroy(Request $request, $id){
        $category = Category::query()->find($id);
        if(!$category){
            return $this->error('Category not found', 404); 
        }
        $category->delete();
        return $this->success(null, 'Category deleted successfully');
    }
}
