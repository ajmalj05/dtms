{{-- AI Chatbot Component for Patient Detail Page --}}
{{-- Only shows on pages with patient context --}}

<style>
.ai-chatbot-container {
    --ai-primary: #0f766e;
    --ai-primary-dark: #115e59;
    --ai-accent: #14b8a6;
    --ai-surface: #ffffff;
    --ai-surface-soft: #f5fbfa;
    --ai-surface-muted: #eef6f5;
    --ai-border: #d7e7e3;
    --ai-text: #18332f;
    --ai-text-muted: #5d7570;
    --ai-shadow: 0 24px 70px rgba(18, 53, 47, 0.22);
    position: fixed;
    right: 24px;
    bottom: 24px;
    z-index: 2147483647;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.ai-chat-toggle {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border: 0;
    border-radius: 18px;
    background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
    color: #ffffff;
    cursor: pointer;
    box-shadow: 0 20px 45px rgba(15, 118, 110, 0.32);
    transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
}

.ai-chat-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 24px 55px rgba(15, 118, 110, 0.4);
}

.ai-chat-toggle svg {
    width: 22px;
    height: 22px;
    flex-shrink: 0;
}

.ai-chat-toggle-label {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.1;
}

.ai-chat-toggle-label strong {
    font-size: 15px;
    font-weight: 700;
}

.ai-chat-toggle-label span {
    font-size: 12px;
    opacity: 0.88;
}

.ai-chatbot-container.active .ai-chat-toggle {
    opacity: 0;
    pointer-events: none;
    transform: translateY(16px) scale(0.94);
}

.ai-chat-window {
    position: absolute;
    right: 0;
    bottom: 0;
    width: min(440px, calc(100vw - 24px));
    height: min(760px, calc(100vh - 40px));
    display: none;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid rgba(215, 231, 227, 0.9);
    border-radius: 28px;
    background:
        radial-gradient(circle at top left, rgba(20, 184, 166, 0.14), transparent 34%),
        linear-gradient(180deg, #fbfffe 0%, #f3fbf9 100%);
    box-shadow: var(--ai-shadow);
    animation: ai-slide-up 0.22s ease;
}

.ai-chat-window.active {
    display: flex;
}

@keyframes ai-slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ai-chat-header {
    padding: 20px 20px 16px;
    color: #ffffff;
    background:
        radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 28%),
        linear-gradient(135deg, #0f766e 0%, #0b5f58 100%);
}

.ai-chat-header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.ai-chat-header-info {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.ai-chat-header-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(8px);
    flex-shrink: 0;
}

.ai-chat-header-icon svg {
    width: 24px;
    height: 24px;
    fill: #ffffff;
}

.ai-chat-header-text {
    min-width: 0;
}

.ai-chat-header-text h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.01em;
}

.ai-chat-header-text p {
    margin: 4px 0 0;
    font-size: 13px;
    opacity: 0.92;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ai-chat-close {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 12px;
    color: #ffffff;
    background: rgba(255, 255, 255, 0.12);
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}

.ai-chat-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.04);
}

.ai-chat-close svg {
    width: 18px;
    height: 18px;
    fill: currentColor;
}

.ai-chat-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 14px;
    padding: 9px 12px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.12);
    font-size: 12px;
    color: rgba(255, 255, 255, 0.94);
}

.ai-chat-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #86efac;
    box-shadow: 0 0 0 4px rgba(134, 239, 172, 0.18);
}

.ai-chat-quick-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    padding: 14px 16px 0;
}

.ai-quick-action {
    border: 1px solid var(--ai-border);
    background: rgba(255, 255, 255, 0.82);
    color: var(--ai-primary-dark);
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
}

.ai-quick-action:hover {
    transform: translateY(-1px);
    border-color: var(--ai-accent);
    background: #ffffff;
}

.ai-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.ai-chat-messages::-webkit-scrollbar {
    width: 8px;
}

.ai-chat-messages::-webkit-scrollbar-thumb {
    background: #c8ddd8;
    border-radius: 999px;
}

.ai-chat-message {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    max-width: 92%;
}

