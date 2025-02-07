document.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById("custom-popup");
    const closeBtn = document.querySelector(".popup-close");

    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            popup.style.display = "none"; // Hide the popup when clicking the close button
        });
    }

    // Optional: Close popup when clicking outside of it
    popup.addEventListener("click", function (event) {
        if (event.target === popup) {
            popup.style.display = "none";
        }
    });

    // Show popup after page load
    setTimeout(function () {
        popup.style.display = "flex"; // Ensure the popup is displayed properly
    }, 1000); // Delay popup appearance (1 second)
});
