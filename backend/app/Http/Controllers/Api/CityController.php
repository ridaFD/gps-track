<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Get list of cities from cities.json file.
     */
    public function index(Request $request)
    {
        $citiesFile = base_path('cities.json');
        
        if (!file_exists($citiesFile)) {
            return response()->json([
                'data' => [],
                'message' => 'Cities file not found',
            ]);
        }

        $cities = json_decode(file_get_contents($citiesFile), true);
        
        if (!is_array($cities)) {
            return response()->json([
                'data' => [],
                'message' => 'Invalid cities data',
            ]);
        }

        // Filter cities if search query provided
        $search = $request->get('search');
        if ($search) {
            $cities = array_filter($cities, function ($city) use ($search) {
                return stripos($city, $search) !== false;
            });
        }

        return response()->json([
            'data' => array_values($cities),
        ]);
    }
}
