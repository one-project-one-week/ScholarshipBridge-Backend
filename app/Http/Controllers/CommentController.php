<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Http\Controllers\BaseController;


class CommentController extends BaseController
{

    public function create(CommentRequest $request)
    {
        $validated = $request->validated();

        // $comment =   Comment::create($validated);
        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'message' => $request->message,
            'scholarship_id' => $request->scholarship_id,
        ]);
        if (is_null($comment)) {
            return $this->errorResponse();
        }
        $data['data'] = $comment;

        return $this->successResponse($data);
    }

    public function showByUs()
    {
        $user = Auth::user();
        $comments = Comment::where('user_id', $user->id)->get();
        // $comments = Comment::with('scholarship')
        //     ->where('user_id', $user->id)
        //     ->get();

        if ($comments->isEmpty()) {
            return $this->errorResponse([
                'message' => 'No comments found for this user.',
                'status' => 404,
            ]);
        }

        return $this->successResponse([
            'data' => $comments,
            'message' => 'Comments retrieved successfully',
            'status' => 200,
        ]);
    }

    public function showByScholarship($sch_id)
    {
        $comments = Comment::where('scholarship_id', $sch_id)->get();

        if ($comments->isEmpty()) {
            return $this->errorResponse([
                'message' => 'No comments found for this scholarship.',
                'status' => 404,
            ]);
        }

        return $this->successResponse([
            'data' => $comments,
            'message' => 'Comments retrieved successfully',
            'status' => 200,
        ]);
    }
    public function delete(Comment $comment)
    {
        if (Auth::user()->cannot('delete', $comment)) {
            $data['message'] = 'Unauthorize';
            $data['status'] = 401;
            return $this->errorResponse($data);
        }
        $comment->delete();
        return $this->successResponse($data['data'] = null);
    }
}
