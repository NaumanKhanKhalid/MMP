<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PARENT CATEGORIES
    |--------------------------------------------------------------------------
    */

    public function parentCategories()
    {
        // Show only MAIN categories (no parent)
        $categories = Category::with('children', 'products')
            ->whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('categories.main_categories', compact('categories'));
    }

    public function subCategories()
    {
        // Show only SUB-categories (has parent)
        $categories = Category::with('parent', 'products')
            ->whereNotNull('parent_id')
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('categories.sub_categories', compact('categories'));
    }

    public function storeParent(CategoryRequest $request)
    {
        $data = $this->prepareData($request);
        Category::create($data);

        // Check if it's a main category or sub-category
        if ($request->parent_id) {
            return redirect()->route('categories.subcategories')->with('success', 'Sub-category created successfully.');
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function editParent(Category $category)
    {
        return view('categories.index', compact('category'));
    }

    public function updateParent(CategoryRequest $request, Category $category)
    {
        $data = $this->prepareData($request, $category->id, $category);
        $category->update($data);

        // Check if it's a main category or sub-category
        if ($category->parent_id) {
            return redirect()->route('categories.subcategories')->with('success', 'Sub-category updated successfully.');
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroyParent(Category $category)
    {
        if ($category->logo) {
            // Remove 'public/' prefix if exists before deleting
            $logoPath = str_replace('public/storage/', '', $category->logo);
            Storage::disk('public')->delete($logoPath);
        }
        $category->delete();

        // Check if it was a main category or sub-category
        $wasSubCategory = $category->parent_id !== null;

        if ($wasSubCategory) {
            return redirect()->route('categories.subcategories')->with('success', 'Sub-category deleted successfully.');
        }

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
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

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($existingCategory && $existingCategory->logo) {
                // Remove 'public/storage/' prefix if exists before deleting
                $oldPath = str_replace('public/storage/', '', $existingCategory->logo);
                Storage::disk('public')->delete($oldPath);
            }
            // Store file and add 'public/storage/' prefix
            $path = $request->file('logo')->store('category_logos', 'public');
            $data['logo'] = 'public/storage/' . $path;
        }

        // Handle logo removal
        if ($request->has('remove_logo') && $request->remove_logo == '1') {
            if ($existingCategory && $existingCategory->logo) {
                // Remove 'public/' prefix if exists before deleting
                $oldPath = str_replace('public/storage/', '', $existingCategory->logo);
                Storage::disk('public')->delete($oldPath);
            }
            $data['logo'] = null;
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
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function createCategoryModal()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('categories.partials.create_category_modal', compact('parentCategories'));
    }

    public function createSubCategoryModal()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('categories.partials.create_subcategory_modal', compact('parentCategories'));
    }

    public function viewModal(Category $category)
    {
        return view('categories.partials.view_modal', compact('category'));
    }

    public function editModal(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get();

        return view('categories.partials.edit_modal', compact('category', 'parentCategories'));
    }
}
