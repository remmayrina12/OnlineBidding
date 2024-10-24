// Function to start the countdown
function startCountdown(timerElement, endTime) {
    const timerInterval = setInterval(function () {
        const now = Math.floor(Date.now() / 1000); // Current time in seconds
        const remainingTime = endTime - now;

        if (remainingTime > 0) {
            const hours = Math.floor(remainingTime / 3600);
            const minutes = Math.floor((remainingTime % 3600) / 60);
            const seconds = remainingTime % 60;

            // Update the timer display
            timerElement.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        } else {
            clearInterval(timerInterval);
            timerElement.innerHTML = "Bidding Closed";
            // Optionally disable the bid button inside the same card/modal
            const bidButton = timerElement.closest('.product-card, .modal-content').querySelector('.submit-button');
            if (bidButton) bidButton.disabled = true;
        }
    }, 1000);
}

// Initialize countdowns for all timers on the page and modals
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auction-timer').forEach(function (timerElement) {
        // Each timer element should have a data-end-time attribute with the Unix end time
        const endTime = parseInt(timerElement.dataset.endTime, 10);
        if (!isNaN(endTime)) {
            startCountdown(timerElement, endTime);
        }
    });
});
