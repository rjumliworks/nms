<?php

namespace App\Http\Controllers\Modules\Trips;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListName;
use App\Models\Buyer;
use App\Models\Employee;
use App\Models\FishSpecies;
use App\Services\DropdownClass;
use App\Services\Modules\Trips\Trip\SaveClass;
use App\Services\Modules\Trips\Trip\ViewClass;
use App\Http\Requests\Modules\Trips\TripRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class TripController extends Controller
{
    use HandlesTransaction;

    protected ViewClass $view;
    protected SaveClass $save;
    protected DropdownClass $dropdown;

    public function __construct(SaveClass $save, ViewClass $view, DropdownClass $dropdown){
        $this->view = $view;
        $this->save = $save;
        $this->dropdown = $dropdown;
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
            return inertia('Modules/Trips/Trip/Index',[
                'boats' => ListName::where('type', 'Boat')->where('is_active', 1)->get(),
                'stats' => $this->view->stats($request),
            ]);
        }
    }

    public function print(Request $request){
        $trips = $this->view->forPrint($request);

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
        ];

        $monthLabel = $request->month ? ($months[(int) $request->month] ?? 'All Months') : 'All Months';
        $year = $request->year ?: now()->year;

        $pdf = Pdf::loadView('prints.trips', [
            'trips' => $trips,
            'monthLabel' => $monthLabel,
            'year' => $year,
        ])->setPaper('a4', 'portrait');

        $filename = 'trips-' . strtolower(str_replace(' ', '-', $monthLabel)) . '-' . $year . '.pdf';

        return $pdf->stream($filename);
    }

    public function store(TripRequest $request){
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

    public function show($id){
        return inertia('Modules/Trips/Trip/View', [
            'trip_data' => $this->view->show($id),
            'names' => [
                'Buyer' => Buyer::where('is_active', 1)->orderBy('lastname')->get(),
                'Truck' => ListName::where('type', 'Truck')->where('is_active', 1)->get(),
                'Carrier' => ListName::where('type', 'Carrier')->where('is_active', 1)->get(),
                'Fish' => FishSpecies::where('is_active', 1)->orderBy('name')->get(),
                'Employee' => Employee::where('is_active', 1)->orderBy('lastname')->get(),
            ],
            'categories' => [
                'Expense' => $this->dropdown->dropdowns('Expense', 'Category'),
                'Loan' => $this->dropdown->dropdowns('Loan', 'Category'),
            ],
        ]);
    }

    public function update(TripRequest $request){
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
