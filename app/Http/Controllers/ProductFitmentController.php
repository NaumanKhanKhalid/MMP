<?php

namespace App\Http\Controllers;

use App\Models\ProductFitment;
use App\Models\Product;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Engine;
use Illuminate\Http\Request;

class ProductFitmentController extends Controller
{
    public function index()
    {
        $fitments = ProductFitment::with(['product', 'make', 'model', 'engine'])->paginate(15);
        $products = Product::orderBy('name')->get();
        $makes = CarMake::where('status', 'active')->orderBy('name')->get();
        $models = CarModel::where('status', 'active')->orderBy('name')->get();
        $engines = Engine::where('status', 'active')->orderBy('code')->get();
        return view('fitments.index', compact('fitments', 'products', 'makes', 'models', 'engines'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'make_id' => 'required|exists:car_makes,id',
            'model_id' => 'required|exists:car_models,id',
            'engine_id' => 'required|exists:engines,id',
            'year_start' => 'required|integer|min:1900|max:' . date('Y'),
            'year_end' => 'required|integer|min:1900|max:' . date('Y'),
            'market' => 'nullable|string|max:255',
        ]);

        ProductFitment::create($data);
        return redirect()->route('fitments.index')->with('success', 'Product fitment added successfully.');
    }

    public function update(Request $request, ProductFitment $fitment)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'make_id' => 'required|exists:car_makes,id',
            'model_id' => 'required|exists:car_models,id',
            'engine_id' => 'required|exists:engines,id',
            'year_start' => 'required|integer|min:1900|max:' . date('Y'),
            'year_end' => 'required|integer|min:1900|max:' . date('Y'),
            'market' => 'nullable|string|max:255',
        ]);

        $fitment->update($data);
        return redirect()->route('fitments.index')->with('success', 'Product fitment updated successfully.');
    }

    public function destroy(ProductFitment $fitment)
    {
        $fitment->delete();
        return redirect()->route('fitments.index')->with('success', 'Product fitment deleted successfully.');
    }
}
