<?php

namespace App\Http\Controllers;

use App\Models\Chat;

use App\Models\GeneralChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\BaseController;

class ChatController extends BaseController
{
    public function question($id, Request $request)
    {
        try {


            if (Auth::user()->id != $id) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }


            $chatHis = Chat::where('user_id', $id)
                ->latest()
                ->take(6)
                ->orderBy('id', 'desc')
                ->get();

            $chat = collect(array_reverse($chatHis->toArray()));

            $chatHistory = $chat->map(function ($item) {
                return [
                    'role' => $item['type'] == 1 ? 'user' : 'ai',
                    'content' => $item['message'],
                ];
            })->toArray();

            $userQuestion = [
                'role' => 'user',
                'content' => $request->message,
            ];
            array_push($chatHistory, $userQuestion);

            $resFromAi = Http::post(
                'https://7970aa8a-7757-4760-a5bf-e1e27f54654a-00-1rwbasf2xvnel.pike.replit.dev/mock',
                $chatHistory
            );
            if ($resFromAi->failed()) {
                return response()->json([
                    'message' => 'Error from AI service',
                ], 500);
            }

            $data = $resFromAi->json();

            // type 1 = question
            // type 2 = answer from ai
            $save_msg = Chat::create([
                'user_id' => Auth::user()->id,
                'message' => $request->message,
                'type' => 1,
            ]);
            $save_msg = Chat::create([
                'user_id' => Auth::user()->id,
                'message' => $data['message'],
                'type' => 2,

            ]);

            return response()->json(
                $data,
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function generalHistory($id, Request $request)
    {
        if ($request->user()->id != $id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $history = GeneralChat::where('user_id', $id)->get();
        // $data['data'] = $history;
        return response()->json($history);
    }
    public function mockhistory($id, Request $request)
    {
        if ($request->user()->id != $id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        $history = Chat::where('user_id', $id)->get();
        // $data['data'] = $history;
        return response()->json($history);
    }


    public function generalChat($id, Request $request)
    {

        if (Auth::user()->id != $id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $request->validate([
            'message' => 'required|string',
        ]);




        $chatHis = GeneralChat::where('user_id', $id)
            ->latest()
            ->take(6)
            ->orderBy('id', 'desc')
            ->get();

        $chat = collect(array_reverse($chatHis->toArray()));

        $chatHistory = $chat->map(function ($item) {
            return [
                'role' => $item['type'] == 1 ? 'user' : 'ai',
                'content' => $item['message'],
            ];
        })->toArray();

        $userQuestion = [
            'role' => 'user',
            'content' => $request->message,
        ];

        array_push($chatHistory, $userQuestion);

        $resFromAi = Http::post(
            'https://7970aa8a-7757-4760-a5bf-e1e27f54654a-00-1rwbasf2xvnel.pike.replit.dev/gen',
            $chatHistory
        );
        if ($resFromAi->failed()) {
            return response()->json([
                'message' => 'Error from AI service',
            ], 500);
        }

        $data = $resFromAi->json();

        // type 1 = question
        // type 2 = answer from ai
        $save_msg = GeneralChat::create([
            'user_id' => Auth::user()->id,
            'message' => $request->message,
            'type' => 1,
        ]);
        $save_msg = GeneralChat::create([
            'user_id' => Auth::user()->id,
            'message' => $data['message'],
            'type' => 2,

        ]);

        return response()->json(
            $data,
            // $chat,
            200
        );
    }
}
