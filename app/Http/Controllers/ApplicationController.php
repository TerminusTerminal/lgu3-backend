<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\IncentiveAllocation;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    public function index(Request $r) {
        $q = Application::with('investor','project','incentive');

        if ($r->has('status')) {
            $q->where('status', $r->status);
        }
        if ($r->has('investor_id')) {
            $q->where('investor_id', $r->investor_id);
        }

        return $q->orderBy('submitted_at','desc')->paginate(25);
    }

    public function store(Request $r) {
        $data = $r->validate([
            'investor_id'=>'required|exists:investors,id',
            'project_id'=>'required|exists:projects,id',
            'incentive_id'=>'required|exists:incentives,id',
            'requested_amount'=>'required|numeric',
            'remarks'=>'nullable|string'
        ]);

        $data['status'] = 'pending';
        $data['submitted_at'] = now();

        $app = Application::create($data);

        return response()->json($app->load('investor','project','incentive'), 201);
    }

    public function show($id) {
        return Application::with('investor','project','incentive','allocation')->findOrFail($id);
    }

    public function decide(Request $r, $id) {
        $app = Application::findOrFail($id);
        $data = $r->validate([
            'action'=>'required|in:approve,reject',
            'remarks'=>'nullable|string',
            'allocated_amount'=>'nullable|numeric',
            'start_date'=>'nullable|date',
            'end_date'=>'nullable|date'
        ]);

        if ($data['action'] === 'approve') {
            $app->status = 'approved';
            $app->remarks = $data['remarks'] ?? null;
            $app->decision_at = now();
            $app->save();

            $alloc = IncentiveAllocation::create([
                'application_id'=>$app->id,
                'incentive_id'=>$app->incentive_id,
                'allocated_amount'=>$data['allocated_amount'] ?? 0,
                'start_date'=>$data['start_date'] ?? now()->toDateString(),
                'end_date'=>$data['end_date'] ?? now()->addMonths(12)->toDateString(),
                'notes'=>$data['remarks'] ?? null,
            ]);

            return response()->json(['application'=>$app->load('investor','project','incentive'),'allocation'=>$alloc]);
        } else {
            $app->status = 'rejected';
            $app->remarks = $data['remarks'] ?? null;
            $app->decision_at = now();
            $app->save();
            return response()->json(['application'=>$app]);
        }
    }
}
