<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bantuan AI - SDN Susukan 08 Pagi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .chat-container {
            height: calc(100vh - 200px);
            min-height: 500px;
        }
        .message-ai {
            background-color: #f3f4f6;
        }
        .message-user {
            background-color: #dcfce7;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('landing') }}" class="flex items-center space-x-4 hover:opacity-90 transition">
                    <img src="{{ asset('img/logo.svg') }}" alt="SDN Susukan 08 Pagi" class="h-14 w-auto">
                    <span class="text-lg font-semibold hidden sm:inline">SDN Susukan 08 Pagi</span>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing') }}" class="hover:text-green-100 transition">Beranda</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Chat Area -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-gray-200 rounded-xl shadow-lg overflow-hidden chat-container flex flex-col">
            <!-- Messages Container -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messagesContainer">
                <!-- Initial AI Message -->
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="message-ai rounded-2xl px-4 py-3 max-w-md">
                        <p class="text-gray-800">Ada pertanyaan apa yang ingin kamu tanyakan ?</p>
                    </div>
                </div>
            </div>

            <!-- Input Field -->
            <div class="border-t border-gray-300 bg-white p-4">
                <form id="chatForm" class="flex items-center space-x-3">
                    @csrf
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="messageInput" 
                            name="message" 
                            placeholder="Ada kesulitan apa ?" 
                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            autocomplete="off"
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium"
                    >
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');

        // Auto scroll to bottom
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Add message to chat
        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex items-start space-x-3 ${isUser ? 'justify-end' : ''}`;
            
            const avatar = `
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full ${isUser ? 'bg-green-500 border-2 border-white' : 'bg-white border-2 border-gray-300'} flex items-center justify-center">
                        <svg class="w-6 h-6 ${isUser ? 'text-white' : 'text-gray-600'}" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                        </svg>
                    </div>
                </div>
            `;
            
            const bubble = `
                <div class="${isUser ? 'message-user' : 'message-ai'} rounded-2xl px-4 py-3 max-w-md">
                    <p class="text-gray-800">${message}</p>
                </div>
            `;
            
            if (isUser) {
                messageDiv.innerHTML = bubble + avatar;
            } else {
                messageDiv.innerHTML = avatar + bubble;
            }
            
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        // Handle form submission
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Add user message
            addMessage(message, true);
            messageInput.value = '';
            
            // Show loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'flex items-start space-x-3';
            loadingDiv.id = 'loadingMessage';
            loadingDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                        </svg>
                    </div>
                </div>
                <div class="message-ai rounded-2xl px-4 py-3 max-w-md">
                    <p class="text-gray-500 italic">Mengetik...</p>
                </div>
            `;
            messagesContainer.appendChild(loadingDiv);
            scrollToBottom();
            
            try {
                // Send message to server (you can implement AI API here)
                const response = await fetch('{{ route("ai.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: message })
                });
                
                const data = await response.json();
                
                // Remove loading message
                loadingDiv.remove();
                
                // Add AI response
                addMessage(data.response || 'Maaf, saya belum bisa menjawab pertanyaan tersebut. Silakan coba lagi nanti.');
            } catch (error) {
                // Remove loading message
                loadingDiv.remove();
                
                // Add error message
                addMessage('Maaf, terjadi kesalahan. Silakan coba lagi nanti.');
            }
        });

        // Initial scroll
        scrollToBottom();
    </script>
</body>
</html>
