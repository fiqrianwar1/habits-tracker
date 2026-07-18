<div>
    <!-- Chat Toggle Button -->
    <button 
        wire:click="toggleChat"
        class="fixed bottom-6 right-6 w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full shadow-xl shadow-indigo-500/30 flex items-center justify-center text-white hover:scale-110 transition-transform z-50 focus:outline-none"
    >
        @if($isOpen)
            <!-- Close Icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        @else
            <!-- Chat Icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        @endif
    </button>

    <!-- Chat Window -->
    @if($isOpen)
        <div class="fixed bottom-24 right-6 w-80 md:w-96 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-2xl rounded-2xl overflow-hidden z-50 flex flex-col transition-all duration-300 transform origin-bottom-right" style="height: 500px; max-height: calc(100vh - 120px);">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white flex justify-between items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                <div class="relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm shadow-inner">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">Habits AI</h3>
                        <p class="text-[10px] text-indigo-100 opacity-80 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Online
                        </p>
                    </div>
                </div>
                <button wire:click="toggleChat" class="relative z-10 text-white/70 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-800/50 space-y-4 relative scroll-smooth"
                 x-data
                 x-init="$wire.on('chat-scrolled', () => { setTimeout(() => { $el.scrollTop = $el.scrollHeight }, 100); });"
                 wire:poll.keep-alive.500ms="getBotResponse"
            >
                @foreach($messages as $msg)
                    @if($msg['role'] === 'assistant')
                        <!-- Bot Message -->
                        <div class="flex items-end gap-2 max-w-[85%]">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="px-4 py-2 bg-white dark:bg-gray-700 rounded-2xl rounded-bl-sm shadow-sm border border-gray-100 dark:border-gray-600 text-sm text-gray-800 dark:text-gray-200">
                                {!! \Illuminate\Support\Str::markdown($msg['content']) !!}
                            </div>
                        </div>
                    @else
                        <!-- User Message -->
                        <div class="flex items-end justify-end gap-2 max-w-[85%] ml-auto">
                            <div class="px-4 py-2 bg-indigo-500 text-white rounded-2xl rounded-br-sm shadow-sm text-sm">
                                {{ $msg['content'] }}
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Typing Indicator -->
                @if($isTyping)
                    <div class="flex items-end gap-2 max-w-[85%]">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="px-4 py-3 bg-white dark:bg-gray-700 rounded-2xl rounded-bl-sm shadow-sm border border-gray-100 dark:border-gray-600 flex gap-1 items-center h-10">
                            <div class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Input Area -->
            <div class="p-3 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                <form wire:submit="sendMessage" class="flex items-center gap-2 relative">
                    <input 
                        wire:model="userInput" 
                        type="text" 
                        placeholder="Tanya tentang plan atau progres..." 
                        class="w-full pl-4 pr-12 py-2.5 bg-gray-100 dark:bg-gray-800 border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-gray-900 focus:ring-0 rounded-full text-sm dark:text-white transition-all"
                        @if($isTyping) disabled @endif
                    >
                    <button 
                        type="submit" 
                        class="absolute right-1 top-1 bottom-1 w-8 h-8 flex items-center justify-center bg-indigo-500 text-white rounded-full hover:bg-indigo-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        @if($isTyping) disabled @endif
                    >
                        <svg class="w-4 h-4 translate-x-px -translate-y-px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
                <div class="text-center mt-2">
                    <span class="text-[10px] text-gray-400 dark:text-gray-500">Powered by Groq AI</span>
                </div>
            </div>
        </div>
    @endif
    
    <style>
        /* Custom Scrollbar for Chat */
        #chat-messages::-webkit-scrollbar {
            width: 4px;
        }
        #chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        #chat-messages::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 4px;
        }
    </style>
</div>
