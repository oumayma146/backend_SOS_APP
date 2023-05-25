<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class MapsController extends Controller
{
    
    public function getClosestStation(Request $request)
{
    $latitude = $request->input('latitude');
    $longitude = $request->input('longitude');
    $apiKey = env('GOOGLE_MAPS_API_KEY');

    $types = ['police','car_repair'];
    $results = [];

  
        $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
             
                'key' => $apiKey,
                'location' => "{$latitude},{$longitude}",
                'radius' => 25000,
                'types' => 'police'
            
        ]);
        $results['police'] = json_decode($response->getBody(), true);

        $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
             
            'key' => $apiKey,
            'location' => "{$latitude},{$longitude}",
            'radius' => 25000,
            'types' => 'car_repair'
        
    ]);
    $results['car_repair']= json_decode($response->getBody(), true);

    $nearestPoliceStation = null;
    $nearestCarRepair = null;
    $name = [];
    $formatted_phone_number = [];
    
    if (isset($results['car_repair']) && isset($results['police'])) {
        $policeStations = collect($results['police']['results']);
        $carRepairShops = collect($results['car_repair']['results']);
        
        $name = $policeStations->pluck('name');
        $name_car = $carRepairShops->pluck('name');
        
        $nearestPoliceStation = [];
        $nearestCarRepair = [];
    
        foreach ($name as $index => $policeName) {
            $nearestPoliceStation[] = [
                'name' => $policeName,
                'phone' => 193,
            ];
        }
    
        foreach ($name_car as $index => $carRepairName) {
            $nearestCarRepair[] = [
                'name' => $carRepairName,
                'phone' => ($index === 0) ? '27555555':'26052052',
            ];
        }
    }
    
        return response()->json([
            'nearest_police_station' => $nearestPoliceStation,
            'nearest_car_repair' => $nearestCarRepair
        ]);

}}