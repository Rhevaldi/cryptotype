<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/live-prices', [HomeController::class, 'getPricesApi'])->name('api.live-prices');

Route::get('/api/realtime-prices', function () {
    $pricesData = Cache::remember('realtime_crypto_prices', 2, function () {
        try {
            $response = Http::withoutVerifying()->timeout(3)->get('https://api.coincap.io/v2/assets', [
                'ids' => 'bitcoin,ethereum,binance-coin,ripple,solana,tron'
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                $result = [];
                $map = [
                    'bitcoin' => 'bitcoin',
                    'ethereum' => 'ethereum',
                    'binance-coin' => 'binancecoin',
                    'ripple' => 'ripple',
                    'solana' => 'solana',
                    'tron' => 'tron'
                ];

                foreach ($data as $coin) {
                    if (isset($map[$coin['id']])) {
                        $result[$map[$coin['id']]] = ['usd' => (float) $coin['priceUsd']];
                    }
                }
                return $result;
            }
        } catch (\Throwable $e) {}

        return [];
    });

    return response()->json($pricesData ?? []);
});