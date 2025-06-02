<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArrivalRequest;
use App\Services\ArrivalService;
use App\Models\Product;
use App\Models\Arrival;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArrivalController extends Controller
{
    protected $arrivalService;

    public function __construct(ArrivalService $arrivalService)
    {
        $this->arrivalService = $arrivalService;
    }

    public function index(Request $request)
    {
        $query = Arrival::query();

        // Фильтр по названию
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Фильтр по дате
        if ($request->filled('date_from')) {
            $query->where('arrival_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('arrival_date', '<=', $request->date_to);
        }

        // Фильтр по количеству
        if ($request->filled('quantity_min')) {
            $query->where('quantity', '>=', $request->quantity_min);
        }
        if ($request->filled('quantity_max')) {
            $query->where('quantity', '<=', $request->quantity_max);
        }

        $arrivals = $query->orderByDesc('created_at')->paginate(15);
            
        return view('admin.arrivals.index', [
            'arrivals' => $arrivals,
            'filters' => $request->all()
        ]);
    }

    public function create()
    {
        return view('admin.arrivals.create');
    }

    public function store(ArrivalRequest $request)
    {
        $this->arrivalService->create($request->validated());

        return redirect()->route('admin.arrivals.index')
            ->with('success', 'Поступление товаров успешно создано');
    }

    public function edit(Arrival $arrival)
    {
        return view('admin.arrivals.edit', compact('arrival'));
    }

    public function update(ArrivalRequest $request, Arrival $arrival)
    {
        $arrival->update($request->validated());

        return redirect()->route('admin.arrivals.index')
            ->with('success', 'Поступление товаров успешно обновлено');
    }

    public function destroy(Arrival $arrival)
    {
        $arrival->delete();

        return redirect()->route('admin.arrivals.index')
            ->with('success', 'Поступление товаров успешно удалено');
    }
}