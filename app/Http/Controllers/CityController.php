<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{

    public function GetCities(Request $request)
    {
        $states = $request->get('states');
        if($states == true) {
            return response()->json(City::where('approved', true)->with('state')->get());
        }
        return response()->json(City::where('approved', true)->get());
    }

    public function GetCity($id, Request $request)
    {
        $states = $request->get('states');
        if($states == true) {
            return response()->json(City::where('approved', true)->with('state')->findOrFail($id));
        }
        return response()->json(City::where('approved', true)->findOrFail($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'state_id' => 'exists:states,id'
        ]);

        $city = new City;

        $city->name = $request->name;
        $city->state()->associate($request->state_id);
        $city->approved = false;
        $city->save();

        return response()->json($city, 201);
    }

    public function update($id, Request $request)
    {
        $city = City::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'state' => 'exists:states'
        ]);
        
        $city->name = $request->name;
        $city->state()->associate($request->state_id);
        $city->save();

        return response()->json($city, 200);
    }

    public function delete($id)
    {
        City::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Record deleted.',
        ], 204);
    }

    public function approve($id)
    {
        $city = City::where('approved', false)->findOrFail($id);
        $city->approved = true;
        $city->save();
        return response()->json($city, 200);
    }
}