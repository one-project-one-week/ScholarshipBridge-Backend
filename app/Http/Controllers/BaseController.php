<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    public function successResponse($data): JsonResponse
    {
        $response = array_merge([
            'success' => true,
            'message' => $data['message'] ?? 'Request was successful',
            'errors' => null,
            'data' => $data['data'] ?? null,

        ], array_filter([
            'token' => $data['token'] ?? null,
            'token_type' => $data['token_type'] ?? null
        ]));

        return response()->json($response, $data['status'] ?? 200);
    }

    // public function successResponse($data = [], $message = 'Request was successful', $status = 200)
    // {
    //     $response = [
    //         'success' => true,
    //         'message' => $message,
    //         'errors' => null,
    //         'data' => $data
    //     ];

    //     return response()->json($response, $status);
    // }


    public function errorResponse($data = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $data['message'] ?? 'Request failed',
            'errors' => $data['error'] ?? 'An error occurred',
            'data' =>  null
        ];

        return response()->json($response,  $data['status'] ?? 500);
    }
}
