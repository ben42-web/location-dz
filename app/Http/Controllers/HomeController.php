<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Property::with(['images', 'reviews'])
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $recent = Property::with(['images', 'reviews'])
            ->where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();

        $cities = Property::where('is_active', true)
            ->select('city', \DB::raw('count(*) as count'))
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        return view('home', compact('featured', 'recent', 'cities'));
    }
}
