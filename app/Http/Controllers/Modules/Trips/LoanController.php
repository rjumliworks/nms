<?php

namespace App\Http\Controllers\Modules\Trips;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListName;
use App\Services\DropdownClass;
use App\Services\Modules\Trips\Loan\SaveClass;
use App\Services\Modules\Trips\Loan\ViewClass;
use App\Http\Requests\Modules\Trips\LoanRequest;

class LoanController extends Controller
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
            default:
            return inertia('Modules/Trips/CashAdvance/Index',[
                'categories' => $this->dropdown->dropdowns('Loan', 'Category'),
                'breakdown' => $this->view->breakdown(),
                'names' => [
                    'Person' => ListName::where('type', 'Person')->where('is_active', 1)->get(),
                ],
            ]);
        }
    }

    public function store(LoanRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            if ($request->option === 'pay') {
                return $this->save->pay($request);
            }
            return $request->editable ? $this->save->update($request) : $this->save->store($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(LoanRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'pay':
                    return $this->save->pay($request);
                break;
                default:
                    return $this->save->update($request);
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
