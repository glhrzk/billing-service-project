<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // Default ke bulan & tahun sekarang
        $selectedYear = $request->filled('year') ? $request->get('year') : now()->year;
        $selectedMonth = $request->filled('month') ? $request->get('month') : now()->month;
        $selectedType = $request->get('type');

        // Daftar tahun
        $availableYears = Income::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->filter()
            ->sortDesc()
            ->values()
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        // Income
        $incomeQuery = Income::select(
            DB::raw('created_at as date'),
            DB::raw("'income' as type"),
            'description',
            'amount'
        );

        // Expense
        $expenseQuery = Expense::select(
            DB::raw('created_at as date'),
            DB::raw("'expense' as type"),
            'description',
            'amount'
        );

        // Gabungkan income dan expense
        $query = $incomeQuery->unionAll($expenseQuery);
        $query = DB::table(DB::raw("({$query->toSql()}) as transactions"))
            ->mergeBindings($incomeQuery->getQuery())
            ->mergeBindings($expenseQuery->getQuery());

        // Filter
        if ($selectedYear) {
            $query->whereYear('date', $selectedYear);
        }
        if ($selectedMonth) {
            $query->whereMonth('date', $selectedMonth);
        }
        if ($selectedType) {
            $query->where('type', $selectedType);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        // Ringkasan
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;


        return view('admin.finances.index', compact(
            'transactions',
            'availableYears',
            'selectedYear',
            'selectedMonth',
            'selectedType',
            'totalIncome',
            'totalExpense',
            'balance',
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        Expense::create([
            'date' => $request->input('date'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    }
}
