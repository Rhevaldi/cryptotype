<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'onchain');
        $apiKey = env('COINGECKO_API_KEY');

        // 1. Ticker Realtime Prices (CoinGecko API dengan Key)
        $prices = Cache::remember('ticker_prices_live', 5, function () use ($apiKey) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(4)
                    ->withHeaders(['x-cg-demo-api-key' => $apiKey])
                    ->get('https://api.coingecko.com/api/v3/simple/price', [
                        'ids' => 'bitcoin,ethereum,binancecoin,ripple,solana,tron',
                        'vs_currencies' => 'usd',
                        'include_24hr_change' => 'true'
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'btc' => ['usd' => $data['bitcoin']['usd'] ?? 0, 'usd_24h_change' => $data['bitcoin']['usd_24h_change'] ?? 0],
                        'eth' => ['usd' => $data['ethereum']['usd'] ?? 0, 'usd_24h_change' => $data['ethereum']['usd_24h_change'] ?? 0],
                        'bnb' => ['usd' => $data['binancecoin']['usd'] ?? 0, 'usd_24h_change' => $data['binancecoin']['usd_24h_change'] ?? 0],
                        'xrp' => ['usd' => $data['ripple']['usd'] ?? 0, 'usd_24h_change' => $data['ripple']['usd_24h_change'] ?? 0],
                        'sol' => ['usd' => $data['solana']['usd'] ?? 0, 'usd_24h_change' => $data['solana']['usd_24h_change'] ?? 0],
                        'trx' => ['usd' => $data['tron']['usd'] ?? 0, 'usd_24h_change' => $data['tron']['usd_24h_change'] ?? 0],
                    ];
                }
            } catch (\Throwable $e) {}

            return [
                'btc' => ['usd' => 64770.00, 'usd_24h_change' => 1.10],
                'eth' => ['usd' => 1912.93, 'usd_24h_change' => 0.00],
                'bnb' => ['usd' => 602.31, 'usd_24h_change' => -0.60],
                'xrp' => ['usd' => 1.00, 'usd_24h_change' => 0.00],
            ];
        });

        // 2. Data Kategori
        $categoryData = Cache::remember('cat_live_' . $category, 10, function () use ($category, $apiKey) {
            if ($category === 'us-stock') {
                return [
                    ['symbol' => 'AAPL', 'name' => 'Apple Inc.', 'price' => 224.23, 'change' => 1.45, 'url' => '#'],
                    ['symbol' => 'NVDA', 'name' => 'NVIDIA Corp.', 'price' => 128.10, 'change' => -0.85, 'url' => '#'],
                    ['symbol' => 'TSLA', 'name' => 'Tesla Inc.', 'price' => 210.50, 'change' => 3.20, 'url' => '#'],
                    ['symbol' => 'MSFT', 'name' => 'Microsoft Corp.', 'price' => 448.90, 'change' => 0.15, 'url' => '#'],
                ];
            }

            if ($category === 'degen') {
                return [
                    ['chainId' => 'solana', 'baseToken' => ['name' => 'POPCAT', 'symbol' => 'POPCAT'], 'priceUsd' => '0.7420', 'volume' => ['h24' => 12450000], 'url' => '#'],
                    ['chainId' => 'solana', 'baseToken' => ['name' => 'WIF', 'symbol' => 'WIF'], 'priceUsd' => '1.8200', 'volume' => ['h24' => 45000000], 'url' => '#'],
                    ['chainId' => 'ethereum', 'baseToken' => ['name' => 'PEPE', 'symbol' => 'PEPE'], 'priceUsd' => '0.000008', 'volume' => ['h24' => 89000000], 'url' => '#'],
                    ['chainId' => 'solana', 'baseToken' => ['name' => 'MEW', 'symbol' => 'MEW'], 'priceUsd' => '0.0054', 'volume' => ['h24' => 8200000], 'url' => '#'],
                ];
            }

            try {
                $response = Http::withoutVerifying()
                    ->timeout(4)
                    ->withHeaders(['x-cg-demo-api-key' => $apiKey])
                    ->get('https://api.coingecko.com/api/v3/coins/markets', [
                        'vs_currency' => 'usd',
                        'order' => 'market_cap_desc',
                        'per_page' => 8,
                        'page' => 1,
                        'sparkline' => 'false'
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Throwable $e) {}

            return [];
        });

        // 3. News Feed
        $news = Cache::remember('news_feed', 300, function () {
            try {
                $res = Http::withoutVerifying()->timeout(4)->get('https://api.rss2json.com/v1/api.json?rss_url=https://www.coindesk.com/arc/outboundfeeds/rss/');
                if ($res->successful()) {
                    return array_slice($res->json()['items'] ?? [], 0, 3);
                }
            } catch (\Throwable $e) {}
            return [];
        });

        return view('home', compact('prices', 'categoryData', 'category', 'news'));
    }
}