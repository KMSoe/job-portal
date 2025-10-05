<?php
namespace Modules\Organization\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\State;
use Nnjeim\World\Models\Currency;

class WorldController extends Controller
{
    public function getAllCountries()
    {
        $countries = Country::all();

        return response()->json([
            'status' => true,
            'data'   => [
                'countries' => $countries,
            ],
        ], 200);
    }

    public function getAllCities(Request $request)
    {
        $cities = City::query();

        if(isset($request->state_id)) {
            $cities->where('state_id', $request->state_id);
        }

        if(isset($request->country_id)) {
            $cities->where('country_id', $request->country_id);
        }

        $cities = $cities->get();

        return response()->json([
            'status' => true,
            'data'   => [
                'cities' => $cities,
            ],
        ], 200);
    }

    public function getAllStates(Request $request)
    {
        $states = State::query();

        if(isset($request->country_id)) {
            $states->where('country_id', $request->country_id);
        }

        $states = $states->get();

        return response()->json([
            'status' => true,
            'data'   => [
                'states' => $states,
            ],
        ], 200);
    }

    public function getAllCurrencies()
    {
        $currencies = Currency::all();

        return response()->json([
            'status' => true,
            'data'   => [
                'currencies' => $currencies,
            ],
        ], 200);
    }
}