.ai-chat-message.user {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.ai-chat-message.bot {
    align-self: flex-start;
}

.ai-message-stack {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
}

.ai-message-avatar {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    flex-shrink: 0;
}

.ai-chat-message.bot .ai-message-avatar {
    background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
    box-shadow: 0 12px 24px rgba(15, 118, 110, 0.18);
}

.ai-chat-message.user .ai-message-avatar {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18);
}

.ai-message-avatar svg {
    width: 18px;
    height: 18px;
    fill: #ffffff;
}

.ai-message-content {
    min-width: 0;
    padding: 14px 16px;
    border: 1px solid var(--ai-border);
    border-radius: 20px;
    background: #ffffff;
    color: var(--ai-text);
    font-size: 14px;
    line-height: 1.65;
    box-shadow: 0 10px 26px rgba(18, 53, 47, 0.06);
    word-break: break-word;
}

.ai-chat-message.bot .ai-message-content {
    border-top-left-radius: 8px;
}

.ai-chat-message.user .ai-message-content {
    color: #ffffff;
    border: 0;
    border-top-right-radius: 8px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    box-shadow: 0 12px 28px rgba(37, 99, 235, 0.24);
}

.ai-message-content p,
.ai-message-content ul,
.ai-message-content ol {
    margin: 0;
}

.ai-message-content p + p,
.ai-message-content p + ul,
.ai-message-content p + ol,
.ai-message-content ul + p,
.ai-message-content ol + p {
    margin-top: 8px;
}

.ai-message-content ul,
.ai-message-content ol {
    padding-left: 18px;
}

.ai-message-content li + li {
    margin-top: 6px;
}

.ai-message-content strong {
    font-weight: 700;
}

.ai-message-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-left: 4px;
}

.ai-chat-message.user .ai-message-meta {
    justify-content: flex-end;
    padding-right: 4px;
    padding-left: 0;
}

.ai-message-caption {
    font-size: 11px;
    color: var(--ai-text-muted);
}

.ai-message-tts {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 10px;
    color: var(--ai-primary);
    background: rgba(15, 118, 110, 0.08);
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease, opacity 0.2s ease;
}

.ai-message-tts:hover,
.ai-message-tts.speaking {
    background: rgba(15, 118, 110, 0.14);
    transform: translateY(-1px);
}

.ai-message-tts.speaking {
    color: #047857;
}

.ai-typing-indicator {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 14px 16px;
    border: 1px solid var(--ai-border);
    border-radius: 18px;
    background: #ffffff;
}

.ai-typing-indicator span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #0f766e;
    animation: ai-typing 1.15s infinite ease-in-out;
}

.ai-typing-indicator span:nth-child(2) {
    animation-delay: 0.15s;
}

.ai-typing-indicator span:nth-child(3) {
    animation-delay: 0.3s;
}

@keyframes ai-typing {
    0%, 80%, 100% {
        opacity: 0.35;
        transform: translateY(0);
    }
    40% {
        opacity: 1;
        transform: translateY(-4px);
    }
}

.ai-chat-input-wrap {
    padding: 14px 16px 16px;
    background: rgba(255, 255, 255, 0.92);
    border-top: 1px solid rgba(215, 231, 227, 0.9);
}

.ai-chat-input-shell {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    padding: 10px;
    border: 1px solid var(--ai-border);
    border-radius: 22px;
    background: #ffffff;
    box-shadow: 0 12px 30px rgba(18, 53, 47, 0.06);
}

.ai-chat-input {
    flex: 1;
    min-height: 24px;
    max-height: 120px;
    border: 0;
    outline: 0;
    resize: none;
    background: transparent;
    color: var(--ai-text);
    font-size: 14px;
    line-height: 1.5;
    padding: 6px 4px;
    font-family: inherit;
}

.ai-chat-input::placeholder {
    color: #7b9390;
}

