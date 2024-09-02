<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MetricHistoryRun;
use App\Services\MetricHistoryRunService;
use Exception;

class MetricHistoryRunController extends Controller
{
    protected $metricHistoryRunService;

    public function __construct(MetricHistoryRunService $metricHistoryRunService)
    {
        $this->metricHistoryRunService = $metricHistoryRunService;
    }

    public function index()
    {
        $metrics = MetricHistoryRun::with('strategy')->get();
        return view('history', compact('metrics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'strategy' => 'required|string|in:DESKTOP,MOBILE',
            'datetime' => 'required|date_format:Y-m-d H:i:s',
            'SEO' => 'nullable|numeric|min:0|max:1',
            'PERFORMANCE' => 'nullable|numeric|min:0|max:1',
            'BEST_PRACTICES' => 'nullable|numeric|min:0|max:1',
            'ACCESSIBILITY' => 'nullable|numeric|min:0|max:1',
            'PWA' => 'nullable|numeric|min:0|max:1',
        ]);

        try {
            $metricHistoryRun = $this->metricHistoryRunService->storeMetrics($validated);

            return response()->json([
                'success' => true,
                'message' => 'Metrics saved successfully!',
                'data' => $metricHistoryRun
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
