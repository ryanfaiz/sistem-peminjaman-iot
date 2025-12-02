<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Users by role as associative array ['mahasiswa' => 12, 'dosen' => 4, 'admin' => 1]
        $usersByRole = User::groupBy('role')
            ->select('role', DB::raw('count(*) as cnt'))
            ->pluck('cnt', 'role')
            ->toArray();

        // Loans per month for a given year (default: current year)
        $year = $request->get('year', now()->year);

        // Use `loan_date` if present; fall back to created_at
        $dateColumn = Schema::hasColumn('loans', 'loan_date') ? 'loan_date' : 'created_at';

        $loansPerMonth = Loan::selectRaw('MONTH(' . $dateColumn . ') as m, count(*) as cnt')
            ->whereYear($dateColumn, $year)
            ->groupBy('m')
            ->pluck('cnt', 'm')
            ->toArray();

        // Condition counts (total across all categories)
        // Expect Item::condition values like 'baik', 'rusak ringan', 'rusak berat'
        // `condition` is a reserved word in some SQL dialects, quote it.
        $conditionCounts = DB::table('items')
            ->selectRaw("COALESCE(`condition`, '') as cond, count(*) as cnt")
            ->groupBy('cond')
            ->pluck('cnt', 'cond')
            ->toArray();

        // Normalize keys to standard ones: baik, rusak_ringan, rusak_berat
        $normalized = [
            'baik' => 0,
            'rusak_ringan' => 0,
            'rusak_berat' => 0,
        ];
        foreach ($conditionCounts as $k => $v) {
            $key = str_replace([' ', '-'], '_', strtolower($k));
            if (array_key_exists($key, $normalized)) {
                $normalized[$key] = (int) $v;
            }
        }
        $conditionCounts = $normalized;

        // Overdue loans per month (by due_date) for the year
        $overduePerMonth = Loan::selectRaw('MONTH(due_date) as m, count(*) as cnt')
            ->whereYear('due_date', $year)
            ->where(function ($q) {
                $q->where('status', 'disetujui')->orWhere('status', 'dipinjam');
            })
            ->whereNull('returned_at')
            ->whereColumn('due_date', '<', DB::raw('NOW()'))
            ->groupBy('m')
            ->pluck('cnt', 'm')
            ->toArray();

        return view('reports.index', compact('usersByRole', 'loansPerMonth', 'conditionCounts', 'overduePerMonth'));
    }
}