.ai-chat-send {
    width: 46px;
    height: 46px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 16px;
    background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
    color: #ffffff;
    cursor: pointer;
    flex-shrink: 0;
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.ai-chat-send:hover:not(:disabled) {
    transform: translateY(-1px) scale(1.02);
}

.ai-chat-send:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.ai-chat-send svg {
    width: 20px;
    height: 20px;
    fill: currentColor;
}

.ai-chat-input-note {
    margin-top: 8px;
    font-size: 11px;
    color: var(--ai-text-muted);
    text-align: center;
}

@media (max-width: 768px) {
    .ai-chatbot-container {
        right: 12px;
        bottom: 12px;
        left: 12px;
    }

    .ai-chat-toggle {
        width: 100%;
        justify-content: center;
    }

    .ai-chat-window {
        width: 100%;
        height: min(82vh, 760px);
        border-radius: 24px;
    }

    .ai-chat-message {
        max-width: 100%;
    }
}
</style>

<div class="ai-chatbot-container" id="aiChatbotContainer">
    <button class="ai-chat-toggle" id="aiChatToggle" title="Open AI Medical Assistant">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor" d="M19 3H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3v4l4.8-4H19a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-8 11H9v-2H7v-2h2V8h2v2h2v2h-2v2z"/>
        </svg>
        <span class="ai-chat-toggle-label">
            <strong>AI Medical Assistant</strong>
            <span>Short, point-wise answers</span>
        </span>
    </button>

    <div class="ai-chat-window" id="aiChatWindow">
        <div class="ai-chat-header">
            <div class="ai-chat-header-top">
                <div class="ai-chat-header-info">
                    <div class="ai-chat-header-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M19 3H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3v4l4.8-4H19a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-8 11H9v-2H7v-2h2V8h2v2h2v2h-2v2z"/>
                        </svg>
                    </div>
                    <div class="ai-chat-header-text">
                        <h4>AI Medical Assistant</h4>
                        <p id="aiPatientName">{{ isset($patient_data) ? trim($patient_data->name . ' ' . ($patient_data->last_name ?? '')) : 'Patient' }}</p>
                    </div>
                </div>
                <button class="ai-chat-close" id="aiChatClose" title="Close">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18.3 5.71 12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.29 19.71 2.88 18.3 9.17 12 2.88 5.71 4.29 4.29l6.3 6.3 6.3-6.3z"/>
                    </svg>
                </button>
            </div>

            <div class="ai-chat-status">
                <span class="ai-chat-status-dot"></span>
                Clinical record assistant ready
            </div>
        </div>

        <div class="ai-chat-quick-actions">
            <button type="button" class="ai-quick-action" onclick="window.aiSendQuickMessage('Give the latest lab report in short bullet points')">Latest labs</button>
            <button type="button" class="ai-quick-action" onclick="window.aiSendQuickMessage('List current medicines in short bullet points')">Current meds</button>
            <button type="button" class="ai-quick-action" onclick="window.aiSendQuickMessage('Show key vitals in short bullet points')">Vitals</button>
            <button type="button" class="ai-quick-action" onclick="window.aiSendQuickMessage('Give a very short patient summary in bullet points')">Quick summary</button>
        </div>

        <div class="ai-chat-messages" id="aiChatMessages">
            <div class="ai-chat-message bot">
                <div class="ai-message-avatar">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M19 3H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3v4l4.8-4H19a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-8 11H9v-2H7v-2h2V8h2v2h2v2h-2v2z"/>
                    </svg>
                </div>
                <div class="ai-message-stack">
                    <div class="ai-message-content" id="msg-initial">
                        <p><strong>Ready.</strong> Ask about this patient in short form.</p>
                        <ul>
                            <li>Latest lab report</li>
                            <li>Current medicines</li>
                            <li>Vitals and BP</li>
                            <li>Brief patient summary</li>
                        </ul>
                    </div>
                    <div class="ai-message-meta">
                        <span class="ai-message-caption">AI assistant</span>
                        <button class="ai-message-tts" onclick="window.aiSpeakText(this, 'msg-initial')" title="Read Aloud">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="ai-chat-input-wrap">
            <div class="ai-chat-input-shell">
                <textarea
                    class="ai-chat-input"
                    id="aiChatInput"
                    placeholder="Ask for labs, medicines, vitals, or a short summary..."
                    rows="1"
                    onkeydown="aiHandleKeyPress(event)"
                ></textarea>
                <button class="ai-chat-send" id="aiChatSend" onclick="aiSendMessage()" title="Send">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>
            <div class="ai-chat-input-note">Replies are optimized for short, point-wise clinical reading.</div>
        </div>
    </div>
</div>

<script>
(function() {
    const PATIENT_ID = {{ isset($patient_data) ? $patient_data->id : 0 }};
    const AI_CHAT_URL = "{{ route('ai.chat') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";

    const chatContainer = document.getElementById('aiChatbotContainer');
    const chatToggle = document.getElementById('aiChatToggle');
    const chatWindow = document.getElementById('aiChatWindow');
    const chatClose = document.getElementById('aiChatClose');
    const chatMessages = document.getElementById('aiChatMessages');
    const chatInput = document.getElementById('aiChatInput');
    const chatSend = document.getElementById('aiChatSend');

    let isLoading = false;

    chatToggle.addEventListener('click', function() {
        chatContainer.classList.add('active');
        chatWindow.classList.add('active');
        chatInput.focus();
    });

    chatClose.addEventListener('click', function() {
        chatContainer.classList.remove('active');
        chatWindow.classList.remove('active');
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.ai-chatbot-container') && chatContainer.classList.contains('active')) {
            chatContainer.classList.remove('active');
            chatWindow.classList.remove('active');
        }
    });

    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    window.aiHandleKeyPress = function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            aiSendMessage();
        }
    };

    window.aiSpeakText = function(btnElement, messageId) {
        if (!('speechSynthesis' in window)) {
            alert("Your browser doesn't support Text to Speech.");
            return;
        }

        window.speechSynthesis.cancel();

        if (btnElement && btnElement.classList.contains('speaking')) {
            btnElement.classList.remove('speaking');
            return;
        }

        document.querySelectorAll('.ai-message-tts').forEach(function(el) {
            el.classList.remove('speaking');
        });

        const contentDiv = document.getElementById(messageId);
        if (!contentDiv) {
            return;
        }

        const utterance = new SpeechSynthesisUtterance(contentDiv.innerText || contentDiv.textContent || '');
        const voices = window.speechSynthesis.getVoices();

        if (voices.length > 0) {
            const selectedVoice =
                voices.find(function(voice) {
                    return voice.name.includes('Google') && voice.lang === 'en-IN';
                }) ||
                voices.find(function(voice) {
                    return voice.lang === 'en-IN' && (voice.name.includes('Female') || voice.voiceURI.includes('Female'));
                }) ||
                voices.find(function(voice) {
                    return voice.lang === 'en-IN';
                });

            if (selectedVoice) {
                utterance.voice = selectedVoice;
            }
        }

        utterance.pitch = 1.05;
        utterance.rate = 1;

        if (btnElement) {
            btnElement.classList.add('speaking');
            utterance.onend = function() {
                btnElement.classList.remove('speaking');
            };
            utterance.onerror = function() {
                btnElement.classList.remove('speaking');
            };
        }

        window.speechSynthesis.speak(utterance);
    };

    window.aiSendQuickMessage = function(message) {
        if (isLoading) {
            return;
        }
        chatInput.value = message;
        chatInput.style.height = 'auto';
        chatInput.style.height = Math.min(chatInput.scrollHeight, 120) + 'px';
        aiSendMessage();
    };

    window.aiSendMessage = function() {
        const message = chatInput.value.trim();
        if (!message || isLoading) {
            return;
        }

        addMessage(message, 'user');
        chatInput.value = '';
        chatInput.style.height = 'auto';

        showTypingIndicator();
        isLoading = true;
        chatSend.disabled = true;

        fetch(AI_CHAT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'text/event-stream'
            },
            body: JSON.stringify({
                patient_id: PATIENT_ID,
                message: message
            })
        })
        .then(async function(response) {
            hideTypingIndicator();

            if (!response.ok) {
                const errorData = await response.json().catch(function() { return {}; });
                addMessage(errorData.message || 'Sorry, I encountered an error. Please try again.', 'bot');
                return;
            }

            const messageId = 'msg-' + Date.now();
            const wrapper = createMessageShell(messageId, 'bot', true);
            const contentDiv = wrapper.querySelector('.ai-message-content');
            chatMessages.appendChild(wrapper);
            scrollToBottom();

            let fullText = '';
            const reader = response.body.getReader();
            const decoder = new TextDecoder('utf-8');
            let buffer = '';

            while (true) {
                const chunk = await reader.read();
                if (chunk.done) {
                    break;
                }

                buffer += decoder.decode(chunk.value, { stream: true });
                const blocks = buffer.split(/\n\n/);
                buffer = blocks.pop() || '';

                blocks.forEach(function(block) {
                    const line = block.trim();
                    if (!line.startsWith('data: ')) {
                        return;
                    }

                    const data = line.slice(6).trim();
                    if (data === '[DONE]') {
                        return;
                    }

                    try {
                        const parsed = JSON.parse(data);
                        if (parsed.text) {
                            fullText += parsed.text;
                            contentDiv.innerHTML = formatMessage(fullText);
                            scrollToBottom();
                        } else if (parsed.error) {
                            contentDiv.innerHTML = formatMessage('Error: ' + parsed.error);
                            scrollToBottom();
                        }
                    } catch (error) {
                        console.error('Error parsing AI response chunk:', error, data);
                    }
                });
            }
        })
        .catch(function(error) {
            console.error('AI Chat Error:', error);
            hideTypingIndicator();
            addMessage('Sorry, I could not connect to the AI service. Please try again.', 'bot');
        })
        .finally(function() {
            isLoading = false;
            chatSend.disabled = false;
            chatInput.focus();
        });
    };

    function createMessageShell(messageId, sender, includeTts) {
        const messageDiv = document.createElement('div');
        const avatarSvg = sender === 'bot'
            ? '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 3H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3v4l4.8-4H19a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-8 11H9v-2H7v-2h2V8h2v2h2v2h-2v2z"/></svg>'
            : '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';
        const label = sender === 'bot' ? 'AI assistant' : 'You';

        messageDiv.className = 'ai-chat-message ' + sender;
        messageDiv.innerHTML = `
            <div class="ai-message-avatar">${avatarSvg}</div>
            <div class="ai-message-stack">
                <div class="ai-message-content" id="${messageId}"></div>
                <div class="ai-message-meta">
                    <span class="ai-message-caption">${label}</span>
                    ${includeTts ? `<button class="ai-message-tts" onclick="window.aiSpeakText(this, '${messageId}')" title="Read Aloud">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                        </svg>
                    </button>` : ''}
                </div>
            </div>
        `;

        return messageDiv;
    }

    function addMessage(text, sender) {
        const messageId = 'msg-' + Date.now() + Math.floor(Math.random() * 1000);
        const wrapper = createMessageShell(messageId, sender, sender === 'bot');
        wrapper.querySelector('.ai-message-content').innerHTML = formatMessage(text);
        chatMessages.appendChild(wrapper);
        scrollToBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatMessage(text) {
        const normalized = (text || '').replace(/\r\n/g, '\n').trim();
        if (!normalized) {
            return '';
        }

        const lines = normalized.split('\n');
        const html = [];
        let listType = null;

        function closeList() {
            if (listType) {
                html.push('</' + listType + '>');
                listType = null;
            }
        }

        lines.forEach(function(rawLine) {
            const line = rawLine.trim();

            if (!line) {
                closeList();
                return;
            }

            const bulletMatch = line.match(/^[-*]\s+(.*)$/);
            const numberMatch = line.match(/^\d+\.\s+(.*)$/);

            if (bulletMatch) {
                if (listType !== 'ul') {
                    closeList();
                    html.push('<ul>');
                    listType = 'ul';
                }
                html.push('<li>' + inlineFormat(bulletMatch[1]) + '</li>');
                return;
            }

            if (numberMatch) {
                if (listType !== 'ol') {
                    closeList();
                    html.push('<ol>');
                    listType = 'ol';
                }
                html.push('<li>' + inlineFormat(numberMatch[1]) + '</li>');
                return;
            }

            closeList();
            html.push('<p>' + inlineFormat(line) + '</p>');
        });

        closeList();
        return html.join('');
    }

    function inlineFormat(text) {
        let formatted = escapeHtml(text);
        formatted = formatted.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        formatted = formatted.replace(/\*(.+?)\*/g, '<em>$1</em>');
        return formatted;
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'aiTypingIndicator';
        typingDiv.className = 'ai-chat-message bot';
        typingDiv.innerHTML = `
            <div class="ai-message-avatar">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M19 3H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3v4l4.8-4H19a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-8 11H9v-2H7v-2h2V8h2v2h2v2h-2v2z"/>
                </svg>
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

    function hideTypingIndicator() {
        const indicator = document.getElementById('aiTypingIndicator');
        if (indicator) {
            indicator.remove();
        }
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
})();
</script>
