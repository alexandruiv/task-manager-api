<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\Project;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request, Project $project)
    {
        return new UserCollection($project->members);
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project->members()->syncWithoutDetaching([$request->user_id]);

        return new UserCollection($project->members);
    }

    public function destroy(Request $request, Project $project, int $member)
    {
        abort_if($project->creator_id === $member, 400, 'Cannot remove creator from project.');

        $project->members()->detach([$member]);

        return new UserCollection($project->members);
    }
}
