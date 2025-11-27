@extends('layouts.app')

@section('title', 'Chat with ' . $user->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 h-[calc(100vh-8rem)]">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-800 flex flex-col h-full">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between bg-gradient-to-r from-purple-50 to-blue-50 dark:from-gray-800 dark:to-gray-900">
            <div class="flex items-center space-x-3">
                <a href="{{ route('messages.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition" title="Back to messages">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="flex items-center space-x-3 hover:opacity-80 transition">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                             class="w-12 h-12 rounded-full ring-2 ring-purple-400">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center text-white font-bold ring-2 ring-purple-400">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white flex items-center">
                            {{ $user->name }}
                            @if($user->username)
                                <span class="material-symbols-outlined text-blue-500 text-sm ml-1" title="Verified">verified</span>
                            @endif
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                            Active now
                        </p>
                    </div>
                </a>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="p-2 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-gray-700 rounded-full transition" title="View profile">
                    <span class="material-symbols-outlined">account_circle</span>
                </a>
                <button type="button" class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition" title="More options">
                    <span class="material-symbols-outlined">more_vert</span>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-4" style="scroll-behavior: smooth;">
            <!-- Typing Indicator -->
            <div id="typing-indicator" class="hidden">
                <div class="flex justify-start">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl px-4 py-3 shadow-sm">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            @foreach($messages as $message)
                <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] {{ $message->sender_id === Auth::id() ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white' }} rounded-2xl p-3 shadow-sm">
                        @if($message->file_path)
                            <div class="mb-2">
                                @if($message->file_type === 'image')
                                    <img src="{{ asset('storage/' . $message->file_path) }}" alt="Image" class="rounded-lg max-w-xs cursor-pointer" onclick="window.open(this.src, '_blank')">
                                @elseif($message->file_type === 'video')
                                    <video controls class="rounded-lg max-w-xs">
                                        <source src="{{ asset('storage/' . $message->file_path) }}" type="video/mp4">
                                    </video>
                                @elseif($message->file_type === 'pdf')
                                    <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" class="flex items-center space-x-2 {{ $message->sender_id === Auth::id() ? 'text-white' : 'text-purple-600 dark:text-purple-400' }} hover:underline">
                                        <span class="material-symbols-outlined">description</span>
                                        <span>View PDF</span>
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" class="flex items-center space-x-2 {{ $message->sender_id === Auth::id() ? 'text-white' : 'text-purple-600 dark:text-purple-400' }} hover:underline">
                                        <span class="material-symbols-outlined">attach_file</span>
                                        <span>Download File</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                        @if($message->message)
                            <p class="break-words whitespace-pre-wrap">{{ $message->message }}</p>
                        @endif
                        <p class="text-xs mt-1 {{ $message->sender_id === Auth::id() ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ $message->created_at->format('g:i A') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            @if(!$canSendMessage)
                <div class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-700 rounded-xl p-4 mb-4">
                    <div class="flex items-start space-x-3">
                        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">info</span>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">Message Limit Reached</p>
                            <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                                You've sent {{ $messagesRemaining === 0 ? '2' : '' }} message(s). Follow {{ $user->name }} to send unlimited messages.
                            </p>
                            <form action="{{ route('users.follow', $user->username ?? $user->id) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-semibold flex items-center">
                                    <span class="material-symbols-outlined text-sm mr-1">person_add</span>
                                    Follow {{ $user->name }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                @if(!$isFollowing && $messagesRemaining !== null)
                    <div class="bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-700 rounded-xl p-3 mb-4">
                        <p class="text-sm text-blue-800 dark:text-blue-300 flex items-center">
                            <span class="material-symbols-outlined text-sm mr-2">info</span>
                            You have {{ $messagesRemaining }} message(s) remaining. Follow to send unlimited messages.
                        </p>
                    </div>
                @endif

                <form action="{{ route('messages.store', $user) }}" method="POST" enctype="multipart/form-data" id="message-form" class="space-y-3">
                    @csrf
                    
                    <!-- File Preview -->
                    <div id="file-preview" class="hidden bg-gray-100 dark:bg-gray-800 rounded-lg p-3"></div>

                    <div class="flex items-end space-x-2">
                        <!-- Emoji Button -->
                        <button type="button" class="p-3 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition flex-shrink-0" title="Coming soon">
                            <span class="material-symbols-outlined">sentiment_satisfied</span>
                        </button>

                        <!-- File Upload Button -->
                        <input type="file" id="file-input" name="file" accept="image/*,video/*,.pdf,.doc,.docx,.txt" class="hidden" onchange="previewFile(this)">
                        <button type="button" onclick="document.getElementById('file-input').click()" class="p-3 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition flex-shrink-0" title="Attach file">
                            <span class="material-symbols-outlined">attach_file</span>
                        </button>

                        <!-- Camera Button -->
                        <button type="button" onclick="document.getElementById('file-input').accept='image/*,video/*'; document.getElementById('file-input').click();" class="p-3 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition flex-shrink-0" title="Photo & Video">
                            <span class="material-symbols-outlined">photo_camera</span>
                        </button>

                        <!-- Message Input -->
                        <textarea name="message" id="message-input" rows="1" placeholder="Type a message..." 
                                  class="flex-1 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-3 rounded-xl border-0 focus:ring-2 focus:ring-purple-500 resize-none"
                                  onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); document.getElementById('message-form').submit(); }"></textarea>

                        <!-- Send Button -->
                        <button type="submit" class="p-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl hover:from-purple-700 hover:to-blue-700 transition flex-shrink-0 shadow-lg" title="Send message">
                            <span class="material-symbols-outlined">send</span>
                        </button>
                    </div>
                </form>
            @endif

            @if(session('error'))
                <div class="mt-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    let lastMessageId = {{ $messages->last()->id ?? 0 }};
    let isScrolledToBottom = true;

    // Scroll to bottom on page load
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messages-container');
        scrollToBottom();
        
        // Check if user is scrolled to bottom
        container.addEventListener('scroll', function() {
            const threshold = 50;
            isScrolledToBottom = container.scrollHeight - container.scrollTop - container.clientHeight < threshold;
        });

        // Auto-refresh messages every 2 seconds
        setInterval(fetchNewMessages, 2000);
    });

    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    }

    // Fetch new messages via AJAX
    function fetchNewMessages() {
        fetch(`{{ route('messages.fetch', $user) }}?last_id=${lastMessageId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                const container = document.getElementById('messages-container');
                
                data.messages.forEach(message => {
                    appendMessage(message);
                    lastMessageId = message.id;
                });

                // Only auto-scroll if user was already at bottom
                if (isScrolledToBottom) {
                    scrollToBottom();
                }
            }
        })
        .catch(error => console.error('Error fetching messages:', error));
    }

    function appendMessage(message) {
        const container = document.getElementById('messages-container');
        const isOwnMessage = message.sender_id === {{ Auth::id() }};
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;
        
        let fileContent = '';
        if (message.file_path) {
            if (message.file_type === 'image') {
                fileContent = `<div class="mb-2"><img src="/storage/${message.file_path}" alt="Image" class="rounded-lg max-w-xs cursor-pointer" onclick="window.open(this.src, '_blank')"></div>`;
            } else if (message.file_type === 'video') {
                fileContent = `<div class="mb-2"><video controls class="rounded-lg max-w-xs"><source src="/storage/${message.file_path}" type="video/mp4"></video></div>`;
            } else if (message.file_type === 'pdf' || message.file_type === 'document') {
                const icon = message.file_type === 'pdf' ? 'description' : 'attach_file';
                fileContent = `<div class="mb-2"><a href="/storage/${message.file_path}" target="_blank" class="flex items-center space-x-2 ${isOwnMessage ? 'text-white' : 'text-purple-600 dark:text-purple-400'} hover:underline"><span class="material-symbols-outlined">${icon}</span><span>View File</span></a></div>`;
            }
        }

        const messageText = message.message ? `<p class="break-words whitespace-pre-wrap">${escapeHtml(message.message)}</p>` : '';
        const time = new Date(message.created_at).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        
        messageDiv.innerHTML = `
            <div class="max-w-[75%] ${isOwnMessage ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white'} rounded-2xl p-3 shadow-sm">
                ${fileContent}
                ${messageText}
                <p class="text-xs mt-1 ${isOwnMessage ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400'}">
                    ${time}
                </p>
            </div>
        `;
        
        container.appendChild(messageDiv);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Handle form submission with AJAX
    document.getElementById('message-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageInput = document.getElementById('message-input');
        const fileInput = document.getElementById('file-input');
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                fileInput.value = '';
                removeFile();
                fetchNewMessages();
                scrollToBottom();
            }
        })
        .catch(error => console.error('Error sending message:', error));
    });

    // File preview function
    function previewFile(input) {
        const preview = document.getElementById('file-preview');
        const file = input.files[0];
        
        if (file) {
            preview.classList.remove('hidden');
            const fileType = file.type.split('/')[0];
            const fileName = file.name;
            
            let icon = 'attach_file';
            if (fileType === 'image') icon = 'image';
            else if (fileType === 'video') icon = 'videocam';
            else if (file.type === 'application/pdf') icon = 'description';
            
            preview.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">${icon}</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">${fileName}</span>
                    </div>
                    <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            `;
        }
    }

    function removeFile() {
        document.getElementById('file-input').value = '';
        document.getElementById('file-preview').classList.add('hidden');
    }
</script>
@endsection
