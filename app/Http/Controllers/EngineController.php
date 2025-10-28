<?php

namespace App\Http\Controllers;

use App\Models\Engine;
use Illuminate\Http\Request;

class EngineController extends Controller
{
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $query = Engine::where('status', 'active')->orderBy('code');
            
            if ($request->has('model_id')) {
                $query->whereHas('models', function($q) use ($request) {
                    $q->where('model_id', $request->model_id);
                });
            }
            
            $engines = $query->get(['id', 'code']);
            return response()->json($engines);
        }
        
        $engines = Engine::orderBy('code')->paginate(10);
        return view('engines.index', compact('engines'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:255|unique:engines,code',
            'displacement' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|max:255',
            'cylinder' => 'nullable|integer',
            'power' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $data['status'] = $data['status'] ?? 'active';
        Engine::create($data);

        return redirect()->route('engines.index')->with('success', 'Engine created successfully.');
    }

    public function update(Request $request, Engine $engine)
    {
        $data = $request->validate([
            'code' => 'required|string|max:255|unique:engines,code,' . $engine->id,
            'displacement' => 'nullable|string|max:255',
            'fuel_type' => 'nullable|string|max:255',
            'cylinder' => 'nullable|integer',
            'power' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $engine->update($data);

        return redirect()->route('engines.index')->with('success', 'Engine updated successfully.');
    }

    public function destroy(Engine $engine)
    {
        $engine->delete();
        return redirect()->route('engines.index')->with('success', 'Engine deleted successfully.');
    }

    public function toggleStatus(Engine $engine)
    {
        $engine->status = $engine->status === 'active' ? 'inactive' : 'active';
        $engine->save();

        return redirect()->back()->with('success', 'Engine status updated.');
    }

    /**
     * Quick add for Select2 tagging
     */
    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Use name as code for quick add
        $engine = Engine::create([
            'code' => $validated['name'],
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $engine->id,
                'name' => $engine->code,
            ]
        ]);
    }
}
