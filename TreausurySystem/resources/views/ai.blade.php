<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Chat') }}
        </h2>
    </x-slot>

    <style>
        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden p-6 sm:p-8">
                <div id="chat-box" class="space-y-4 max-h-[60vh] overflow-y-auto mb-4 px-4 py-2">
                    <!-- Chat messages will appear here -->
                </div>
                <div class="flex items-center space-x-2 px-4 pb-4">
                    <input id="user-input" type="text"
                        class="flex-1 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                        placeholder="Type your message...">
                    <button id="send-btn"
                        class="bg-black hover:bg-gray-800
 text-white font-semibold px-5 py-2 rounded">Send</button>
                </div>
                {{-- <div id="loader" class="loader hidden">
                    <div class="loader-inner">Loading...</div>
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        const chatBox = $('#chat-box');
        const userInput = $('#user-input');
        const sendBtn = $('#send-btn');
        const loader = $('#loader');

        let messages = [{
            role: 'system',
            content: 'You are a helpful assistant.'
        }];

        function appendMessage(role, message) {
            const isUser = role === 'user';
            const alignment = isUser ? 'justify-end' : 'justify-start';
            const bubbleStyle = isUser ?
                'bg-gray-800 text-white rounded-br-none' :
                'bg-indigo-100 text-indigo-800 rounded-bl-none';

            const msgHtml = `
        <div class="flex ${alignment}">
            <div class="max-w-[75%] px-4 py-2 rounded-lg mb-2 ${bubbleStyle}">
                <span class="block text-sm whitespace-pre-wrap">${message}</span>
            </div>
        </div>`;

            chatBox.append(msgHtml);
            chatBox.scrollTop(chatBox.prop('scrollHeight'));
        }


        function sendMessage(message) {
            messages.push({
                role: 'user',
                content: message
            });
            appendMessage('user', message);
            loader.removeClass('hidden');

            $.ajax({
                url: 'http://localhost:1234/v1/chat/completions',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    model: 'gemma-3n-e4b-it-text',
                    messages: messages,
                    temperature: 0.6
                }),
                success: function(response) {
                    if (response && response.choices && response.choices[0].message) {
                        const reply = response.choices[0].message.content;
                        messages.push({
                            role: 'assistant',
                            content: reply
                        });
                        const formattedReply = reply.replace(/\*\*([^*]+)\*\*/g, '<br>$1');
                        appendMessage('assistant', formattedReply);
                    } else {
                        appendMessage('assistant', '⚠️ Unexpected response format.');
                    }
                    loader.addClass('hidden');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText || error);
                    const errorMsg = '⚠️ Error: Could not connect to AI.';
                    messages.push({
                        role: 'assistant',
                        content: errorMsg
                    });
                    appendMessage('assistant', errorMsg);
                    loader.addClass('hidden');
                }
            });
        }

        sendBtn.on('click', function() {
            const message = userInput.val().trim();
            if (message) {
                sendMessage(message);
                userInput.val('');
            }
        });

        userInput.on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) {
                sendBtn.click();
            }
        });
    });
</script>
