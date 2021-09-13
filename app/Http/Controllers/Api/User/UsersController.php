<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Traits\Api\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Users']);
    }


    /**
     * Display a listing of the user's users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::doesnthave('roles')->get();

        return !$users->count()
            ? $this->noContent()
            : $this->success($users, 'Users fetched successfully.');
    }
}
