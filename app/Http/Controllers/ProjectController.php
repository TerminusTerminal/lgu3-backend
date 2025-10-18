<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index() {
        return Project::with('investor')->paginate(20);
    }

    public function store(Request $r) {
        $data = $r->validate([
            'investor_id'=>'required|exists:investors,id',
            'name'=>'required|string',
            'sector'=>'nullable|string',
            'investment_amount'=>'nullable|numeric',
            'location'=>'nullable|string',
            'description'=>'nullable|string',
            'status'=>'nullable|string'
        ]);
        $proj = Project::create($data);
        return response()->json($proj, 201);
    }

    public function show(Project $project) {
        return $project->load('investor','applications');
    }

    public function update(Request $r, Project $project) {
        $data = $r->only(['name','sector','investment_amount','location','description','status']);
        $project->update($data);
        return $project;
    }

    public function destroy(Project $project) {
        $project->delete();
        return response()->json(['message'=>'deleted']);
    }
}
