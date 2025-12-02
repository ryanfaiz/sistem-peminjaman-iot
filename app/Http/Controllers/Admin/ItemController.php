<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:items',
            'total_quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'condition' => ['required', Rule::in(['Baik', 'Perlu Perawatan', 'Rusak'])],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('item_photos', 'public');
        }

        Item::create([
            'name' => $request->name,
            'code' => $request->code,
            'total_quantity' => $request->total_quantity,
            'available_quantity' => $request->total_quantity,
            'description' => $request->description,
            'condition' => $request->condition,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('admin.items.index')
                         ->with('success', 'Barang baru berhasil ditambahkan!');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('items')->ignore($item->id)], 
            'total_quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'condition' => ['required', Rule::in(['Baik', 'Perlu Perawatan', 'Rusak'])],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $quantityDifference = $request->total_quantity - $item->total_quantity;
        $newAvailableQuantity = $item->available_quantity + $quantityDifference;

        $photoPath = $item->photo_path;

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($item->photo_path) {
                Storage::disk('public')->delete($item->photo_path);
            }
            $photoPath = $request->file('photo')->store('item_photos', 'public');
        }

        $item->update([
            'name' => $request->name,
            'code' => $request->code,
            'total_quantity' => $request->total_quantity,
            'available_quantity' => max(0, $newAvailableQuantity),
            'description' => $request->description,
            'condition' => $request->condition,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('admin.items.index')
                         ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(Item $item): RedirectResponse
    {
        if ($item->available_quantity < $item->total_quantity) {
            return back()->with('error', 'Tidak dapat menghapus. Beberapa unit barang ini sedang dipinjam.');
        }

        if ($item->photo_path) {
            Storage::disk('public')->delete($item->photo_path);
        }

        $item->delete();

        return redirect()->route('admin.items.index')
                        ->with('success', 'Barang berhasil dihapus (soft delete)!');
    }   
}