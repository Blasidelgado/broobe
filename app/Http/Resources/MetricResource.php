<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetricResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $possibleCategories = ['performance', 'accessibility', 'best-practices', 'seo', 'pwa'];
        $currentDateTime = now()->toDateTimeString();
        $result = [
            'url' => $request->input('url'),
            'strategy' => $request->input('strategy'),
            'datetime' => $currentDateTime,
        ];

        $categoriesFromApi = $this->resource['lighthouseResult']['categories'];

        foreach ($possibleCategories as $category) {
            if (isset($categoriesFromApi[$category])) {
                $categoryKey = strtoupper(str_replace('-', ' ', $category));
                $result[$categoryKey] = $categoriesFromApi[$category]['score'];
            } else {
                $categoryKey = strtoupper(str_replace('-', ' ', $category));
                $result[$categoryKey] = null;
            }
        }

        return $result;
    }
}
