@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Chat with user
                </div>
                <div class="card-body p-3">
                    <div id="messages" class="chat-messages p-3 mb-3 border rounded" style="height: 300px; overflow-y: auto; background-color: #f9f9f9;">
                        <!-- Messages will load here -->
                    </div>
                    <div class="input-group">
                        <input type="text" id="message" name="message" class="form-control" placeholder="Type a message..." aria-label="Type a message" {{ Auth::id() === $userId ? 'disabled' : '' }}>
                        <button type="submit" id="send" class="btn btn-primary" {{ Auth::id() === $userId ? 'disabled' : '' }}>Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
<script>
    const userId = {{ auth()->id() }};
    const receiverId = {{ $userId ?? 0 }}; // Ensure receiver ID is passed from the server

    if (userId === receiverId) {
        alert("You cannot message yourself.");
    }

    const fetchMessages = () => {
        fetch(`/profile/messages/${receiverId}`)
            .then(response => response.json())
            .then(messages => {
                console.log(messages); // Add this to debug the data
                const messagesDiv = document.getElementById('messages');
                messagesDiv.innerHTML = messages.map(msg => `
                    <div class="d-flex ${msg.sender_id === userId ? 'justify-content-end' : 'justify-content-start'} my-2">
                        <div class="d-inline-block px-3 py-2 ${msg.sender_id === userId ? 'bg-primary text-white' : 'bg-light'} rounded">
                            ${msg.message}
                        </div>
                    </div>
                `).join('');
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
    };


    document.getElementById('send').addEventListener('click', () => {
        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();
        if (!message) return;

        if (userId === receiverId) {
            alert("You cannot send messages to yourself.");
            return;
        }

        fetch('/profile/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ receiver_id: receiverId, message }),
        })
            .then(response => {
                if (response.ok) {
                    messageInput.value = '';
                    fetchMessages();
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data && data.error) {
                    alert(data.error);
                }
            });
    });

    window.Echo.channel('chat-channel')
        .listen('message-sent', (e) => {
            console.log(e.message); // Debug received event data
            if (e.message.sender_id === receiverId || e.message.receiver_id === receiverId) {
                fetchMessages();
            }
        });

    // Load messages initially
    fetchMessages();
</script>
@endsection
