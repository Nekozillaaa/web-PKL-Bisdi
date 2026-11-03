<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabItem;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabItemController extends Controller
{
    public function index(Request $request)
    {
        $query = LabItem::with('laboratory')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('laboratory_id')) {
            $query->where('laboratory_id', $request->laboratory_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $items = $query->paginate(10)->withQueryString();
        $laboratories = Laboratory::where('is_active', true)->get();

        $stats = [
            'total_items' => LabItem::count(),
            'total_units' => LabItem::sum('total_quantity'),
            'available_units' => LabItem::sum('available_quantity'),
            'damaged_units' => LabItem::whereIn('condition', ['slightly_damaged', 'damaged'])->count(),
        ];

        return view('admin.items.index', compact('items', 'laboratories', 'stats'));
    }

    public function create()
    {
        $laboratories = Laboratory::where('is_active', true)->get();

        return view('admin.items.create', compact('laboratories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'laboratory_id' => ['required', 'exists:laboratories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:lab_items,code'],
            'description' => ['nullable', 'string'],
            'total_quantity' => ['required', 'integer', 'min:1'],
            'available_quantity' => ['required', 'integer', 'min:0', 'lte:total_quantity'],
            'condition' => ['required', 'in:good,slightly_damaged,damaged'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        LabItem::create($validated);

        return redirect()->route('admin.items.index')->with('success', 'Barang laboratorium berhasil ditambahkan.');
    }

    public function edit(LabItem $item)
    {
        $laboratories = Laboratory::where('is_active', true)->get();

        return view('admin.items.edit', compact('item', 'laboratories'));
    }

    public function update(Request $request, LabItem $item)
    {
        $validated = $request->validate([
            'laboratory_id' => ['required', 'exists:laboratories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('lab_items', 'code')->ignore($item->id)],
            'description' => ['nullable', 'string'],
            'total_quantity' => ['required', 'integer', 'min:1'],
            'available_quantity' => ['required', 'integer', 'min:0', 'lte:total_quantity'],
            'condition' => ['required', 'in:good,slightly_damaged,damaged'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $item->update($validated);

        return redirect()->route('admin.items.index')->with('success', 'Data barang laboratorium berhasil diperbarui.');
    }

    public function destroy(LabItem $item)
    {
        $name = $item->name;
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', "Barang '{$name}' berhasil dihapus.");
    }
}
