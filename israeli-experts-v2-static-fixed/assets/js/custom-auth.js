// Function to open the modal programmatically
function openLoginModal() {
    // Get the modal element by its ID
    const loginModalElement = document.getElementById('modal-login');

    // Check if the element exists and if Bootstrap is loaded
    if (loginModalElement && typeof bootstrap !== 'undefined') {
        // Create a new Bootstrap Modal instance
        const loginModal = new bootstrap.Modal(loginModalElement);
        // Show the modal
        loginModal.show();
    } else {
        console.error('Modal element or Bootstrap not found.');
    }
}

