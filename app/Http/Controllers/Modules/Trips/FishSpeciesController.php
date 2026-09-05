<?php

namespace App\Http\Controllers\Modules\Trips;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Modules\Trips\FishSpecies\SaveClass;
use App\Services\Modules\Trips\FishSpecies\ViewClass;
use App\Http\Requests\Modules\Trips\FishSpeciesRequest;

class FishSpeciesController extends Controller
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
            default:
            return inertia('Modules/Trips/FishSpecies/Index', [
                'categories' => $this->dropdown->dropdowns('Fish', 'Category'),
            ]);
        }
    }

    public function store(FishSpeciesRequest $request){
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

    public function update(FishSpeciesRequest $request){
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
