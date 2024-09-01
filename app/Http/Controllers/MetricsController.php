<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetricsController extends Controller
{
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
