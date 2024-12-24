<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Notification Settings</h2>

        @if (session('error'))
        <div class="p-4 mb-4 text-red-800 bg-red-50 rounded-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        @if (session('success'))
        <div class="p-4 mb-4 text-green-800 bg-green-50 rounded-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Telegram Setup Card -->
            <div class="p-6 bg-white rounded-lg shadow-sm border">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Telegram Notifications</h3>
                    @if($settings['telegram']['setup_status'] ?? '' === 'completed')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    @endif
                </div>

                <p class="text-gray-600 mb-4">
                    Receive notifications through our Telegram bot.
                </p>

                @if(($settings['telegram']['setup_status'] ?? '') === 'completed')
                <div class="text-sm text-gray-500">
                    Connected to @{{ $settings['telegram']['bot_username'] }}
                </div>
                @else
                <form action="{{ route('notification-settings.setup', ['service' => 'telegram']) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300"
                        @if($setupInProgress ?? false) disabled @endif>
                        @if($setupInProgress ?? false)
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        @endif
                        Connect Telegram
                    </button>
                </form>
                @endif
            </div>

            <!-- Slack Setup Card -->
            <div class="p-6 bg-white rounded-lg shadow-sm border">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Slack Notifications</h3>
                    @if($settings['slack']['setup_status'] ?? '' === 'completed')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    @endif
                </div>

                <p class="text-gray-600 mb-4">
                    Receive notifications in your Slack workspace.
                </p>

                @if(($settings['slack']['setup_status'] ?? '') === 'completed')
                <div class="text-sm text-gray-500">
                    Connected to workspace: {{ $settings['slack']['team_name'] }}
                </div>
                @else
                <form action="{{ route('notification-settings.setup', ['service' => 'slack']) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:ring-4 focus:ring-purple-300"
                        @if($setupInProgress ?? false) disabled @endif>
                        @if($setupInProgress ?? false)
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        @endif
                        Connect Slack
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const button = this.querySelector('button[type="submit"]');
                    button.disabled = true;
                    button.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Connecting...
                        `;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>