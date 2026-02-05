{{-- AI Chatbot Component for Patient Detail Page --}}
{{-- Only shows on pages with patient context --}}

<style>
/* AI Chatbot Styles */
.ai-chatbot-container {
    position: fixed;
    bottom: 40px;
    right: 25px;
    z-index: 2147483647;
    font-family: 'Roboto', sans-serif;
}

/* Chat Toggle Button */
/* Chat Toggle Button */
.ai-chat-toggle {
    width: auto;
    height: 54px; /* Slightly reduced from 56 for tighter look */
    padding: 0 24px;
    border-radius: 27px;
    background: #ffffff;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0,0,0,0.02); /* Softer, diffused shadow */
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
    font-family: 'Poppins', sans-serif; /* Applied font */
}

.ai-chat-toggle span {
    font-size: 19px; /* Slightly larger */
    font-weight: 600;
    letter-spacing: -0.2px;
    /* Premium Gradient: Cyan -> Blue -> Purple */
    background: linear-gradient(92deg, #00f2fe 0%, #4facfe 50%, #f093fb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Hide toggle when chat is open matches JS class toggling on container */
.ai-chatbot-container.active .ai-chat-toggle {
    transform: scale(0.9) translateY(20px);
    opacity: 0;
    pointer-events: none;
}

.ai-chat-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.ai-chat-toggle svg {
    /* SVG size handled inline */
    flex-shrink: 0;
}

.ai-chat-toggle .chat-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #ff4757;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 10px;
    display: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Chat Window */
.ai-chat-window {
    position: absolute;
    bottom: 0; 
    right: 0;
    width: 380px;
    max-width: calc(100vw - 40px);
    height: 750px; /* Increased height */
    max-height: calc(100vh - 100px);
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
    z-index: 1;
}

.ai-chat-window.active {
    display: flex;
}
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Chat Header */
.ai-chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ai-chat-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.ai-chat-header-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ai-chat-header-icon svg {
    width: 22px;
    height: 22px;
    fill: white;
}

.ai-chat-header-text h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.ai-chat-header-text p {
    margin: 0;
    font-size: 12px;
    opacity: 0.9;
}

.ai-chat-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background 0.2s;
}

.ai-chat-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.ai-chat-close svg {
    width: 20px;
    height: 20px;
    fill: white;
}

/* Chat Messages */
.ai-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.ai-chat-message {
    display: flex;
    gap: 10px;
    max-width: 85%;
}

.ai-chat-message.user {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.ai-chat-message.bot {
    align-self: flex-start;
}

.ai-message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ai-chat-message.bot .ai-message-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.ai-chat-message.user .ai-message-avatar {
    background: #4CAF50;
}

.ai-message-avatar svg {
    width: 18px;
    height: 18px;
    fill: white;
}

.ai-message-content {
    background: white;
    padding: 12px 16px;
    border-radius: 16px;
    font-size: 14px;
    line-height: 1.5;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.ai-chat-message.user .ai-message-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.ai-message-content p {
    margin: 0 0 8px 0;
}

.ai-message-content p:last-child {
    margin-bottom: 0;
}

.ai-message-content ul, .ai-message-content ol {
    margin: 8px 0;
    padding-left: 20px;
}

.ai-message-content li {
    margin: 4px 0;
}

.ai-message-content strong {
    font-weight: 600;
}

.ai-typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.ai-typing-indicator span {
    width: 8px;
    height: 8px;
    background: #667eea;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.ai-typing-indicator span:nth-child(1) { animation-delay: 0s; }
.ai-typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.ai-typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.4;
    }
    30% {
        transform: translateY(-8px);
        opacity: 1;
    }
}

/* Chat Input */
.ai-chat-input-container {
    padding: 16px;
    background: white;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}

.ai-chat-input {
    flex: 1;
    border: 1px solid #e0e0e0;
    border-radius: 24px;
    padding: 12px 18px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
    resize: none;
    max-height: 100px;
    font-family: inherit;
}

.ai-chat-input:focus {
    border-color: #667eea;
}

.ai-chat-input::placeholder {
    color: #999;
}

.ai-chat-send {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}

.ai-chat-send:hover:not(:disabled) {
    transform: scale(1.05);
}

.ai-chat-send:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.ai-chat-send svg {
    width: 20px;
    height: 20px;
    fill: white;
}

/* Quick Actions */
.ai-quick-actions {
    padding: 10px 16px;
    background: white;
    border-top: 1px solid #eee;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.ai-quick-action {
    padding: 6px 12px;
    font-size: 12px;
    border: 1px solid #667eea;
    border-radius: 16px;
    background: white;
    color: #667eea;
    cursor: pointer;
    transition: all 0.2s;
}

.ai-quick-action:hover {
    background: #667eea;
    color: white;
}
</style>

<div class="ai-chatbot-container" id="aiChatbotContainer">
    <!-- Load Poppins Font for Premium Look -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet">
    
    <!-- Chat Toggle Button -->
    <button class="ai-chat-toggle" id="aiChatToggle" title="Open AI Assistant">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="premiumGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:#00f2fe;stop-opacity:1" /> <!-- Cyan -->
                    <stop offset="50%" style="stop-color:#4facfe;stop-opacity:1" /> <!-- Blue -->
                    <stop offset="100%" style="stop-color:#f093fb;stop-opacity:1" /> <!-- Pink/Purple -->
                </linearGradient>
            </defs>
            <!-- Chat Bubble Outline -->
            <path stroke="url(#premiumGradient)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M21 11.5C21.0029 10.1651 20.6698 8.84784 20.035 7.68591C19.4001 6.52398 18.4871 5.5607 17.3916 4.8966C16.2961 4.23249 15.0577 3.89163 13.8058 3.90938C12.5539 3.92714 11.334 4.30285 10.2736 4.99723M5.04456 9.5C4.3855 10.5658 4.02008 11.7828 3.98721 13.0232C3.95435 14.2636 4.25524 15.4815 4.85834 16.5497L4 21L8.59128 20.2312C9.64164 20.785 10.8242 21.0568 12.0152 21.0182"/>
            <!-- Sparkles -->
            <path fill="url(#premiumGradient)" d="M15 8L15.65 9.35C15.9 9.9 16.45 10.15 17 10.4L18.35 11.05L17 11.7C16.45 11.95 15.9 12.5 15.65 13.05L15 14.4L14.35 13.05C14.1 12.5 13.55 11.95 13 11.7L11.65 11.05L13 10.4C13.55 10.15 14.1 9.9 14.35 9.35L15 8Z"/>
            <path fill="url(#premiumGradient)" d="M9 11L9.35 11.8C9.5 12.1 9.8 12.2 10.1 12.3L10.9 12.65L10.1 13C9.8 13.1 9.5 13.4 9.35 13.7L9 14.5L8.65 13.7C8.5 13.4 8.2 13.1 7.9 13L7.1 12.65L7.9 12.3C8.2 12.2 8.5 12.1 8.65 11.8L9 11Z"/>
        </svg>
        <span>AI Assistant</span>
    </button>
    
    <!-- Chat Window -->
    <div class="ai-chat-window" id="aiChatWindow">
        <!-- Header -->
        <div class="ai-chat-header">
            <div class="ai-chat-header-info">
                <div class="ai-chat-header-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div class="ai-chat-header-text">
                    <h4>AI Medical Assistant</h4>
                    <p id="aiPatientName">{{ isset($patient_data) ? $patient_data->name . ' ' . ($patient_data->last_name ?? '') : 'Patient' }}</p>

                </div>
            </div>
            <button class="ai-chat-close" id="aiChatClose" title="Close">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        </div>
        
        <!-- Messages -->
        <div class="ai-chat-messages" id="aiChatMessages">
            <div class="ai-chat-message bot">
                <div class="ai-message-avatar">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div class="ai-message-content">
                    <p>Hello! I'm your AI medical assistant. I have access to <strong>{{ isset($patient_data) ? $patient_data->name : 'this patient' }}'s</strong> complete medical records.</p>
                    <p>Ask me anything about their:</p>
                    <ul>
                        <li>Diagnosis & Complications</li>
                        <li>Medications & Prescriptions</li>
                        <li>Lab Results & Vitals</li>
                        <li>Medical History</li>
                    </ul>
                </div>
            </div>
        </div>
        

        
        <!-- Input -->
        <div class="ai-chat-input-container">
            <textarea 
                class="ai-chat-input" 
                id="aiChatInput" 
                placeholder="Ask about this patient..." 
                rows="1"
                onkeydown="aiHandleKeyPress(event)"
            ></textarea>
            <button class="ai-chat-send" id="aiChatSend" onclick="aiSendMessage()" title="Send">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    // Constants
    const PATIENT_ID = {{ isset($patient_data) ? $patient_data->id : 0 }};
    const AI_CHAT_URL = "{{ route('ai.chat') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
    
    // Elements
    const chatContainer = document.getElementById('aiChatbotContainer');
    const chatToggle = document.getElementById('aiChatToggle');
    const chatWindow = document.getElementById('aiChatWindow');
    const chatClose = document.getElementById('aiChatClose');
    const chatMessages = document.getElementById('aiChatMessages');
    const chatInput = document.getElementById('aiChatInput');
    const chatSend = document.getElementById('aiChatSend');
    
    // State
    let isLoading = false;
    
    // Toggle chat window
    chatToggle.addEventListener('click', function() {
        chatContainer.classList.add('active');
        chatWindow.classList.add('active');
        chatInput.focus();
    });
    
    chatClose.addEventListener('click', function() {
        chatContainer.classList.remove('active');
        chatWindow.classList.remove('active');
    });
    
    // Close on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.ai-chatbot-container') && chatContainer.classList.contains('active')) {
            chatContainer.classList.remove('active');
            chatWindow.classList.remove('active');
        }
    });
    
    // Auto-resize textarea
    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
    
    // Handle keypress
    window.aiHandleKeyPress = function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            aiSendMessage();
        }
    };
    
    // Send quick message
    window.aiSendQuickMessage = function(message) {
        chatInput.value = message;
        aiSendMessage();
    };
    
    // Send message
    window.aiSendMessage = function() {
        const message = chatInput.value.trim();
        if (!message || isLoading) return;
        
        // Add user message
        addMessage(message, 'user');
        chatInput.value = '';
        chatInput.style.height = 'auto';
        
        // Show typing indicator
        showTypingIndicator();
        
        // Disable input
        isLoading = true;
        chatSend.disabled = true;
        
        // Send request
        fetch(AI_CHAT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                patient_id: PATIENT_ID,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            
            if (data.status === 'success') {
                addMessage(data.response, 'bot');
            } else {
                addMessage(data.message || 'Sorry, I encountered an error. Please try again.', 'bot');
            }
        })
        .catch(error => {
            console.error('AI Chat Error:', error);
            hideTypingIndicator();
            addMessage('Sorry, I could not connect to the AI service. Please check your connection and try again.', 'bot');
        })
        .finally(() => {
            isLoading = false;
            chatSend.disabled = false;
            chatInput.focus();
        });
    };
    
    // Add message to chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `ai-chat-message ${sender}`;
        
        const avatarSvg = sender === 'bot' 
            ? '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>'
            : '<svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';
        
        // Format text with basic markdown
        const formattedText = formatMessage(text);
        
        messageDiv.innerHTML = `
            <div class="ai-message-avatar">${avatarSvg}</div>
            <div class="ai-message-content">${formattedText}</div>
        `;
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }
    
    // Format message with basic markdown
    function formatMessage(text) {
        // Convert newlines to <br>
        let formatted = text.replace(/\n/g, '<br>');
        
        // Convert **bold** to <strong>
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Convert *italic* to <em>
        formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        // Convert bullet points
        formatted = formatted.replace(/^- (.*?)(<br>|$)/gm, '<li>$1</li>');
        
        // Wrap consecutive li elements in ul
        formatted = formatted.replace(/(<li>.*?<\/li>\s*)+/g, '<ul>$&</ul>');
        
        // Wrap in paragraph if no HTML tags
        if (!formatted.includes('<')) {
            formatted = `<p>${formatted}</p>`;
        }
        
        return formatted;
    }
    
    // Show typing indicator
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'aiTypingIndicator';
        typingDiv.className = 'ai-chat-message bot';
        typingDiv.innerHTML = `
            <div class="ai-message-avatar">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            </div>
            <div class="ai-typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        scrollToBottom();
    }
    
    // Hide typing indicator
    function hideTypingIndicator() {
        const indicator = document.getElementById('aiTypingIndicator');
        if (indicator) {
            indicator.remove();
        }
    }
    
    // Scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
})();
</script>
