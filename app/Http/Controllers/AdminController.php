<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function userList()
    {


        $users = User::all();
        $data['data'] = $users;

        return $this->successResponse($data);
    }

    public function userDelete($id)
    {
        $user = User::find($id);
        if (!$user) {
            $data['message'] = 'User not found';
            $data['data'] = null;
            return $this->errorResponse($data);
        }
        $user->delete();
        return $this->successResponse('User deleted successfully');
    }
}
