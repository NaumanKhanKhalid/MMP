<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PARENT CATEGORIES
    |--------------------------------------------------------------------------
    */

    public function parentCategories()
    {
        $categories = Category::whereNull('parent_id')->orderBy('name')->paginate(15);
        return view('categories.parents', compact('categories'));
    }


    public function storeParent(CategoryRequest $request)
    {
        $data = $this->prepareData($request);
        Category::create($data);

        return redirect()->route('categories.parents')->with('success', 'Category created successfully.');
    }

    public function editParent(Category $category)
    {
        return view('categories.index', compact('category'));
    }


    public function updateParent(CategoryRequest $request, Category $category)
    {
        $data = $this->prepareData($request, $category->id, $category);
        $category->update($data);

        return redirect()->route('categories.parents')->with('success', 'Category updated successfully.');
    }

    public function destroyParent(Category $category)
    {
        if ($category->logo) {
            Storage::disk('public')->delete($category->logo);
        }
        $category->delete();

        return redirect()->route('categories.parents')->with('success', 'Category deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SUBCATEGORIES
    |--------------------------------------------------------------------------
    */

    public function subCategories()
    {
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();
        $categories = Category::whereNotNull('parent_id')->with('parent')->orderBy('name')->paginate(15);
        return view('categories.subcategories', compact('categories', 'parents'));
    }


    public function storeSub(CategoryRequest $request)
    {
        $data = $this->prepareData($request);
        Category::create($data);

        return redirect()->route('categories.subcategories')->with('success', 'Subcategory created successfully.');
    }

    public function editSub(Category $category)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get();
        return view('categories.subcategories', compact('category', 'parents'));
    }

    public function updateSub(CategoryRequest $request, Category $category)
    {
        $data = $this->prepareData($request, $category->id, $category);
        $category->update($data);

        return redirect()->route('categories.subcategories')->with('success', 'Subcategory updated successfully.');
    }

    public function destroySub(Category $category)
    {
        if ($category->logo) {
            Storage::disk('public')->delete($category->logo);
        }
        $category->delete();

        return redirect()->route('categories.subcategories')->with('success', 'Subcategory deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS TOGGLE (Shared)
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Category $category)
    {
        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->save();

        return redirect()->back()->with('success', 'Category status updated.');
    }
    public function toggleStatusSub(Category $category)
    {
        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->save();

        return redirect()->back()->with('success', 'Subcategory status updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    protected function prepareData($request, $ignoreId = null, $existingCategory = null)
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name'], $ignoreId);
        $data['status'] = $data['status'] ?? 'active';

        if ($request->hasFile('logo')) {
            if ($existingCategory && $existingCategory->logo) {
                Storage::disk('public')->delete($existingCategory->logo);
            }
            $data['logo'] = $request->file('logo')->store('category_logos', 'public');
        }

        return $data;
    }

    protected function uniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $base = $slug;
        $i = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
