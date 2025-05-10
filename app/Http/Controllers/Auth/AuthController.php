<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\PasswordChangeRequest;

class AuthController extends BaseController
{

    public function login(LoginRequest $request)
    {

        $validated_credentials = $request->validated();

        if (!Auth::attempt($validated_credentials)) {

            $data['message'] = 'Invalid Email or password';
            $data['status'] = 401;
            return $this->errorResponse($data);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('authTokenOf' . $user->name)->plainTextToken;

        $data['message'] = 'Login successful';
        $data['token'] = $token;
        $data['token_type'] = 'Bearer';
        $data['code'] = 200;
        $data['data'] = [
            'user' => $user,
        ];
        return $this->successResponse($data);
    }



    public function register(RegisterRequest $request): JsonResponse
    {


        $validated_data = $request->validated();
        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'admin'
        ]);

        $token = $user->createToken('authTokenOf' . $user->name)->plainTextToken;

        $data['message'] = 'Registered Successfully';
        $data['data'] = $user;
        $data['token'] = $token;
        $data['token_type'] = 'Bearer';


        return $this->successResponse($data);
    }

    public function profile(User $user)
    {
        $data['message'] = 'User Profile';
        $data['data'] = $user;
        return $this->successResponse($data);
    }


    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        if ($user->token == null) {
            $data = [];
            return $this->successResponse($data);
        }
    }

    public function passwordChange(PasswordChangeRequest $request, User $user)
    {
        if (Auth::user()->cannot('update', $user)) {
            $data['message'] = 'You are not authorized to change this user password';
            $data['status'] = 401;
            return $this->errorResponse($data);
        }


        $validated_data = $request->validated();



        if (!password_verify($validated_data['old_password'], $user->password)) {
            $data['message'] = 'Your Password Is Incorrect';
            $data['status'] = 401;
            return $this->errorResponse($data);
        }

        $user = Auth::user();
        $user->password = Hash::make($validated_data['new_password']);
        $user->save();



        $data['message'] = 'Password changed successfully';
        return $this->successResponse($data);
    }

    // account delete method
    public function delete(User $user)
    {
        if (Auth::user()->cannot('delete', $user)) {
            $data['message'] = 'You are not authorized to delete this user';
            $data['status'] = 401;
            return $this->errorResponse($data);
        }

        $user->tokens()->delete();
        $user->delete();

        $data['message'] = 'Acccount Deleted Successfully';

        return $this->successResponse($data);
    }
}
