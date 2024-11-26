function startCountdown(timerElement, endTime, productId, auctionStatus) {
    timerElement.timerInterval = setInterval(function () {
        const now = Math.floor(Date.now() / 1000); // Current time in seconds
        const remainingTime = endTime - now;

        if (remainingTime > 0 && auctionStatus === 'open') {
            // Update the countdown UI
            const hours = Math.floor(remainingTime / 3600);
            const minutes = Math.floor((remainingTime % 3600) / 60);
            const seconds = remainingTime % 60;
            timerElement.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        } else {
            clearInterval(timerElement.timerInterval); // Stop the countdown
            timerElement.innerHTML = "Bidding Closed";

            // Notify the server to close the auction and send notifications
            if (auctionStatus === 'open') {
                auctionStatus = 'closed'; // Update local status to prevent multiple requests
                fetch(`/auctions/${productId}/end`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Auction closed:", data.message);
                })
                .catch(error => console.error("Error closing auction:", error));
            }
        }
    }, 1000);
}

// resources/js/notifications.js

document.addEventListener('DOMContentLoaded', () => {
    // Ensure `userId` is provided by the backend
    const userId = window.userId;

    window.Echo.private(`users.${userId}`)
        .notification((notification) => {
            console.log(notification.message); // Log the notification message
            alert(notification.message); // Display as an alert

            // Optionally, update the notifications list in the UI
            const notificationsList = document.getElementById('notifications-list');
            const newNotification = document.createElement('li');
            newNotification.className = 'font-weight-bold';
            newNotification.innerHTML = `
                ${notification.message}
                <small>Just now</small>
            `;
            notificationsList.prepend(newNotification);
        });
});
