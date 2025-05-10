<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveScholarshipController extends BaseController
{
    public function save(Scholarship $scholarship, User $user)
    {
        $user->scholarships()->syncWithoutDetaching($scholarship);
        return response()->json([
            'data' => 'success'
        ]);
    }

    public function show(User $user)
    {
        return response()->json($user->scholarships);
    }

    public function delete(Scholarship $scholarship, User $user)
    {
        $user->scholarships()->detach($scholarship);
        $data['message'] = 'success unsave';
        return response()->json($data);
    }
}
