<div class="space-y-4">
    @if (!$notifications->isEmpty())
        @foreach ($notifications as $notification)
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start space-x-3">
                    @php
                        $notifType = $notification->data['type'] ?? null;
                    @endphp
                    @if($notifType == 'thread_approved')
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-green-800">Thread Disetujui</p>
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message'] }}</p>
                            </div>
                        @elseif($notifType == 'thread_rejected')
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-red-800">Thread Ditolak</p>
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message'] }}</p>
                                @if(isset($notification->data['rejection_reason']))
                                    <p class="text-xs text-red-600 mt-2 bg-red-50 px-2 py-1 rounded">
                                        Alasan: {{ $notification->data['rejection_reason'] }}
                                    </p>
                                @endif
                            </div>
                        @elseif($notifType == 'thread_liked')
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-red-800">Postingan Disukai</p>
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                @if(isset($notification->data['thread_slug']) && isset($notification->data['category_slug']) && !empty($notification->data['thread_slug']) && !empty($notification->data['category_slug']))
                                    <a href="{{ route('threads.show', [$notification->data['category_slug'], $notification->data['thread_slug']]) }}" class="text-blue-500 hover:text-blue-700 text-xs mt-2 inline-block relative z-10 pointer-events-auto font-medium">
                                        Lihat Postingan →
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs mt-2">Link tidak tersedia</span>
                                @endif
                            </div>
                        @elseif($notifType == 'new_reply')
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-blue-800">Balasan Baru</p>
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                @if(isset($notification->data['thread_slug']) && isset($notification->data['category_slug']) && !empty($notification->data['thread_slug']) && !empty($notification->data['category_slug']))
                                    <a href="{{ route('threads.show', [$notification->data['category_slug'], $notification->data['thread_slug']]) }}" class="text-blue-500 hover:text-blue-700 text-xs mt-2 inline-block relative z-10 pointer-events-auto font-medium">
                                        Lihat Balasan →
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs mt-2">Link tidak tersedia</span>
                                @endif
                            </div>
                        @elseif($notifType == 'user_followed')
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-purple-800">Pengikut Baru</p>
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                @if(isset($notification->data['user_id']))
                                    <a href="{{ route('profile', $notification->data['user_id'] ?? '') }}" class="text-blue-500 hover:text-blue-700 text-xs mt-2 inline-block relative z-10 pointer-events-auto">
                                        Lihat Profil →
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 0 0-15 0v5h5l-5 5-5-5h5V7a9 9 0 1 1 18 0v10z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-800">Notifikasi</p>
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </p>
                            </div>
                        @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-12">
            <div class="text-gray-500 text-lg">📭 Tidak ada notifikasi</div>
            <p class="text-gray-400 mt-2">Semua notifikasi sudah dibaca atau belum ada notifikasi baru.</p>
        </div>
    @endif
</div>
