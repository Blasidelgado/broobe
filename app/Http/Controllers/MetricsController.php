<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;
use App\Services\GooglePageSpeedService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Resources\MetricResource;


class MetricsController extends Controller
{
    protected $googlePageSpeedService;

    public function __construct(GooglePageSpeedService $googlePageSpeedService)
    {
        $this->googlePageSpeedService = $googlePageSpeedService;
    }

    /**
    * Return google page speed insights data
    */
    public function getMetrics(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'categories' => 'required|array|in:' . implode(',', config('const.categories')),
            'strategy' => 'required|string|in:' . implode(',', config('const.strategies'))
        ]);

        try {
            $metrics = $this->googlePageSpeedService->fetchMetrics(
                $validated['url'],
                $validated['categories'],
                $validated['strategy']
            );

            return new MetricResource($metrics);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * Load data for the initial page.
    */
    public function index(): View
    {
        $categories = Category::all();
        $strategies = Strategy::all();

        return view('metrics', compact('categories', 'strategies'));
    }
}
