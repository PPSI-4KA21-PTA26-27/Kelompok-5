<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;
    protected $originCity;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = 'https://api.rajaongkir.com/starter';
        $this->originCity = env('RAJAONGKIR_ORIGIN_CITY', 151); // 151 = Jakarta Utara
    }

    /**
     * Ambil Data Seluruh Provinsi (Menggunakan API atau Master Data Config Lokal)
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', 86400, function () {
            try {
                $response = Http::withHeaders(['key' => $this->apiKey])
                    ->timeout(5)
                    ->withoutVerifying()
                    ->get("{$this->baseUrl}/province");

                if ($response->successful() && isset($response->json()['rajaongkir']['results'])) {
                    return $response->json()['rajaongkir']['results'];
                }
            } catch (\Exception $e) {
                Log::warning('RajaOngkir Connection Timeout, menggunakan master data provinsi lokal: ' . $e->getMessage());
            }

            // AMBIL SELURUH PROVINSI DARI CONFIG LOKAL
            return config('rajaongkir_data.provinces', []);
        });
    }

    /**
     * Ambil Data Kota Berdasarkan ID Provinsi (Menggunakan API atau Master Data Config Lokal)
     */
    public function getCities($provinceId)
    {
        return Cache::remember('rajaongkir_cities_' . $provinceId, 86400, function () use ($provinceId) {
            try {
                $response = Http::withHeaders(['key' => $this->apiKey])
                    ->timeout(5)
                    ->withoutVerifying()
                    ->get("{$this->baseUrl}/city", ['province' => $provinceId]);

                if ($response->successful() && isset($response->json()['rajaongkir']['results'])) {
                    return $response->json()['rajaongkir']['results'];
                }
            } catch (\Exception $e) {
                Log::warning('RajaOngkir Connection Timeout, menggunakan master data kota lokal: ' . $e->getMessage());
            }

            // AMBIL DAFTAR KOTA SESUAI PROVINSI DARI CONFIG LOKAL
            $allCities = config('rajaongkir_data.cities', []);

            return $allCities[$provinceId] ?? [
                ['city_id' => '151', 'type' => 'Kota', 'city_name' => 'Kota Tujuan (Simulasi)']
            ];
        });
    }

    /**
     * Hitung Ongkir Real-time / Fallback Estimasi
     */
    public function getShippingCost($destinationCityId, $weightGram, $courier)
    {
        try {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->timeout(5)
                ->withoutVerifying()
                ->post("{$this->baseUrl}/cost", [
                    'origin'      => $this->originCity,
                    'destination' => $destinationCityId,
                    'weight'      => $weightGram,
                    'courier'     => strtolower($courier),
                ]);

            if ($response->successful() && isset($response->json()['rajaongkir']['results'][0]['costs'])) {
                return $response->json()['rajaongkir']['results'][0]['costs'];
            }
        } catch (\Exception $e) {
            Log::warning('RajaOngkir Cost Connection Timeout, menggunakan estimasi ongkir simulasi: ' . $e->getMessage());
        }

        // ESTIMASI ONGKOS KIRIM SIMULASI
        $weightKg = ceil($weightGram / 1000);
        $baseRate = (strtolower($courier) === 'pos') ? 12000 : 15000;

        return [
            [
                'service' => 'REG',
                'description' => 'Layanan Reguler',
                'cost' => [
                    [
                        'value' => $baseRate * $weightKg,
                        'etd' => '2-3',
                        'note' => ''
                    ]
                ]
            ],
            [
                'service' => 'EXPRESS',
                'description' => 'Layanan Kilat',
                'cost' => [
                    [
                        'value' => ($baseRate + 10000) * $weightKg,
                        'etd' => '1-2',
                        'note' => ''
                    ]
                ]
            ]
        ];
    }
}