<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;
use Illuminate\Http\JsonResponse;

class UsersRepository implements IUsersRepository
{
    public function index(): JsonResponse
    {
        return response()->json(User::all());
    }
}
