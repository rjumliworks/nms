<?php

namespace App\Http\Controllers\Modules\Trips;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListName;
use App\Services\Modules\Trips\Trip\SaveClass;
use App\Services\Modules\Trips\Trip\ViewClass;
use App\Http\Requests\Modules\Trips\TripRequest;

class TripController extends Controller
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
            return inertia('Modules/Trips/Trip/Index',[
                'boats' => ListName::where('type', 'Boat')->where('is_active', 1)->get(),
                'names' => [
                    'Carrier' => ListName::where('type', 'Carrier')->where('is_active', 1)->get(),
                    'Fish' => ListName::where('type', 'Fish')->where('is_active', 1)->get(),
                ],
            ]);
        }
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
