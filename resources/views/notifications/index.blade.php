<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="section-heading">Pusat Notifikasi</p>
                <h2 class="text-2xl font-black text-white leading-tight">
                    Riwayat <span class="text-gradient">Notifikasi</span>
                </h2>
            </div>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white/5 border border-white/10 hover:bg-white/10 text-gray-300 text-xs font-bold rounded-xl transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-check-double text-brand-orange"></i>
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/20 text-green-400 px-5 py-4 rounded-2xl animate-fade-in">
                <i class="fa-solid fa-circle-check text-lg shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="card-dark rounded-2xl overflow-hidden border border-white/7">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between bg-premium-gray/30">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Daftar Notifikasi</span>
                <span class="text-xs text-brand-orange font-semibold">{{ auth()->user()->unreadNotifications->count() }} belum dibaca</span>
            </div>

            <div class="divide-y divide-white/5">
                @forelse($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $data = $notification->data;
                    @endphp
                    <div class="p-5 flex items-start gap-4 transition-colors {{ $isUnread ? 'bg-brand-orange/5 hover:bg-brand-orange/10' : 'hover:bg-white/3' }}">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $isUnread ? 'bg-brand-orange/20 border border-brand-orange/30' : 'bg-white/5 border border-white/10' }}">
                            <i class="fa-solid {{ $data['icon'] ?? 'fa-bell text-brand-orange' }} text-sm"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h4 class="font-bold text-sm text-white flex items-center gap-2">
                                    {{ $data['title'] ?? 'Notifikasi' }}
                                    @if($isUnread)
                                        <span class="w-2 h-2 rounded-full bg-brand-orange inline-block"></span>
                                    @endif
                                </h4>
                                <span class="text-[11px] text-gray-500 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-300 leading-relaxed mb-3">{{ $data['message'] ?? '' }}</p>

                            <div class="flex items-center gap-3">
                                @if(!empty($data['url']) || !empty($data['booking_id']))
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="text-xs font-bold text-brand-orange hover:underline inline-flex items-center gap-1">
                                        Lihat Detail <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                @endif

                                @if($isUnread)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-gray-500 hover:text-gray-300">
                                            Tandai sudah dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <div class="w-14 h-14 bg-premium-gray rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class="fa-regular fa-bell-slash text-gray-600 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada notifikasi.</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-white/5 bg-premium-gray/20">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

