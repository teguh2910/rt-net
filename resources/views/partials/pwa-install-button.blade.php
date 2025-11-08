<!-- PWA Install Button -->
<button id="install-pwa-button" style="display: none;" 
    class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2 z-50 animate-bounce">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
    </svg>
    <span class="font-semibold">Install Aplikasi</span>
</button>

<!-- iOS Install Instructions -->
<div id="ios-install-prompt" style="display: none;" 
    class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-2xl p-6 z-50 border-t-4 border-blue-500">
    <div class="max-w-lg mx-auto">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Install RT-Net di iPhone/iPad</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                    Untuk install aplikasi ini di perangkat iOS Anda:
                </p>
                <ol class="text-sm text-gray-600 dark:text-gray-300 space-y-2 list-decimal list-inside">
                    <li>Tap tombol <strong>Share</strong> 
                        <svg class="inline w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                        </svg>
                        di bawah
                    </li>
                    <li>Scroll ke bawah dan pilih <strong>"Add to Home Screen"</strong></li>
                    <li>Tap <strong>Add</strong> untuk menginstall</li>
                </ol>
            </div>
            <button onclick="document.getElementById('ios-install-prompt').style.display='none'" 
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- PWA Running Indicator -->
<div class="pwa-indicator hidden" style="position: fixed; bottom: 20px; left: 20px; background: rgba(34, 197, 94, 0.9); color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; z-index: 9999;">
    <i class="fas fa-mobile-alt mr-2"></i>
    Running as App
</div>

<style>
    body.pwa-mode .pwa-indicator {
        display: block;
    }
</style>
