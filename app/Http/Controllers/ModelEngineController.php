<?php
namespace App\Http\Controllers;

use App\Models\ModelEngine;
use App\Models\Engine;
use App\Models\CarModel;
use Illuminate\Http\Request;

class ModelEngineController extends Controller
{
    public function index()
    {
        $modelEngines = ModelEngine::with(['model', 'engine'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        $carModels = CarModel::where('status', 'active')->orderBy('name')->get();
        $engines = Engine::where('status', 'active')->orderBy('code')->get();

        return view('model_engines.index', compact('modelEngines', 'carModels', 'engines'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'engine_id' => 'required|exists:engines,id',
            'notes' => 'nullable|string|max:255',
        ]);

        ModelEngine::create($data);

        return redirect()->route('model.engines.index')->with('success', 'Model Engine added successfully.');
    }

    public function update(Request $request, ModelEngine $modelEngine)
    {
        $data = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'engine_id' => 'required|exists:engines,id',
            'notes' => 'nullable|string|max:255',
        ]);

        $modelEngine->update($data);

        return redirect()->route('model.engines.index')->with('success', 'Model Engine updated successfully.');
    }

    public function destroy(ModelEngine $modelEngine)
    {
        $modelEngine->delete();
        return redirect()->route('model.engines.index')->with('success', 'Model Engine deleted successfully.');
    }

    public function toggleStatus(ModelEngine $modelEngine)
    {
        $modelEngine->status = $modelEngine->status === 'active' ? 'inactive' : 'active';
        $modelEngine->save();

        return redirect()->back()->with('success', 'Model Engine status updated.');
    }

}
