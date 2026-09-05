<?php

namespace App\Http\Controllers\Modules\Trips;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Services\DropdownClass;
use App\Services\Modules\Trips\Expense\SaveClass;
use App\Services\Modules\Trips\Expense\ViewClass;
use App\Http\Requests\Modules\Trips\ExpenseRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpenseController extends Controller
{
    use HandlesTransaction;

    protected ViewClass $view;
    protected SaveClass $save;
    protected DropdownClass $dropdown;

    public function __construct(DropdownClass $dropdown, SaveClass $save, ViewClass $view){
        $this->dropdown = $dropdown;
        $this->view = $view;
        $this->save = $save;
    }

    public function index(Request $request){
        switch($request->options){
            case 'lists':
                return $this->view->list($request);
            break;
            case 'stats':
                return response()->json($this->view->stats($request));
            break;
            case 'analytics':
                return response()->json($this->view->analytics($request));
            break;
            default:
            return inertia('Modules/Trips/Expense/Index',[
                'categories' => $this->dropdown->dropdowns('Expense', 'Category'),
                'names' => [
                    'Employee' => Employee::where('is_active', 1)->orderBy('lastname')->get(),
                ],
                'stats' => $this->view->stats($request),
            ]);
        }
    }

    public function print(Request $request){
        $expenses = $this->view->forPrint($request);

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
        ];

        $monthLabel = $request->month ? ($months[(int) $request->month] ?? 'All Months') : 'All Months';
        $year = $request->year ?: now()->year;

        $pdf = Pdf::loadView('prints.expenses', [
            'expenses' => $expenses,
            'monthLabel' => $monthLabel,
            'year' => $year,
            'total' => $expenses->sum('amount'),
            'tripKeyword' => $request->trip_keyword,
        ])->setPaper('a4', 'portrait');

        $filenameSuffix = $request->trip_keyword ? '-' . strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $request->trip_keyword)) : '';
        $filename = 'expenses-' . strtolower(str_replace(' ', '-', $monthLabel)) . '-' . $year . $filenameSuffix . '.pdf';

        return $pdf->stream($filename);
    }

    public function store(ExpenseRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $request->editable ? $this->save->update($request) : $this->save->store($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(ExpenseRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->update($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
