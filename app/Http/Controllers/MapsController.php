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

        // dd($data);
        $nearestPoliceStation = null;
        $nearestCarRepair = null;
        $name=[];
        $formatted_phone_number=[];
        if (isset($results['car_repair'])&& isset($results['police'])) {
           
            $name=collect($results['police']['results'])->pluck('name');
            $name_car=collect($results['car_repair']['results'])->pluck('name');
        //    dd($name);
                    $nearestPoliceStation = [
                        'name' =>  $name,
                        'phone' => isset($results['police']['formatted_phone_number']) ? $results['police']['formatted_phone_number'] : 'N/A'
                    ];
             
             
                    $nearestCarRepair = [
                        'name' =>  $name_car,
                        'phone' => isset($results['car_repair']['formatted_phone_number']) ? $results['car_repair']['formatted_phone_number'] : 'N/A'
                    ];
               
            
        }
    
        return response()->json([
            'nearest_police_station' => $nearestPoliceStation,
            'nearest_car_repair' => $nearestCarRepair
        ]);

}}