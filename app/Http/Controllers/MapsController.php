<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use alexpechkarev\GoogleMaps\GoogleMaps;
use App\Models\Station;
// use GoogleMaps\GoogleMaps;

class MapsController extends Controller
{
    public function getClosestStation(Request $request) {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $googleMaps = new GoogleMaps(['apiKey' => 'AIzaSyABaDps7PKHjLoVOnh6hEs_gGSDBKHfEeg']);
        // $client = new Client();
        // $client->setApiKey(env('GOOGLE_MAPS_API_KEY'));
    
        $location = $latitude . ',' . $longitude;
        $radius = 15000; // in meters
        $types = 'police|hospital|car_repair';

        $places = $googleMaps->nearbySearch([
            'location' => $location,
            'radius'   => $radius,
            'type'     => $types
        ]);
        $stations = [];
        foreach ($places['results'] as $place) {
            Station::create([
                'name' => $place['name'],
                'address' => $place['vicinity'],
                'phone_number' => $place['formatted_phone_number'] ?? null,
                'types' => $place['types'],
            ]);
            $stations = Station::all();
            // $result = [
            //     'name' => $place['name'],
            //     'address' => $place['vicinity'],
            //     'phone_number' => $place['formatted_phone_number'] ?? null,
            //     'types' => $place['types']
            // ];

            // $results[] = $result;
        
        }
        if (count($stations) > 0) {
            return response()->json([
                'success' => true,
                'station' => $stations
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No stations found within 15km of your location'
            ]);
        }
    }
   

}
