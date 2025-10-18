<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investor;

class InvestorController extends Controller
{
    public function index() {
        return Investor::paginate(20);
    }

    public function store(Request $r) {
        $data = $r->validate([
            'name'=>'required|string',
            'email'=>'nullable|email',
            'phone'=>'nullable|string',
            'address'=>'nullable|string',
            'type'=>'nullable|string',
            'tax_id'=>'nullable|string'
        ]);
        $inv = Investor::create($data);
        return response()->json($inv, 201);
    }

    public function show(Investor $investor) {
        return $investor->load('projects','applications');
    }

    public function update(Request $r, Investor $investor) {
        $data = $r->only(['name','email','phone','address','type','tax_id']);
        $investor->update($data);
        return $investor;
    }

    public function destroy(Investor $investor) {
        $investor->delete();
        return response()->json(['message'=>'deleted']);
    }
}
