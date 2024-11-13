// Start Countdown Function
function startCountdown(timerElement, endTime, productId, auctionStatus) {
    // Use a separate interval for each product
    timerElement.timerInterval = setInterval(function () {
        const now = Math.floor(Date.now() / 1000); // Current time in seconds
        const remainingTime = endTime - now;

        // Only update countdown if auction is open
        if (remainingTime > 0 && auctionStatus == 'open') {
            const hours = Math.floor(remainingTime / 3600);
            const minutes = Math.floor((remainingTime % 3600) / 60);
            const seconds = remainingTime % 60;

            // Update the timer display
            timerElement.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        } else {
            clearInterval(timerElement.timerInterval); // Stop countdown
            timerElement.innerHTML = "Bidding Closed";

            // Disable the bid button if the auction is closed
            const bidButton = timerElement.closest('.product-card, .modal-content').querySelector('.submit-button');
            if (bidButton) bidButton.disabled = true;
        }
    }, 1000);
}

document.addEventListener('DOMContentLoaded', function () {
    // Loop through all timers on the page
    document.querySelectorAll('.auction-timer').forEach(function (timerElement) {
        const endTime = parseInt(timerElement.dataset.endTime, 10);
        const productId = timerElement.dataset.productId;
        let auctionStatus = timerElement.dataset.auctionStatus;  // Track auction status in data attribute

        // Initialize countdown for each timer independently
        startCountdown(timerElement, endTime, productId, auctionStatus);

        // Check if the auction has been ended via session message
        const auctionEndedMessage = document.querySelector('.auction-ended-message');
        if (auctionEndedMessage) {
            // Stop the countdown for the ended auction
            if (auctionEndedMessage.dataset.productId == productId) {
                auctionStatus = 'closed';  // Update auction status in JS
                timerElement.dataset.auctionStatus = auctionStatus;  // Update the DOM with new status
                clearInterval(timerElement.timerInterval); // Stop countdown for the current product
                timerElement.innerHTML = "Bidding Closed"; // Update text for that product
                const bidButton = timerElement.closest('.product-card, .modal-content').querySelector('.submit-button');
                if (bidButton) bidButton.disabled = true; // Disable bid button for that product
            }
        }

        // Add event listener for the "End Countdown" button to end auction for a specific product
        const endButton = document.querySelector(`#end[data-product-id="${productId}"]`);
        if (endButton) {
            endButton.addEventListener('click', function () {
                auctionStatus = 'closed';  // Update status to 'closed' for the clicked product
                timerElement.dataset.auctionStatus = auctionStatus;  // Update the DOM with new status
                clearInterval(timerElement.timerInterval); // Stop countdown for the clicked product
                timerElement.innerHTML = "Bidding Closed"; // Update text for that product
                const bidButton = timerElement.closest('.product-card, .modal-content').querySelector('.submit-button');
                if (bidButton) bidButton.disabled = true; // Disable bid button for that product
            });
        }
    });
});
