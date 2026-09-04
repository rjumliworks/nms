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
            default:
            return inertia('Modules/Trips/Sale/Index',[
                'names' => [
                    'Buyer' => Buyer::where('is_active', 1)->orderBy('lastname')->get(),
                    'Truck' => ListName::where('type', 'Truck')->where('is_active', 1)->get(),
                ],
            ]);
        }
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
}
