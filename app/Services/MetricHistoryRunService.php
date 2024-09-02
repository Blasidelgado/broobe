<?php

namespace App\Services;

use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use Exception;

class MetricHistoryRunService
{
    public function storeMetrics(array $validatedData)
    {
        try {
            $strategy = Strategy::where('name', $validatedData['strategy'])->firstOrFail();

            return MetricHistoryRun::create([
                'url' => $validatedData['url'],
                'strategy_id' => $strategy->id,
                'datetime' => $validatedData['datetime'],
                'seo_metric' => $validatedData['SEO'],
                'performance_metric' => $validatedData['PERFORMANCE'],
                'best_practices_metric' => $validatedData['BEST_PRACTICES'],
                'accessibility_metric' => $validatedData['ACCESSIBILITY'],
                'pwa_metric' => $validatedData['PWA'],
            ]);
        } catch (Exception $e) {
            throw new Exception("Failed attempting to save a new register: " . $e->getMessage());
        }
    }
}
