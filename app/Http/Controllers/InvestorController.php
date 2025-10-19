<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investor;

class InvestorController extends Controller
{
    public function index(Request $r)
    {
        $query = Investor::query();

        // ✅ Check for ?archived=1 or ?archived=0
        if ($r->has('archived')) {
            $query->where('archived', $r->archived == 1);
        }

        return $query->orderBy('id', 'desc')->paginate(20);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'type' => 'nullable|string',
            'tax_id' => 'nullable|string',
        ]);

        $data['archived'] = false; // default value

        $inv = Investor::create($data);
        return response()->json($inv, 201);
    }

    public function show(Investor $investor)
    {
        return $investor->load('projects', 'applications');
    }

    public function update(Request $r, Investor $investor)
    {
        $data = $r->only(['name', 'email', 'phone', 'address', 'type', 'tax_id']);
        $investor->update($data);
        return $investor;
    }

    public function archive($id)
    {
        $investor = Investor::findOrFail($id);
        $investor->archived = true;
        $investor->save();

        return response()->json(['message' => 'Investor archived successfully']);
    }

    public function restore($id)
    {
        $investor = Investor::findOrFail($id);
        $investor->archived = false;
        $investor->save();

        return response()->json(['message' => 'Investor restored successfully']);
    }

    public function destroy(Investor $investor)
    {
        // optional: keep delete disabled if you only use archiving
        $investor->delete();
        return response()->json(['message' => 'Investor deleted']);
    }
}
