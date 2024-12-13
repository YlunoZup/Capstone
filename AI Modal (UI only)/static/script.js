function toggleChatbot() {
    const modal = document.getElementById('chatbotModal');
    modal.classList.toggle('open');
}

const sendButton = document.getElementById('send-btn');
const inputBox = document.getElementById('chatbotInput');
const chatBox = document.getElementById('chatbotContent');


sendButton.addEventListener('click', sendMessage);

inputBox.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault(); 
        sendMessage(); 
    }
});

async function sendMessage() {
    const message = inputBox.value;
    if (message.trim() === '') return;

    
    displayMessage('You: ' + message);
    inputBox.value = ''; 

    try {
        
        const response = await fetch('/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message }) 
        });

        const data = await response.json();

        
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

function displayMessage(message) {
    const div = document.createElement('div');
    div.textContent = message;
    div.classList.add(message.includes('You:') ? 'user-message' : 'bot-message');
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
}
