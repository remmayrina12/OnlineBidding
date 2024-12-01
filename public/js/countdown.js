function startCountdown(timerElement, endTime, productId) {
    let auctionStatus = timerElement.dataset.auctionStatus; // Get initial auction status

    console.log(`Starting countdown for product ${productId} with end time ${endTime}`);
    console.log(`Initial auction status: ${auctionStatus}`);

    timerElement.timerInterval = setInterval(function () {
        const now = Math.floor(Date.now() / 1000); // Current time in seconds
        const remainingTime = endTime - now;

        console.log(`Current time: ${now}, Remaining time: ${remainingTime}`);

        if (remainingTime > 0 && auctionStatus === 'open') {
            // Update the countdown UI
            const hours = Math.floor(remainingTime / 3600);
            const minutes = Math.floor((remainingTime % 3600) / 60);
            const seconds = remainingTime % 60;
            timerElement.innerHTML = `${hours}h ${minutes}m ${seconds}s`;

            console.log(`Countdown updated: ${hours}h ${minutes}m ${seconds}s`);
        } else {
            clearInterval(timerElement.timerInterval); // Stop the countdown
            timerElement.innerHTML = "Bidding Closed";

            console.log(`Timer ended for product ${productId}. Auction status: ${auctionStatus}`);

            if (auctionStatus === 'open') {
                auctionStatus = 'closed'; // Update local status to prevent duplicate calls
                console.log(`Sending notification for ended auction of product ${productId}`);

                // Send AJAX request to notify the users
                sendAuctionEndedNotification(productId);
            }
        }
    }, 1000);
}


function sendAuctionEndedNotification(productId) {
    console.log(`Preparing to send notification for product ${productId}`);

    fetch(`/auctioneer/end/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ productId })
    })
    .then(response => {
        console.log(`Notification request sent. Status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        console.log('Notification response:', data);
    })
    .catch(error => {
        console.error('Error sending notification:', error);
    });
}


// function triggerButton(productId) {
//     const button = document.querySelector(`a.end-countdown-button[data-product-id="${productId}"]`);
//     if (button) {
//         // Append a query parameter to indicate a programmatic request
//         const originalHref = button.href;
//         const programmaticHref = `${originalHref}?trigger=programmatic`;

//         // Temporarily set the programmatic URL
//         button.href = programmaticHref;

//         button.onclick = null;

//         button.click(); // Trigger the click event

//         // Restore the original URL
//         button.href = originalHref;
//     }
// }

// Initialize countdowns for all timers on the page and modals
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auction-timer').forEach(function (timerElement) {
        // Each timer element should have a data-end-time and data-product-id attribute
        const endTime = parseInt(timerElement.dataset.endTime, 10); // Get auction end time
        const productId = timerElement.id.replace('countdownTimer', ''); // Extract product ID from the element's ID

        if (!isNaN(endTime) && productId) {
            startCountdown(timerElement, endTime, productId);
        }
    });
});


