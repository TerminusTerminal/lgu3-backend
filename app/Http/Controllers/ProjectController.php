<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    // List all projects (optionally filter by archived)
    public function index(Request $r)
    {
        $query = Project::with('investor');

        if ($r->has('archived')) {
            $archived = filter_var($r->archived, FILTER_VALIDATE_BOOLEAN);
            $query->where('archived', $archived ? 1 : 0);
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    // Create a new project
    public function store(Request $r)
    {
        $data = $r->validate([
            'investor_id' => 'required|exists:investors,id',
            'name' => 'required|string',
            'sector' => 'nullable|string',
            'investment_amount' => 'nullable|numeric',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $proj = Project::create($data);
        return response()->json($proj, 201);
    }

    // Show a single project
    public function show(Project $project)
    {
        return $project->load('investor', 'applications');
    }

    // Update an existing project
    public function update(Request $r, Project $project)
    {
        $data = $r->only([
            'name',
            'sector',
            'investment_amount',
            'location',
            'description',
            'status',
        ]);

        $project->update($data);
        return response()->json($project);
    }

    // Soft delete (if you're using an "archived" column, not soft deletes)
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'deleted']);
    }

    // Archive a project (set archived = 1)
    public function archive($id)
    {
        $project = Project::findOrFail($id);
        $project->archived = 1;
        $project->save();

        return response()->json(['message' => 'Project archived']);
    }

    // Restore a project (set archived = 0)
    public function restore($id)
    {
        $project = Project::findOrFail($id);
        $project->archived = 0;
        $project->save();

        return response()->json(['message' => 'Project restored']);
    }
}
