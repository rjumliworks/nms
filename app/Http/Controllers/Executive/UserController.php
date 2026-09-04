<?php

namespace App\Http\Controllers\Executive;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Executive\Users\SaveClass;
use App\Services\Executive\Users\ViewClass;
use App\Http\Requests\Executive\UserRequest;

class UserController extends Controller
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
        switch($request->option){
            case 'list':
                return $this->view->list($request);
            break;
            case 'authentication-logs':
                return $this->view->authentications($request);
            break;
            case 'activity-logs':
                return $this->view->activities($request);
            break;
            default:
            return inertia('Executive/Users/Index',[
                'dropdowns' => [
                    'roles' => $this->dropdown->roles(),
                ],
                'counts' => $this->view->counts()
            ]);
        }
    }

    public function store(UserRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'avatar':
                    return $this->save->avatar($request);
                break;
                default:
                    return $this->save->store($request);
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(UserRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'status':
                    return $this->view->status($request);
                break;
                case 'credential':
                    return $this->view->credential($request);
                break;
                case 'role':
                    return $this->view->role($request);
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

    public function show($code){
        return inertia('Executive/Users/View',[
            'user_data' => $this->view->user($code),
            'dropdowns' => [
               'roles' => $this->dropdown->roles()
            ],
        ]);
    }
}
