<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCountryRequest;
class SportsController extends Controller
{
    public function create()
    {
        $sports = Sport::all();
        $countries = Country::all();
        $places = ['1st', '2nd', '3rd'];
        return view('sports.create', compact('sports', 'countries', 'places'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $fields = $request->validate([
            'score' => ['required', 'array'],
            'score.*' => ['required', 'array'],
            'score.*.*.medal' => ['required', 'in:1,2,3'],
            'score.*.*.country_id' => ['required', 'exists:countries,id'],
        ]);
        foreach ($request->score as $sportId => $data) {
            $sport = Sport::findOrFail($sportId);
            $sport->countries()->attach($data);
        }
        return redirect()->route('show');
    }

    public function show()
    {
        $countries = Country::query()
        ->withCountSport()
        ->orderByDesc('gold_count')
        ->orderByDesc('silver_count')
        ->orderByDesc('bronze_count')
        ->take(20)
        ->get();

        return view('sports.show', compact('countries'));
    }
}
