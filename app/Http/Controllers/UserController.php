<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function getList(Request $request): JsonResponse {
        $users = User::select('id', 'name')
            ->orderBy('name')
            ->get();
            // dd($users);
        return response()->json([
            'users' => $users
        ]);
    }
}
