<?php
namespace App\Http\Controllers\Api\Settings;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Category as Request;
use App\Http\Resources\Setting\Category as Resource;
use App\Jobs\Setting\CreateCategory;
use App\Jobs\Setting\DeleteCategory;
use App\Jobs\Setting\UpdateCategory;
use App\Models\Setting\Category;
class Categories extends ApiController
{
    public function index()
    {
        $categories = Category::withSubCategory()->collect();
        return Resource::collection($categories);
    }
    public function show(Category $category)
    {
        return new Resource($category);
    }
    public function store(Request $request)
    {
        $category = $this->dispatch(new CreateCategory($request));
        return $this->created(route('api.categories.show', $category->id), new Resource($category));
    }
    public function update(Category $category, Request $request)
    {
        try {
            $category = $this->dispatch(new UpdateCategory($category, $request));
            return new Resource($category->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function enable(Category $category)
    {
        try {
            $category = $this->dispatch(new UpdateCategory($category, request()->merge(['enabled' => 1])));
            return new Resource($category->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function disable(Category $category)
    {
        try {
            $category = $this->dispatch(new UpdateCategory($category, request()->merge(['enabled' => 0])));
            return new Resource($category->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function destroy(Category $category)
    {
        try {
            $this->dispatch(new DeleteCategory($category));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
