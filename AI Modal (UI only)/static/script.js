// Toggle the chatbot modal
function toggleChatbot() {
    const modal = document.getElementById('chatbotModal');
    modal.classList.toggle('open');
}

// Get elements
const sendButton = document.getElementById('send-btn');
const inputBox = document.getElementById('chatbotInput');
const chatBox = document.getElementById('chatbotContent');

// Event listener for the "Send" button
sendButton.addEventListener('click', sendMessage);

// Event listener for sending the message using the "Enter" key
inputBox.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();  // Prevent default behavior for Enter key (form submit)
        sendMessage();  // Trigger sendMessage() function
    }
});

// Function to send the message
async function sendMessage() {
    const message = inputBox.value;  // Get the message from the input field
    if (message.trim() === '') return;  // Don't send if the input is empty

    // Display user message
    displayMessage('You: ' + message);
    inputBox.value = '';  // Clear the input field

    try {
        // Send message to Flask backend
        const response = await fetch('/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message })  // Send the message as a JSON payload
        });

        const data = await response.json();

        // Handle the bot's response
        if (data.response) {
            displayMessage('AI: ' + data.response);
        } else {
            displayMessage('Error: ' + data.error);
        }
    } catch (error) {
        console.error('Error:', error);
        displayMessage('Error: Unable to communicate with the server.');
    }
}

// Function to display messages in the chat box
function displayMessage(message) {
    const div = document.createElement('div');
    div.textContent = message;
    div.classList.add(message.includes('You:') ? 'user-message' : 'bot-message');  // Add appropriate class for styling
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;  // Scroll to the bottom when a new message is added
}
