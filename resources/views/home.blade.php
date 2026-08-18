<!DOCTYPE html>
<html lang="id" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cryptotype - Media & Crypto Analytics</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS & DaisyUI CDN Fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />

    <style>
        .ticker-wrapper {
            display: flex;
            overflow: hidden;
            user-select: none;
        }

        .ticker-content {
            display: flex;
            gap: 1.5rem;
            animation: marquee 30s linear infinite;
        }

        .ticker-content:hover {
            animation-play-state: paused;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Flash Effect saat harga berubah realtime */
        .price-up {
            color: #4ade80 !important;
            transition: color 0.3s ease;
        }
        .price-down {
            color: #f87171 !important;
            transition: color 0.3s ease;
        }
    </style>
</head>

<body class="bg-base-300 text-base-content min-h-screen font-sans">

    <!-- 1. HEADER / NAVBAR -->
    <header class="border-b border-base-100 bg-base-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}"
                class="text-2xl font-extrabold tracking-wider text-warning flex items-center gap-2 hover:opacity-80 transition">
                <span>⚡</span> Cryptotype
            </a>
            <nav class="flex gap-8 font-semibold text-sm">
                <a href="{{ route('home') }}" class="hover:text-warning transition">Home</a>
                <a href="#news" class="hover:text-warning transition">News</a>
                <a href="#research" class="hover:text-warning transition">Research</a>
                <a href="#sponsored" class="hover:text-warning transition">Sponsored</a>
            </nav>
        </div>
    </header>

    <!-- 2. RUNNING TICKER BAR (API CRYPTO) -->
    <div class="bg-black border-b border-base-100 py-2.5 px-4 overflow-hidden whitespace-nowrap">
        <div class="max-w-7xl mx-auto flex items-center gap-4 text-xs font-mono">
            <span class="bg-warning text-black px-2 py-0.5 font-bold rounded shrink-0">API CRYPTO</span>
            <div class="ticker-wrapper w-full">
                <div class="ticker-content">
                    @if (!empty($prices))
                        @foreach (array_merge($prices, $prices) as $coin => $val)
                            <div class="flex items-center gap-2 bg-base-200 px-3 py-1 rounded border border-base-100 shrink-0" data-coin="{{ strtolower($coin) }}">
                                <span class="uppercase font-bold text-gray-400">{{ $coin }}</span>
                                <span class="coin-price text-white font-semibold">${{ number_format($val['usd'] ?? 0, 2) }}</span>
                                <span class="coin-change {{ ($val['usd_24h_change'] ?? 0) >= 0 ? 'text-success' : 'text-error' }} font-bold">
                                    {{ ($val['usd_24h_change'] ?? 0) >= 0 ? '▲' : '▼' }}
                                    {{ number_format(abs($val['usd_24h_change'] ?? 0), 2) }}%
                                </span>
                            </div>
                        @endforeach
                    @else
                        <span class="text-gray-500">Memuat data pasar real-time...</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-7xl mx-auto px-6 py-6 space-y-8">

        <!-- 3. HERO / SPONSORED BANNER -->
        <section id="sponsored" class="w-full">
            <div
                class="w-full h-56 md:h-72 bg-gradient-to-r from-gray-900 via-base-200 to-gray-900 border-2 border-dashed border-base-100 rounded-2xl flex flex-col items-center justify-center text-center p-6 relative overflow-hidden group hover:border-warning transition">
                <span class="badge badge-warning badge-sm absolute top-4 left-4 font-bold">SPONSORED</span>
                <h2 class="text-2xl md:text-4xl font-extrabold text-white mb-2">PROMOTIONAL BANNER / FEATURED PROJECT
                </h2>
                <p class="text-gray-400 text-sm max-w-xl">Pasang iklan banner atau sorotan proyek crypto/Web3 terbaru
                    Anda di sini untuk menjangkau ribuan audiens.</p>
            </div>
        </section>

        <!-- 4. CATEGORY SUB-NAVIGATION GRID -->
        <section class="space-y-6">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap gap-3 border-b border-base-100 pb-4">
                @foreach (['onchain' => 'Onchain', 'us-stock' => 'US Stock', 'hot-capital' => 'Hot Capital', 'degen' => 'Degen'] as $key => $label)
                    <a href="?category={{ $key }}"
                        class="btn btn-sm md:btn-md {{ $category === $key ? 'btn-warning' : 'btn-outline btn-neutral' }} font-bold">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <!-- Content Display Area -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @if (!empty($categoryData))
                    @foreach ($categoryData as $item)
                        @php
                            $symbolKey = strtolower($item['symbol'] ?? ($item['baseToken']['symbol'] ?? ''));
                        @endphp
                        <div class="card bg-base-200 border border-base-100 hover:border-warning transition shadow-lg" data-coin="{{ $symbolKey }}">
                            <div class="card-body p-4 justify-between">

                                {{-- Render untuk US Stock --}}
                                @if ($category === 'us-stock')
                                    <div>
                                        <div class="flex justify-between items-start mb-2 gap-2">
                                            <h3 class="font-bold text-white text-base truncate">
                                                {{ $item['name'] ?? $item['symbol'] }}</h3>
                                            <span
                                                class="badge badge-sm badge-outline uppercase shrink-0">{{ $item['symbol'] }}</span>
                                        </div>
                                        <div class="text-xl font-mono font-extrabold text-warning coin-price">
                                            ${{ number_format($item['price'] ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-xs text-gray-400 flex justify-between mt-3 pt-2 border-t border-base-100">
                                        <span
                                            class="coin-change {{ ($item['change'] ?? 0) >= 0 ? 'text-success' : 'text-error' }} font-semibold">
                                            {{ ($item['change'] ?? 0) >= 0 ? '▲' : '▼' }}
                                            {{ number_format(abs($item['change'] ?? 0), 2) }}%
                                        </span>
                                        <a href="{{ $item['url'] ?? '#' }}" target="_blank"
                                            class="text-warning hover:underline">Details ↗</a>
                                    </div>

                                {{-- Render untuk Onchain & Hot Capital (CoinGecko Markets) --}}
                                @elseif($category === 'onchain' || $category === 'hot-capital')
                                    <div>
                                        <div class="flex justify-between items-start mb-2 gap-2">
                                            <h3 class="font-bold text-white text-base truncate flex items-center gap-2">
                                                @if (!empty($item['image']))
                                                    <img src="{{ $item['image'] }}" class="w-5 h-5 rounded-full"
                                                        alt="{{ $item['name'] }}">
                                                @endif
                                                {{ $item['name'] ?? 'Token' }}
                                            </h3>
                                            <span
                                                class="badge badge-sm badge-ghost uppercase shrink-0">{{ $item['symbol'] ?? 'CRYPTO' }}</span>
                                        </div>
                                        <div class="text-xl font-mono font-extrabold text-warning coin-price">
                                            ${{ number_format($item['current_price'] ?? 0, 2) }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-xs text-gray-400 flex justify-between mt-3 pt-2 border-t border-base-100">
                                        <span
                                            class="coin-change {{ ($item['price_change_percentage_24h'] ?? 0) >= 0 ? 'text-success' : 'text-error' }} font-semibold">
                                            {{ ($item['price_change_percentage_24h'] ?? 0) >= 0 ? '▲' : '▼' }}
                                            {{ number_format(abs($item['price_change_percentage_24h'] ?? 0), 2) }}%
                                        </span>
                                        <span>Vol:
                                            ${{ number_format(($item['total_volume'] ?? 0) / 1000000, 1) }}M</span>
                                    </div>

                                {{-- Render untuk Degen (DexScreener Pairs) --}}
                                @else
                                    <div>
                                        <div class="flex justify-between items-start mb-2 gap-2">
                                            <h3 class="font-bold text-white text-base truncate">
                                                {{ $item['baseToken']['name'] ?? ($item['baseToken']['symbol'] ?? 'Token') }}
                                            </h3>
                                            <span
                                                class="badge badge-sm badge-ghost uppercase shrink-0">{{ $item['chainId'] ?? 'DEX' }}</span>
                                        </div>
                                        <div class="text-xl font-mono font-extrabold text-warning coin-price">
                                            ${{ number_format((float) ($item['priceUsd'] ?? 0), 4) }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-xs text-gray-400 flex justify-between mt-3 pt-2 border-t border-base-100">
                                        <span>Vol 24h:
                                            ${{ number_format((float) ($item['volume']['h24'] ?? 0)) }}</span>
                                        <a href="{{ $item['url'] ?? '#' }}" target="_blank"
                                            class="text-warning hover:underline">Chart ↗</a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                @else
                    <div
                        class="col-span-full bg-base-200 p-8 text-center rounded-xl text-gray-400 border border-base-100">
                        Tidak dapat memuat data untuk kategori <span
                            class="text-warning font-bold uppercase">{{ $category }}</span>. Silakan coba beberapa
                        saat lagi.
                    </div>
                @endif
            </div>
        </section>

        <!-- 5. NEWS SECTION (COINDESK RSS) -->
        <section id="news" class="pt-6 border-t border-base-100">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                <span>📰</span> Latest News (CoinDesk Feed)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if (!empty($news))
                    @foreach ($news as $item)
                        <div class="card bg-base-200 border border-base-100 hover:border-warning transition shadow-md">
                            <div class="card-body p-5 justify-between">
                                <div>
                                    <span
                                        class="text-xs text-gray-500 font-mono">{{ date('d M Y, H:i', strtotime($item['pubDate'])) }}</span>
                                    <h3
                                        class="font-bold text-white text-base line-clamp-2 my-2 hover:text-warning transition">
                                        <a href="{{ $item['link'] }}" target="_blank">{{ $item['title'] }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-400 line-clamp-3 leading-relaxed">
                                        {{ $item['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full bg-base-200 p-6 text-center text-gray-400 rounded-xl">
                        Tidak ada berita terbaru yang dapat ditampilkan.
                    </div>
                @endif
            </div>
        </section>

    </main>

    <footer class="footer footer-center p-6 bg-base-200 text-base-content border-t border-base-100 mt-12 text-xs">
        <aside>
            <p>© {{ date('Y') }} Cryptotype - Built for speed & real-time analytics.</p>
        </aside>
    </footer>

    <!-- REAL-TIME WEBSOCKET STREAMING ENGINE (BINANCE PUBLIC API) -->
    <script>
        const symbols = ['btcusdt', 'ethusdt', 'solusdt', 'bnbusdt', 'xrpusdt', 'trxusdt', 'zecusdt'];
        const streamUrl = `wss://stream.binance.com:9443/ws/${symbols.map(s => s + '@ticker').join('/')}`;

        const ws = new WebSocket(streamUrl);

        // Map simbol Binance ke ID koin lokal
        const symbolToKeys = {
            'BTCUSDT': ['btc', 'bitcoin'],
            'ETHUSDT': ['eth', 'ethereum'],
            'SOLUSDT': ['sol', 'solana'],
            'BNBUSDT': ['bnb', 'binancecoin'],
            'XRPUSDT': ['xrp'],
            'TRXUSDT': ['trx'],
            'ZECUSDT': ['zec']
        };

        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            const targetKeys = symbolToKeys[data.s];

            if (targetKeys) {
                const rawPrice = parseFloat(data.c);
                const priceFormatted = rawPrice.toLocaleString('en-US', {
                    minimumFractionDigits: rawPrice < 1 ? 4 : 2,
                    maximumFractionDigits: rawPrice < 1 ? 4 : 2
                });
                const changeVal = parseFloat(data.P);
                const isPositive = changeVal >= 0;

                targetKeys.forEach(key => {
                    const elements = document.querySelectorAll(`[data-coin="${key}"]`);
                    elements.forEach(el => {
                        // Update Harga (USD)
                        const priceEl = el.querySelector('.coin-price');
                        if (priceEl) {
                            priceEl.innerText = `$${priceFormatted}`;
                        }

                        // Update Perubahan Persentase 24h
                        const changeEl = el.querySelector('.coin-change');
                        if (changeEl) {
                            changeEl.className = `coin-change font-bold ${isPositive ? 'text-success' : 'text-error'}`;
                            changeEl.innerText = `${isPositive ? '▲' : '▼'} ${Math.abs(changeVal).toFixed(2)}%`;
                        }
                    });
                });
            }
        };

        ws.onerror = (err) => console.error("WebSocket Error:", err);
    </script>

</body>

</html>