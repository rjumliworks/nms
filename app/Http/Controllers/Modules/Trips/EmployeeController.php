<?php

namespace App\Http\Controllers\Modules\Trips;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Modules\Trips\Employee\SaveClass;
use App\Services\Modules\Trips\Employee\ViewClass;
use App\Http\Requests\Modules\Trips\EmployeeRequest;

class EmployeeController extends Controller
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
            return inertia('Modules/Trips/Employee/Index');
        }
    }

    public function store(EmployeeRequest $request){
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

    public function update(EmployeeRequest $request){
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
