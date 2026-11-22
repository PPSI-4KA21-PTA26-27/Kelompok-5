<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RajaOngkirService;

class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function getCities($provinceId)
    {
        $cities = $this->rajaOngkir->getCities($provinceId);
        return response()->json($cities);
    }

    public function calculateCost(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'weight'  => 'required|numeric|min:1',
            'courier' => 'required|string',
        ]);

        $costs = $this->rajaOngkir->getShippingCost(
            $request->city_id,
            $request->weight,
            $request->courier
        );

        return response()->json($costs);
    }
}