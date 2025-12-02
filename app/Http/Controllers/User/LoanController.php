<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        // order by newest application (created_at) first
        $loans = Auth::user()->loans()->with('items')->orderBy('created_at', 'desc')->get();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $items = Item::all();
        return view('loans.create', compact('items'));
    }

    public function show(Loan $borrow)
    {
        $borrow->load('items');
        $loan = $borrow;
        return view('loans.show', compact('loan'));
    }

    /**
     * Export a loan to PDF (or show HTML if PDF package missing).
     */
    public function export(Loan $borrow)
    {
        $loan = $borrow->load('items', 'user');

        $data = [
            'loan' => $loan,
            'generated_at' => now(),
        ];
        // Render printable HTML and let the page trigger the browser print dialog.
        // This intentionally avoids PDF generation and forces a printable view.
        return view('loans.pdf', $data);
    }

    public function store(Request $request)
    {
        // Validate the loan-level fields first (items will be validated per-selection)
        $request->validate([
            'loan_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after_or_equal:loan_date',
            'purpose' => 'required|string|max:255',
            'consent' => 'accepted',
        ]);

    $selectedItems = collect($request->input('items', []))
        ->filter(function ($i) {
            return isset($i['selected']) && $i['selected'] == 1;
        });

    if ($selectedItems->isEmpty()) {
        return back()->with('error', 'Pilih minimal satu barang!');
    }

    foreach ($selectedItems as $itemId => $data) {
        $quantity = isset($data['quantity']) ? (int) $data['quantity'] : 0;
        $item = Item::find($itemId);
        if (!$item) {
            return back()->with('error', "Barang dengan ID {$itemId} tidak ditemukan.");
        }
        if ($quantity < 1) {
            return back()->with('error', "Jumlah untuk barang '{$item->name}' harus minimal 1.");
        }
        if ($quantity > $item->available_quantity) {
            return back()->with('error', "Jumlah untuk barang '{$item->name}' melebihi stok tersedia ({$item->available_quantity}).");
        }
    }

    DB::beginTransaction();
        try {
            $loan = Loan::create([
                'user_id' => Auth::id(),
                'loan_date' => $request->loan_date,
                'due_date' => $request->due_date,
                'status' => 'diajukan',
                'purpose' => $request->purpose,
            ]);

        foreach ($selectedItems as $itemId => $data) {
            $item = Item::findOrFail($itemId);
            $quantity = (int) $data['quantity'];

            $loan->items()->attach($itemId, ['quantity' => $quantity]);
        }

        DB::commit();

        return redirect()->route('user.borrow.index')
                         ->with('success', 'Peminjaman berhasil diajukan!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan saat menyimpan peminjaman.');
    }
}

}