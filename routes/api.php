<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ReportController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('investors', InvestorController::class);

    Route::apiResource('projects', ProjectController::class);

    Route::apiResource('incentives', IncentiveController::class);

    Route::get('applications', [ApplicationController::class, 'index']); 
    Route::post('applications', [ApplicationController::class, 'store']); 
    Route::get('applications/{id}', [ApplicationController::class, 'show']);
    Route::post('applications/{id}/decide', [ApplicationController::class, 'decide']); 

    Route::get('reports/summary', [ReportController::class, 'summary']);

    // New route for AI summarization
    Route::post('reports/summarize', function(Request $request) {
        $text = $request->input('text');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GEMINI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://gemini.googleapis.com/v1/models/gemini-2.5-flash:generateContent', [
            'contents' => $text
        ]);

        return $response->json();
    });
});
