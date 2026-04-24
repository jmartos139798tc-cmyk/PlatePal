<?php

namespace App\Http\Controllers;

use App\Models\CatererProfile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCaterers = CatererProfile::where('is_featured', true)
            ->where('is_approved', true)
            ->where('profile_status', 'approved')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->take(6)
            ->get();

        return view('LandingPage.home', compact('featuredCaterers'));
    }

    public function browse(Request $request)
    {
        $query = CatererProfile::query()
            ->where('is_approved', true)
            ->where('profile_status', 'approved')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('is_featured')
            ->orderBy('business_name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('business_name', 'like', '%'.$request->search.'%')
                    ->orWhere('cuisine_type', 'like', '%'.$request->search.'%')
                    ->orWhere('barangay', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('location')) {
            $query->where('barangay', 'like', '%'.$request->location.'%');
        }

        if ($request->filled('price')) {
            switch ($request->price) {
                case 'budget':
                    $query->where('price_min', '<=', 400);
                    break;
                case 'mid':
                    $query->whereBetween('price_min', [400, 600]);
                    break;
                case 'premium':
                    $query->where('price_min', '>=', 600);
                    break;
            }
        }

        $selectedCuisines = collect($request->input('cuisine', []))
            ->filter()
            ->values();

        if ($selectedCuisines->isNotEmpty()) {
            $query->where(function ($q) use ($selectedCuisines) {
                foreach ($selectedCuisines as $cuisine) {
                    $q->orWhere('cuisine_type', 'like', '%'.$cuisine.'%');
                }
            });
        }

        if ($request->filled('rating')) {
            $query->whereRaw(
                'COALESCE((select avg(reviews.rating) from reviews where reviews.caterer_profile_id = caterer_profiles.id), 0) >= ?',
                [(float) $request->rating]
            );
        }

        $caterers = $query->paginate(6)->withQueryString();

        $availableBarangays = CatererProfile::query()
            ->whereNotNull('barangay')
            ->where('barangay', '!=', '')
            ->orderBy('barangay')
            ->pluck('barangay')
            ->unique()
            ->values();

        $availableCuisines = CatererProfile::query()
            ->whereNotNull('cuisine_type')
            ->where('cuisine_type', '!=', '')
            ->orderBy('cuisine_type')
            ->pluck('cuisine_type')
            ->unique()
            ->values();

        return view('Caterer.browse', compact(
            'caterers',
            'availableBarangays',
            'availableCuisines',
            'selectedCuisines'
        ));
    }
}
