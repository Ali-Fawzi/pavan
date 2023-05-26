<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;
use http\Env\Request;
use Illuminate\Http\JsonResponse;

class UsersRepository implements IUsersRepository
{


    /**
     * Returns a JSON response with the users and their roles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(User::select('id', 'name', 'role_id','email')->with('role:id,name')->get());
    }

}
