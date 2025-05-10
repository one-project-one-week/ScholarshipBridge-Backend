<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\ScholarshipRequest;
use App\Http\Controllers\BaseController;




class ScholarshipController extends BaseController
{
    public function create(ScholarshipRequest $request)
    {

        // $validated = $request->validated();

        $scholarship = Scholarship::create([

            'title' => $request->title,
            'description' => $request->description,
            'organization' => $request->organization,
            'eligibility' => $request->eligibility,
            'category' => $request->category,
            'program' => $request->program,
            'field' => $request->field,
            'country' => $request->country,
            'deadline' => $request->deadline,
            'apply_link' => $request->apply_link,
            'photo_link' => $request->photo_link,
        ]);

        if (is_null($scholarship)) {
            return $this->errorResponse();
        }
        $data['data'] = $scholarship;
        return $this->successResponse($data);
    }

    public function showAll()
    {
        $scholarships = Scholarship::all();
        return $this->successResponse([
            'data' => $scholarships,
            'message' => 'All scholarships retrieved successfully',
            'status' => 200,
        ]);
    }

    public function showOne(Scholarship $scholarship)
    {
        $scholarship = scholarship::with('comments.user')->find($scholarship);
        // $comments = $scholarship->comments;

        if (($scholarship === null)) {
            return $this->errorResponse(['message' => 'Scholarship not found'], 404);
        }

        return $this->successResponse([
            'data' => $scholarship,

            // 'message' => $comments,
            'status' => 200,
        ]);
    }

    public function update(ScholarshipRequest $request, $id)
    {
        $user = Auth::user();

        if ($user->role != 'admin') {
            return response()->json(['message' => 'Unauthorized. Only admin can update scholarships.'], 403);
        }

        $scholarship = Scholarship::find($id); {
            if (is_null($scholarship)) {
                return $this->errorResponse(['message' => 'Scholarship not found'], 404);
            }

            $validated = $request->validated();
            $scholarship->update($validated);

            return $this->successResponse(['data' => $scholarship]);
        }
    }

    public function search(Request $request)
    {
        $query = $request->search;
        $scholarships = Scholarship::where('title', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->orWhere('organization', 'like', "%$query%")
            ->orWhere('eligibility', 'like', "%$query%")
            ->orWhere('category', 'like', "%$query%")
            ->orWhere('program', 'like', "%$query%")
            ->orWhere('field', 'like', "%$query%")
            ->orWhere('country', 'like', "%$query%")
            ->orWhere('deadline', 'like', "%$query%")

            ->get();
        return response()->json([

            $scholarships
        ]);
    }


    public function delete(Scholarship $scholarship)
    {
        $scholarship->delete();
        return $this->successResponse(['data' => null]);
    }
}
