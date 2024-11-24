<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function getUsersByUsername($username){
        $users = User::
        where('username','like' ,$username."%")
        ->whereNot('id', auth()->id())
        ->get()->toArray();

        return response()->json(["message" => "user List", "data"=> $users]);
    }
}
