@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h4>Users</h4>
            <ul class="list-group">
                @foreach($users as $user)
                    <li class="list-group-item user" data-id="{{ $user->id }}">{{ $user->name }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-8">
            <div id="chat-box" style="height: 400px; overflow-y: auto; border: 1px solid #ccc; margin-bottom: 10px;">
                <!-- Messages will appear here -->
            </div>
            <form id="message-form" method="POST" action="{{route('chat.send')}}">
                @csrf
                <input type="hidden" id="receiver-id">
                <textarea id="message" class="form-control" placeholder="Type a message" rows="3"></textarea>
                <button type="submit" class="btn btn-primary mt-2">Send</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let receiverId = null;

        // Attach click event to user list items
        document.querySelectorAll('.user').forEach(user => {
            user.addEventListener('click', () => {
                receiverId = user.dataset.id;
                document.getElementById('receiver-id').value = receiverId;

                // Fetch existing messages for the selected user
                fetch(`/chat/messages/${receiverId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to fetch messages.');
                        return response.json();
                    })
                    .then(messages => {
                        const chatBox = document.getElementById('chat-box');
                        chatBox.innerHTML = ''; // Clear previous messages
                        messages.forEach(message => {
                            const messageDiv = document.createElement('div');
                            const sender = message.sender_id == receiverId ? 'Them' : 'You';
                            messageDiv.textContent = `${sender}: ${message.message}`;
                            chatBox.appendChild(messageDiv);
                        });
                        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to bottom
                    })
                    .catch(error => console.error('Error loading messages:', error));

                // Listen for new messages
                window.Echo.private(`chat.${receiverId}`)
                    .listen('MessageSent', (event) => {
                        const chatBox = document.getElementById('chat-box');
                        const messageDiv = document.createElement('div');
                        messageDiv.textContent = `Them: ${event.message.message}`;
                        chatBox.appendChild(messageDiv);
                        chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the bottom
                    });
            });
        });

        // Handle sending messages
        document.getElementById('message-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const messageInput = document.getElementById('message');
            const message = messageInput.value.trim();

            if (!receiverId || message === '') {
                alert('Please select a user and enter a message.');
                return;
            }

            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ receiver_id: receiverId, message })
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to send message.');
                return response.json();
            })
            .then(data => {
                const chatBox = document.getElementById('chat-box');
                const messageDiv = document.createElement('div');
                messageDiv.textContent = `You: ${data.message}`;
                chatBox.appendChild(messageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
                messageInput.value = '';
            })
            .catch(error => console.error('Error sending message:', error));
        });
    });
</script>
@endsection
