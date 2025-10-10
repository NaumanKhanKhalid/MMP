<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->latest()->paginate(15);
        return view('brands.index', compact('brands'));
    }

    public function restore($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->restore();
        
        return redirect()->back()->with('success', 'Brand restored successfully.');
    }

    public function createModal()
    {
        return view('brands.partials.create_modal');
    }

    public function viewModal(Brand $brand)
    {
        $brand->load('products');
        return view('brands.partials.view_modal', compact('brand'));
    }

    public function editModal(Brand $brand)
    {
        return view('brands.partials.edit_modal', compact('brand'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => $this->uniqueSlug($request->name),
            'description' => $request->description,
            'status' => $request->status,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brand_logos', 'public');
            $data['logo'] = 'public/storage/' . $path;
        }

        Brand::create($data);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => $this->uniqueSlug($request->name, $brand->id),
            'description' => $request->description,
            'status' => $request->status,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                $oldPath = str_replace('public/storage/', '', $brand->logo);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('logo')->store('brand_logos', 'public');
            $data['logo'] = 'public/storage/' . $path;
        }

        // Handle logo removal
        if ($request->has('remove_logo') && $request->remove_logo == '1') {
            if ($brand->logo) {
                $oldPath = str_replace('public/storage/', '', $brand->logo);
                Storage::disk('public')->delete($oldPath);
            }
            $data['logo'] = null;
        }

        $brand->update($data);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->status = $brand->status === 'active' ? 'inactive' : 'active';
        $brand->save();
        return redirect()->back()->with('success', 'Brand status updated.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }

    protected function uniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $base = $slug;
        $i = 1;

        while (
            Brand::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
