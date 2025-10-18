<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\IncentiveAllocation;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class ReportController extends Controller
{
    public function summary(Request $r)
    {
        $totalProjects = \App\Models\Project::count();
        $totalInvestors = \App\Models\Investor::count();
        $applications = Application::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->get();
        $total_allocated = IncentiveAllocation::sum('allocated_amount');

        return response()->json([
            'total_projects' => $totalProjects,
            'total_investors' => $totalInvestors,
            'applications_by_status' => $applications,
            'total_allocated_amount' => $total_allocated
        ]);
    }

    public function summarize(Request $request)
    {
        $apiKey = env('GEMINI_API_KEY');
        $reportText = $request->input('text');

        $client = new Client();
        try {
            $response = $client->post(
                'https://gemini.googleapis.com/v1/models/gemini-2.5-flash:generateContent',
                [
                    'headers' => [
                        'Authorization' => "Bearer $apiKey",
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'contents' => $reportText,
                    ],
                ]
            );

            $result = json_decode($response->getBody(), true);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate summary',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
