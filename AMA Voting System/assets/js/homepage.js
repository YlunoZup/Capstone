// Get the modal, button, text, and icon
const menuBtn = document.querySelector('.menu-bar .menu-toggle i');
const menuText = document.getElementById('menuText'); // Text element that will change
const modal = document.getElementById('menuModal');
const closeModalBtn = document.getElementById('closeModal');

// Get the profile info container
const profileInfo = document.querySelector('.profile-info');

// Function to open the modal and change the icon and text
function openModal() {
    modal.style.display = 'block'; // Show the modal
    setTimeout(() => {
        modal.classList.add('show'); // Trigger the drop-down animation
    }, 10); // Small delay to apply display block

    // Change the icon from 'fa-bars' (hamburger) to 'fa-x' (close)
    menuBtn.classList.remove('fa-bars');
    menuBtn.classList.add('fa-x');

    // Change the text from 'Menu' to 'Close'
    menuText.textContent = 'Close';
}

// Function to close the modal and revert the icon and text back to 'Menu'
function closeModal() {
    modal.classList.remove('show'); // Start the slide-up effect
    setTimeout(() => {
        modal.style.display = 'none'; // Hide the modal after animation
    }, 500); // Wait for animation to complete

    // Revert the icon back to 'fa-bars'
    menuBtn.classList.remove('fa-x');
    menuBtn.classList.add('fa-bars');

    // Revert the text back to 'Menu'
    menuText.textContent = 'Menu';
}

// When the user clicks the menu button or the text, show the modal
menuBtn.addEventListener('click', openModal);
menuText.addEventListener('click', openModal);

// When the user clicks the close button, hide the modal
closeModalBtn.addEventListener('click', closeModal);

// When the user clicks anywhere outside the modal, close it
window.addEventListener('click', function(event) {
    if (event.target === modal) {
        closeModal();
    }
});

// Add an event listener to the profile info container to toggle the modal
profileInfo.addEventListener('click', function() {
    if (modal.style.display === 'block') {
        closeModal(); // Close the modal if it's already open
    } else {
        openModal(); // Open the modal if it's closed
    }
});

// Function to update the current date and time
function updateDateTime() {
    const dateTimeElement = document.getElementById('dateTime');
    
    const now = new Date();

    // Format for the date
    const dateOptions = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric'
    };
    const formattedDate = now.toLocaleDateString('en-US', dateOptions);

    // Format for the time
    const timeOptions = { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: true 
    };
    const formattedTime = now.toLocaleTimeString('en-US', timeOptions);

    // Combine the formatted date and time
    dateTimeElement.innerHTML = `${formattedDate} - ${formattedTime}`;
}

// Update the date and time every second
setInterval(updateDateTime, 1000);

// Initial call to display the date and time
updateDateTime();


// Update the date and time every second
setInterval(updateDateTime, 1000);

// Initial call to display the date and time
updateDateTime();
