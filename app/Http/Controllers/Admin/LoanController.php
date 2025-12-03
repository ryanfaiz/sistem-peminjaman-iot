<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('loans.index', compact('loans'));
    }

    public function edit(Loan $loan)
    {
        $loan->load(['user', 'approver', 'items']);

        return view('loans.edit', compact('loan'));
    }

    public function approve(Request $request, Loan $loan)
    {
        if ($loan->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman tidak dapat disetujui.');
        }
        $adminNote = trim($request->input('admin_notes', ''));

        try {
            DB::transaction(function () use ($loan, $adminNote) {
                foreach ($loan->items as $item) {

                    if ($item->available_quantity < $item->pivot->quantity) {
                        throw new \Exception("Stok barang '{$item->name}' tidak mencukupi.");
                    }

                    // Decrement available stock (atomic)
                    $item->decrement('available_quantity', $item->pivot->quantity);
                }

                // Append admin note if provided
                if ($adminNote !== '') {
                    $entry = '[' . Carbon::now()->format('Y-m-d H:i') . '] ' . Auth::user()->name . ' : ' . $adminNote;
                    $loan->admin_notes = trim(($loan->admin_notes ? $loan->admin_notes . "\n" : '') . $entry);
                }

                // Update peminjaman
                $loan->status = 'disetujui';
                $loan->approved_at = Carbon::now();
                $loan->approved_by = Auth::id();
                $loan->save();
            });

            return redirect()
                ->route('admin.loans.edit', $loan)
                ->with('success', 'Peminjaman berhasil disetujui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Loan $loan)
    {
        if ($loan->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman ini tidak dapat ditolak.');
        }

        $request->validate([
            'admin_notes' => 'required|string|min:5',
        ]);

        $adminNote = trim($request->input('admin_notes', ''));
        if ($adminNote !== '') {
            $entry = '[' . Carbon::now()->format('Y-m-d H:i') . '] ' . Auth::user()->name . ' : ' . $adminNote;
            $loan->admin_notes = trim(($loan->admin_notes ? $loan->admin_notes . "\n" : '') . $entry);
        }

        $loan->status = 'ditolak';
        $loan->save();

        return redirect()
            ->route('admin.loans.edit', $loan)
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function return(Request $request, Loan $loan)
    {
        $adminNote = trim($request->input('admin_notes', ''));

        try {
            DB::transaction(function () use ($loan, $adminNote) {
                $loan->load('items');

                foreach ($loan->items as $item) {
                    $qty = (int) ($item->pivot->quantity ?? 0);
                    if ($qty <= 0) continue;

                    $item->increment('available_quantity', $qty);

                    if (! is_null($item->total_quantity)) {
                        // perform a conditional update only when available_quantity > total_quantitys
                        DB::table('items')
                            ->where('id', $item->id)
                            ->whereColumn('available_quantity', '>', 'total_quantity')
                            ->update(['available_quantity' => $item->total_quantity]);
                    }
                }

                if ($adminNote !== '') {
                    $entry = '[' . Carbon::now()->format('Y-m-d H:i') . '] ' . Auth::user()->name . ' : ' . $adminNote;
                    $loan->admin_notes = trim(($loan->admin_notes ? $loan->admin_notes . "\n" : '') . $entry);
                }

                $loan->status = 'dikembalikan';
                $loan->returned_at = Carbon::now();
                $loan->save();
            });

            return redirect()->route('admin.loans.edit', $loan)
                ->with('success', 'Status pinjaman diubah menjadi Dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status menjadi Dikembalikan: ' . $e->getMessage());
        }
    }

    public function borrow(Request $request, Loan $loan)
    {
        $loan->status = 'dipinjam';

        $adminNote = trim($request->input('admin_notes', ''));
        if ($adminNote !== '') {
            $entry = '[' . Carbon::now()->format('Y-m-d H:i') . '] ' . Auth::user()->name . ' : ' . $adminNote;
            $loan->admin_notes = trim(($loan->admin_notes ? $loan->admin_notes . "\n" : '') . $entry);
        }

        $loan->save();

        return redirect()->route('admin.loans.edit', $loan)->with('success', 'Status dipinjam berhasil diupdate.');
    }



}