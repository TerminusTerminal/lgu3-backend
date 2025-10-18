<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incentive;

class IncentiveController extends Controller
{
    public function index() {
        return Incentive::paginate(20);
    }

    public function store(Request $r) {
        $data = $r->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'type'=>'nullable|string',
            'max_amount'=>'nullable|numeric',
            'duration_months'=>'nullable|integer',
            'conditions'=>'nullable|string',
            'active'=>'nullable|boolean'
        ]);
        $inc = Incentive::create($data);
        return response()->json($inc, 201);
    }

    public function show(Incentive $incentive) {
        return $incentive;
    }

    public function update(Request $r, Incentive $incentive) {
        $incentive->update($r->only(['title','description','type','max_amount','duration_months','conditions','active']));
        return $incentive;
    }

    public function destroy(Incentive $incentive) {
        $incentive->delete();
        return response()->json(['message'=>'deleted']);
    }
}
