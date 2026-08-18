<!DOCTYPE html>
<html lang="id" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoLyfe - Real-time Onchain & Market Data</title>

    {{-- Tailwind CSS & Font --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        neon: '#a3e635', // Warna hijau neon CryptoLyfe
                        darkbg: '#050811',
                        cardbg: '#0b0f19',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #050811;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Glassmorphism & Neon Glow Effects */
        .neon-card {
            background: linear-gradient(145deg, #0d1322, #070a14);
            border: 1px solid rgba(163, 230, 53, 0.15);
            transition: all 0.3s ease;
        }

        .neon-card:hover {
            border-color: rgba(163, 230, 53, 0.4);
            box-shadow: 0 0 20px rgba(163, 230, 53, 0.1);
        }

        .neon-border-glow {
            border: 1px solid #a3e635;
            box-shadow: 0 0 15px rgba(163, 230, 53, 0.25);
        }

        .neon-text-glow {
            text-shadow: 0 0 10px rgba(163, 230, 53, 0.5);
        }

        /* Running Ticker Marquee */
        .ticker-wrapper { display: flex; overflow: hidden; user-select: none; }
        .ticker-content { display: flex; gap: 2rem; animation: marquee 35s linear infinite; }
        .ticker-content:hover { animation-play-state: paused; }
        @keyframes marquee { 0% { transform: translateX(0%); } 100% { transform: translateX(-50%); } }
    </style>
</head>

<body class="bg-darkbg text-gray-100 min-h-screen pb-12">

    <!-- 1. NAVBAR HEADER -->
    <header class="border-b border-gray-800/60 bg-darkbg/80 backdrop-blur-md sticky top-0 z-50 px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tight text-white flex items-center gap-1">
                Crypto<span class="text-neon neon-text-glow">Lyfe</span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-300">
                <a href="?category=onchain" class="{{ ($category ?? 'onchain') === 'onchain' ? 'text-neon border-b-2 border-neon pb-1' : 'hover:text-white transition' }}">Onchain</a>
                <a href="?category=us-stock" class="{{ ($category ?? '') === 'us-stock' ? 'text-neon border-b-2 border-neon pb-1' : 'hover:text-white transition' }}">US Stock</a>
                <a href="?category=degen" class="{{ ($category ?? '') === 'degen' ? 'text-neon border-b-2 border-neon pb-1' : 'hover:text-white transition' }}">Degen</a>
                <a href="?category=hot-capital" class="{{ ($category ?? '') === 'hot-capital' ? 'text-neon border-b-2 border-neon pb-1' : 'hover:text-white transition' }}">Hot Capital</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <button class="btn btn-ghost btn-circle btn-sm text-gray-300 hover:text-neon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <button class="btn btn-ghost btn-circle btn-sm text-gray-300 hover:text-neon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
                <button class="btn btn-sm bg-neon text-black hover:bg-lime-400 font-bold border-none rounded-xl px-5">
                    Connect Wallet
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN CONTAINER -->
    <main class="max-w-6xl mx-auto px-4 mt-6 space-y-8">

        <!-- 2. HERO SLIDER BANNER -->
        <section class="relative rounded-3xl overflow-hidden neon-card p-8 md:p-12 min-h-[280px] flex items-center justify-between bg-gradient-to-r from-gray-950 via-slate-900 to-emerald-950">
            <div class="space-y-4 max-w-lg z-10">
                <div class="flex items-center gap-3">
                    <span class="text-4xl font-extrabold text-neon neon-text-glow">+13.71%</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
                    OKX Web3
                </h1>
                <p class="text-gray-400 text-sm font-medium">Your Gateway to Onchain Future</p>
                
                <div class="flex items-center gap-6 text-xs text-gray-400 pt-2 font-mono">
                    <div>Research Date : <span class="text-white font-semibold">29 July 2026</span></div>
                    <div>Entry Price : <span class="text-white font-semibold">197 USD</span></div>
                </div>
            </div>

            <!-- Slide Navigation Buttons -->
            <button class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 border border-gray-700 flex items-center justify-center hover:border-neon text-gray-300 hover:text-neon transition">
                ❮
            </button>
            <button class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 border border-gray-700 flex items-center justify-center hover:border-neon text-gray-300 hover:text-neon transition">
                ❯
            </button>
        </section>

        <!-- 3. CATEGORY SELECTOR BUTTONS -->
        <section class="flex justify-center gap-3">
            <a href="?category=onchain" class="btn btn-sm md:btn-md rounded-2xl px-6 font-bold {{ ($category ?? 'onchain') === 'onchain' ? 'bg-neon text-black hover:bg-lime-400 border-none neon-border-glow' : 'bg-gray-900 text-gray-300 border-gray-800 hover:border-neon' }}">
                ⚡ Crypto
            </a>
            <a href="?category=us-stock" class="btn btn-sm md:btn-md rounded-2xl px-6 font-bold {{ ($category ?? '') === 'us-stock' ? 'bg-neon text-black hover:bg-lime-400 border-none neon-border-glow' : 'bg-gray-900 text-gray-300 border-gray-800 hover:border-neon' }}">
                📈 Stock
            </a>
            <button class="btn btn-sm md:btn-md rounded-2xl px-6 font-bold bg-gray-900/50 text-gray-500 border-gray-800/80 cursor-not-allowed">
                🚀 Coming Soon
            </button>
        </section>

        <!-- 4. SUB-CATEGORIES GRID WITH PERFORMANCE INDICATORS -->
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="neon-card p-5 rounded-2xl flex flex-col justify-between space-y-3 group hover:scale-[1.02] transition">
                <div class="flex justify-between items-center text-neon">
                    <span class="text-2xl">⬢</span>
                    <span class="text-xs font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-800 px-2 py-0.5 rounded-md">+13.71% ▲</span>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Onchain</h3>
                    <p class="text-xs text-gray-400 mt-1">Real-time DEX & DeFi Analytics</p>
                </div>
            </div>

            <div class="neon-card p-5 rounded-2xl flex flex-col justify-between space-y-3 group hover:scale-[1.02] transition">
                <div class="flex justify-between items-center text-neon">
                    <span class="text-2xl">📊</span>
                    <span class="text-xs font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-800 px-2 py-0.5 rounded-md">+2.48% ▲</span>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">US Stock</h3>
                    <p class="text-xs text-gray-400 mt-1">Tech & Global Indices</p>
                </div>
            </div>

            <div class="neon-card p-5 rounded-2xl flex flex-col justify-between space-y-3 group hover:scale-[1.02] transition">
                <div class="flex justify-between items-center text-neon">
                    <span class="text-2xl">🔥</span>
                    <span class="text-xs font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-800 px-2 py-0.5 rounded-md">+11.76% ▲</span>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Hot Capital</h3>
                    <p class="text-xs text-gray-400 mt-1">Institutional Inflows</p>
                </div>
            </div>

            <div class="neon-card p-5 rounded-2xl flex flex-col justify-between space-y-3 group hover:scale-[1.02] transition">
                <div class="flex justify-between items-center text-neon">
                    <span class="text-2xl">💎</span>
                    <span class="text-xs font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-800 px-2 py-0.5 rounded-md">+7.32% ▲</span>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Degen</h3>
                    <p class="text-xs text-gray-400 mt-1">Meme & High Risk Gems</p>
                </div>
            </div>
        </section>

        <!-- 5. API PROMOTION CARD -->
        <section class="neon-card rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 bg-gradient-to-r from-gray-950 via-slate-900 to-black">
            <div class="flex items-center gap-6">
                <div class="text-4xl md:text-5xl font-black text-neon tracking-wider neon-text-glow">
                    API
                </div>
                <div class="border-l border-gray-800 pl-6">
                    <h2 class="text-2xl font-bold text-white">CryptoLyfe API</h2>
                    <p class="text-sm text-gray-400 mt-1">Real-time onchain & market data for builders</p>
                </div>
            </div>
            <button class="btn btn-outline border-neon text-neon hover:bg-neon hover:text-black font-bold rounded-xl px-6">
                Get API ➔
            </button>
        </section>

        <!-- 6. RESEARCH SECTION -->
        <section class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-5 bg-neon rounded-full inline-block"></span> Research
                </h2>
                <a href="#" class="text-xs font-bold text-neon hover:underline">View All ➔</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="neon-card rounded-2xl p-6 md:col-span-2 bg-gradient-to-br from-blue-950/40 via-slate-900 to-black space-y-4">
                    <div class="flex gap-2">
                        <span class="badge bg-neon text-black font-bold border-none">Spot</span>
                        <span class="badge bg-gray-800 text-gray-300 font-bold border-none">SMU</span>
                    </div>
                    <div class="text-4xl font-black text-neon neon-text-glow">+11.37%</div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Micron (MU) Strong Momentum Continues</h3>
                        <p class="text-xs text-gray-400 mt-1">AI demand fuels memory chip growth outlook.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="neon-card p-5 rounded-2xl">
                        <span class="badge bg-neon text-black font-bold border-none text-[10px] mb-2">WEB3</span>
                        <h4 class="font-bold text-white text-sm">OKX Web3 Ecosystem Expansion Accelerates</h4>
                    </div>
                    <div class="neon-card p-5 rounded-2xl">
                        <span class="badge bg-blue-600 text-white font-bold border-none text-[10px] mb-2">Market</span>
                        <h4 class="font-bold text-white text-sm">Global Tech Stocks Rebound on AI Optimism</h4>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. REAL-TIME TICKER RUNNING BAR -->
        <section class="bg-black/80 border border-gray-800 rounded-2xl py-3 px-4 overflow-hidden">
            <div class="ticker-wrapper">
                <div class="ticker-content text-xs font-mono">
                    @if (!empty($prices))
                        @foreach (array_merge($prices, $prices) as $coin => $val)
                            <div class="flex items-center gap-2" data-coin="{{ strtolower($coin) }}">
                                <span class="uppercase font-bold text-gray-400">{{ $coin }}</span>
                                <span class="coin-price text-white font-semibold">${{ number_format($val['usd'] ?? 0, 2) }}</span>
                                <span class="coin-change {{ ($val['usd_24h_change'] ?? 0) >= 0 ? 'text-neon' : 'text-red-500' }} font-bold">
                                    {{ ($val['usd_24h_change'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($val['usd_24h_change'] ?? 0, 2) }}%
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        <!-- 8. NEWSLETTER SUBSCRIBE BANNER -->
        <section class="neon-card neon-border-glow rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 bg-gradient-to-r from-gray-950 via-slate-900 to-black">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-neon/10 border border-neon/30 flex items-center justify-center text-neon text-3xl shrink-0">
                    ✉
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-white">Stay Ahead. Join CryptoLyfe.</h2>
                    <p class="text-xs text-gray-400 mt-1">Latest crypto news, market updates & research — straight to your inbox.</p>
                </div>
            </div>

            <div class="flex w-full md:w-auto items-center gap-2">
                <input type="email" placeholder="Your email address" class="input input-bordered bg-gray-950 border-gray-800 text-sm text-white focus:border-neon w-full md:w-64 rounded-xl" />
                <button class="btn bg-neon text-black hover:bg-lime-400 font-bold border-none rounded-xl px-6">
                    Subscribe
                </button>
            </div>
        </section>

    </main>

    <!-- LIVE TICKER FLUTTER ANIMATION -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setInterval(() => {
                const priceElements = document.querySelectorAll('.coin-price');
                const randomIndex = Math.floor(Math.random() * priceElements.length);
                const el = priceElements[randomIndex];

                if (el) {
                    const currentPriceText = el.innerText.replace(/[^0-9.-]+/g, "");
                    let currentPrice = parseFloat(currentPriceText);

                    if (!isNaN(currentPrice) && currentPrice > 0) {
                        const percentageChange = (Math.random() * 0.3 - 0.15) / 100;
                        const newPrice = currentPrice * (1 + percentageChange);

                        const formattedPrice = newPrice.toLocaleString('en-US', {
                            minimumFractionDigits: newPrice < 1 ? 4 : 2,
                            maximumFractionDigits: newPrice < 1 ? 4 : 2
                        });

                        const isUp = newPrice >= currentPrice;
                        el.classList.add(isUp ? 'text-neon' : 'text-red-500');
                        el.innerText = `$${formattedPrice}`;

                        setTimeout(() => {
                            el.classList.remove('text-neon', 'text-red-500');
                        }, 500);
                    }
                }
            }, 1200);
        });
    </script>

</body>

</html>