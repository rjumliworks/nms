<?php

namespace App\Http\Controllers\Modules\Trips;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use App\Services\Modules\Trips\Carrier\SaveClass;
use App\Http\Requests\Modules\Trips\CarrierRequest;

class CarrierController extends Controller
{
    use HandlesTransaction;

    protected SaveClass $save;

    public function __construct(SaveClass $save){
        $this->save = $save;
    }

    public function store(CarrierRequest $request){
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
