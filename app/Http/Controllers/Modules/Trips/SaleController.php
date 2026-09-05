<?php

namespace App\Http\Controllers\Modules\Trips;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListName;
use App\Models\Buyer;
use App\Services\Modules\Trips\Sale\SaveClass;
use App\Services\Modules\Trips\Sale\ViewClass;
use App\Http\Requests\Modules\Trips\SaleRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    use HandlesTransaction;

    protected ViewClass $view;
    protected SaveClass $save;

    public function __construct(SaveClass $save, ViewClass $view){
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
            return inertia('Modules/Trips/Sale/Index',[
                'names' => [
                    'Buyer' => Buyer::where('is_active', 1)->orderBy('lastname')->get(),
                    'Truck' => ListName::where('type', 'Truck')->where('is_active', 1)->get(),
                ],
                'stats' => $this->view->stats($request),
            ]);
        }
    }

    public function print(Request $request){
        $sales = $this->view->forPrint($request);

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
        ];

        $monthLabel = $request->month ? ($months[(int) $request->month] ?? 'All Months') : 'All Months';
        $year = $request->year ?: now()->year;
        $reportTitle = $request->type === 'pending' ? 'Pending Sales Report' : 'Sales Report';

        $pdf = Pdf::loadView('prints.sales', [
            'sales' => $sales,
            'monthLabel' => $monthLabel,
            'year' => $year,
            'total' => $sales->sum('total'),
            'reportTitle' => $reportTitle,
            'tripKeyword' => $request->trip_keyword,
        ])->setPaper('a4', 'portrait');

        $filenamePrefix = $request->type === 'pending' ? 'pending-sales' : 'sales';
        $filenameSuffix = $request->trip_keyword ? '-' . strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $request->trip_keyword)) : '';
        $filename = $filenamePrefix . '-' . strtolower(str_replace(' ', '-', $monthLabel)) . '-' . $year . $filenameSuffix . '.pdf';

        return $pdf->stream($filename);
    }

    public function store(SaleRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->store($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(SaleRequest $request){
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
