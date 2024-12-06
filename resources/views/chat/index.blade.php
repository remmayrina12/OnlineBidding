@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($users as $user)
        <div class="col-md-8">
            <div class="col-md-4">
                <h4>Chat with {{ $user->name }}</h4>
            </div>
            <div id="chat-box" style="height: 400px; overflow-y: auto; border: 1px solid #ccc; margin-bottom: 10px;"></div>

            <form id="message-form" action="{{ route('chat.send') }}" method="POST">
                @csrf
                <input type="hidden" id="receiver-id" name="receiver_id" value="{{ $user->id }}">
                <textarea id="message" name="message" class="form-control" placeholder="Type a message" rows="3"></textarea>
                <button type="submit" class="btn btn-primary mt-2">Send</button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatBox = document.getElementById('chat-box');
        const receiverId = {{ $user->id }};

        function fetchMessages() {
            fetch(`/chat/messages/fetchMessages/${receiverId}`)
                .then(response => response.json())
                .then(messages => {
                    chatBox.innerHTML = '';
                    messages.forEach(message => {
                        const messageElement = document.createElement('div');
                        messageElement.textContent = `${message.sender.name}: ${message.message}`;

                        // Add styling based on sender/receiver
                        if (message.sender_id === {{ Auth::id() }}) {
                            messageElement.style.textAlign = 'right';
                            messageElement.style.backgroundColor = '#d1ffd1'; // Light green for sender
                        } else {
                            messageElement.style.textAlign = 'left';
                            messageElement.style.backgroundColor = '#f1f1f1'; // Light grey for receiver
                        }

                        messageElement.style.margin = '5px';
                        messageElement.style.padding = '10px';
                        messageElement.style.borderRadius = '10px';
                        chatBox.appendChild(messageElement);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        document.getElementById('message-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(() => {
                document.getElementById('message').value = '';
                fetchMessages();
            });
        });

        setInterval(fetchMessages, 3000);
        fetchMessages();
    });
</script>
