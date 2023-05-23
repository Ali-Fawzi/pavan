<?php

namespace App\Http\Controllers;


use App\Repositories\Interfaces\IUsersRepository;
use App\Repositories\UsersRepository;

class UserController extends Controller
{
    private IUsersRepository $user;
    public function __construct(IUsersRepository $user)
    {
        $this->user = $user;
    }

    public function getLoggedInUserProfile()
    {
        // Retrieve the logged-in user
        $user = auth()->user();

        // Return the profile information of the logged-in user
        return response()->json(['user' => $user]);
    }
    public function index()
    {
        return $this->user->index();
    }
}
