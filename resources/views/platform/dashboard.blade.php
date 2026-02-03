<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Dashboard - Pay Resayil.io</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', sans-serif; }
        body {
            background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .gradient-text {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-card {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
            border: 1px solid rgba(168, 85, 247, 0.2);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(168, 85, 247, 0.4);
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.1);
        }
        .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }
    </style>
</head>
<body class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-3xl font-bold gradient-text mb-1">Platform Dashboard</h1>
                <p class="text-gray-400">Welcome back, <span class="text-purple-400 font-medium">{{ $user->full_name ?? 'Soud' }}</span></p>
            </div>
            <div class="flex items-center gap-4">
                <div class="glass-card rounded-xl px-4 py-2">
                    <span class="text-gray-400 text-sm">{{ now()->timezone('Asia/Kuwait')->format('l, F j, Y') }}</span>
                </div>
                <form method="POST" action="{{ route('platform.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn px-4 py-2 rounded-xl text-red-400 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Total Agencies -->
            <div class="stat-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="text-green-400 text-sm font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        Active
                    </span>
                </div>
                <p class="text-4xl font-bold text-white mb-1">{{ $stats['agencies'] ?? 0 }}</p>
                <p class="text-gray-400 text-sm">Total Agencies</p>
            </div>

            <!-- Total Users -->
            <div class="stat-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="text-blue-400 text-sm font-medium">All Roles</span>
                </div>
                <p class="text-4xl font-bold text-white mb-1">{{ $stats['users'] ?? 0 }}</p>
                <p class="text-gray-400 text-sm">Total Users</p>
            </div>

            <!-- Total Payments -->
            <div class="stat-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-orange-400 text-sm font-medium">All Time</span>
                </div>
                <p class="text-4xl font-bold text-white mb-1">{{ $stats['payments'] ?? 0 }}</p>
                <p class="text-gray-400 text-sm">Total Payments</p>
            </div>

            <!-- Revenue -->
            <div class="stat-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-purple-400 text-sm font-medium">KWD</span>
                </div>
                <p class="text-4xl font-bold text-white mb-1">{{ number_format($stats['revenue'] ?? 0, 3) }}</p>
                <p class="text-gray-400 text-sm">Total Revenue</p>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quick Actions -->
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Quick Actions
                </h2>
                <div class="space-y-3">
                    <a href="{{ route('agencies.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition group">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center group-hover:bg-purple-500/30 transition">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Manage Agencies</p>
                            <p class="text-gray-500 text-sm">View and edit all agencies</p>
                        </div>
                    </a>
                    <a href="{{ route('payments.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition group">
                        <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center group-hover:bg-green-500/30 transition">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">View Payments</p>
                            <p class="text-gray-500 text-sm">Monitor all transactions</p>
                        </div>
                    </a>
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition group">
                        <div class="w-10 h-10 rounded-lg bg-pink-500/20 flex items-center justify-center group-hover:bg-pink-500/30 transition">
                            <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Platform Settings</p>
                            <p class="text-gray-500 text-sm">Configure system options</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Agencies -->
            <div class="glass-card rounded-2xl p-6 lg:col-span-2">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Recent Agencies
                </h2>
                @if(isset($recentAgencies) && $recentAgencies->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-400 text-sm border-b border-white/10">
                                    <th class="pb-3 font-medium">Agency</th>
                                    <th class="pb-3 font-medium">IATA</th>
                                    <th class="pb-3 font-medium">Users</th>
                                    <th class="pb-3 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($recentAgencies as $agency)
                                <tr class="text-sm">
                                    <td class="py-3">
                                        <span class="text-white font-medium">{{ $agency->agency_name }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-gray-400">{{ $agency->iata_number }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-gray-400">{{ $agency->users_count ?? 0 }}</span>
                                    </td>
                                    <td class="py-3">
                                        @if($agency->is_active)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <p class="text-gray-400">No agencies registered yet</p>
                        <a href="{{ route('agencies.create') }}" class="inline-flex items-center gap-2 mt-3 text-purple-400 hover:text-purple-300 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create First Agency
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-10 text-gray-500 text-sm">
            <p>Pay Resayil.io Platform Dashboard &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
