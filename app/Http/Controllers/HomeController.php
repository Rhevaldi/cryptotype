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

        // 1. Running Ticker (CoinGecko - Price & 24h Change)
        $prices = Cache::remember('crypto_prices', 120, function () {
            try {
                $response = Http::timeout(5)->get('https://api.coingecko.com/api/v3/simple/price', [
                    'ids' => 'bitcoin,ethereum,solana,binancecoin',
                    'vs_currencies' => 'usd',
                    'include_24hr_change' => 'true'
                ]);
                return $response->successful() ? $response->json() : [];
            } catch (\Exception $e) {
                return [];
            }
        });

        // 2. CoinDesk News Feed (RSS Parsing)
        $news = Cache::remember('coindesk_news', 300, function () {
            try {
                $rss = @simplexml_load_file('https://www.coindesk.com/arc/outboundfeeds/rss/');
                if (!$rss) return [];

                $items = [];
                foreach ($rss->channel->item as $item) {
                    $items[] = [
                        'title' => (string) $item->title,
                        'link' => (string) $item->link,
                        'pubDate' => (string) $item->pubDate,
                        'description' => strip_tags((string) $item->description)
                    ];
                }
                return array_slice($items, 0, 6);
            } catch (\Exception $e) {
                return [];
            }
        });

        // 3. Dynamic Category Data
        $categoryData = Cache::remember('cat_data_' . $category, 180, function () use ($category) {
            try {
                if ($category === 'degen') {
                    // Token Meme / Hot DEX Pairs via DexScreener
                    $res = Http::timeout(5)->get('https://api.dexscreener.com/latest/dex/search?q=pepe%20wif%20bonk')->json();
                    return array_slice($res['pairs'] ?? [], 0, 8);

                } elseif ($category === 'us-stock') {
                    // Crypto-related US Stocks via Yahoo Finance Public API
                    $symbols = 'COIN,MSTR,NVDA,AAPL';
                    $res = Http::timeout(5)->get("https://query1.finance.yahoo.com/v7/finance/quote?symbols={$symbols}")->json();
                    $result = [];

                    foreach ($res['quoteResponse']['result'] ?? [] as $stock) {
                        $result[] = [
                            'name' => $stock['shortName'] ?? $stock['symbol'],
                            'symbol' => $stock['symbol'],
                            'price' => $stock['regularMarketPrice'] ?? 0,
                            'change' => $stock['regularMarketChangePercent'] ?? 0,
                            'marketCap' => $stock['marketCap'] ?? 0,
                            'url' => "https://finance.yahoo.com/quote/" . $stock['symbol']
                        ];
                    }
                    return $result;

                } elseif ($category === 'hot-capital') {
                    // Top Volume Crypto via CoinGecko Markets
                    $res = Http::timeout(5)->get('https://api.coingecko.com/api/v3/coins/markets', [
                        'vs_currency' => 'usd',
                        'order' => 'volume_desc',
                        'per_page' => 8,
                        'page' => 1
                    ]);
                    return $res->successful() ? $res->json() : [];

                } else { // Onchain (Default)
                    // Layer-1 / Major Chain Coins via CoinGecko (Diverse Coins)
                    $res = Http::timeout(5)->get('https://api.coingecko.com/api/v3/coins/markets', [
                        'vs_currency' => 'usd',
                        'category' => 'layer-1',
                        'order' => 'market_cap_desc',
                        'per_page' => 8,
                        'page' => 1
                    ]);
                    return $res->successful() ? $res->json() : [];
                }
            } catch (\Exception $e) {
                return [];
            }
        });

        return view('home', compact('prices', 'news', 'categoryData', 'category'));
    }

    public function getPricesApi()
{
    $prices = Cache::remember('crypto_prices', 30, function () {
        return Http::get('https://api.coingecko.com/api/v3/simple/price', [
            'ids' => 'bitcoin,ethereum,solana,binancecoin',
            'vs_currencies' => 'usd',
            'include_24hr_change' => 'true'
        ])->json();
    });

    return response()->json($prices);
}
}