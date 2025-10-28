<?php
namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Models\CarMake;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarModelController extends Controller
{
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $query = CarModel::where('status', 'active')->orderBy('name');
            
            if ($request->has('make_id')) {
                $query->where('make_id', $request->make_id);
            }
            
            $models = $query->get(['id', 'name']);
            return response()->json($models);
        }
        
        $models = CarModel::with('make')->orderBy('name')->paginate(10);
        $makes = CarMake::where('status', 'active')->orderBy('name')->get();
        return view('car_models.index', compact('models', 'makes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'make_id' => 'required|exists:car_makes,id',
            'name' => 'required|string|max:255',
            'generation' => 'nullable|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
        ]);
        $data['status'] = $data['status'] ?? 'active';

        CarModel::create($data);
        return redirect()->route('car-models.index')->with('success', 'Car Model created successfully.');
    }

    public function update(Request $request, CarModel $model)
    {
        $data = $request->validate([
            'make_id' => 'required|exists:car_makes,id',
            'name' => 'required|string|max:255',
            'generation' => 'nullable|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
        ]);
        $model->update($data);

        return redirect()->route('car-models.index')->with('success', 'Car Model updated successfully.');
    }

    public function destroy(CarModel $model)
    {
        $model->delete();
        return redirect()->route('car-models.index')->with('success', 'Car Model deleted successfully.');
    }

    public function toggleStatus(CarModel $model)
    {
        $model->status = $model->status === 'active' ? 'inactive' : 'active';
        $model->save();

        return redirect()->back()->with('success', 'Car Model status updated.');
    }

    /**
     * Quick add for Select2 tagging
     */
    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'make_id' => 'nullable|exists:car_makes,id',
        ]);

        $model = CarModel::create([
            'name' => $validated['name'],
            'make_id' => $validated['make_id'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $model->id,
                'name' => $model->name,
            ]
        ]);
    }
}
