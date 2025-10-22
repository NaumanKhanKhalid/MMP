<?php

namespace App\Http\Controllers;

use App\Models\CarMake;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarMakeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $makes = CarMake::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            return response()->json($makes);
        }
        
        $makes = CarMake::orderBy('name')->paginate(15);
        return view('car_makes.index', compact('makes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
        ]);
        $data['status'] = $data['status'] ?? 'active';

        CarMake::create($data);
        return redirect()->route('car-makes.index')->with('success', 'Car Make created successfully.');
    }

    public function update(Request $request, CarMake $make)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
        ]);
        $make->update($data);

        return redirect()->route('car-makes.index')->with('success', 'Car Make updated successfully.');
    }

    public function destroy(CarMake $make)
    {
        $make->delete();
        return redirect()->route('car-makes.index')->with('success', 'Car Make deleted successfully.');
    }

    public function toggleStatus(CarMake $make)
    {
        $make->status = $make->status === 'active' ? 'inactive' : 'active';
        $make->save();

        return redirect()->back()->with('success', 'Car Make status updated.');
    }

    /**
     * Quick add for Select2 tagging
     */
    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:car_makes,name',
        ]);

        $make = CarMake::create([
            'name' => $validated['name'],
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $make->id,
                'name' => $make->name,
            ]
        ]);
    }
}
