<!-- Chat Toggle Button (shows when chat is hidden) -->
<div id="bridget-toggle-btn" class="position-fixed d-flex align-items-center justify-content-center" 
     style="bottom: 20px; right: 20px; width: 60px; height: 60px; 
            background: linear-gradient(135deg, #696cff 0%, #5a5fda 100%); 
            border-radius: 50%; cursor: pointer; box-shadow: 0 8px 25px rgba(105, 108, 255, 0.4); 
            z-index: 9998; transition: all 0.3s ease; display: flex;">
    <i class="ti ti-message-circle text-white" style="font-size: 28px;"></i>
    <!-- Notification badge (optional) -->
    <span id="bridget-notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
          style="display: none;">1</span>
</div>

<!-- Floating Chatbot -->
<div id="bridget-chat" class="card position-fixed shadow-lg" 
     style="bottom: 20px; right: 20px; width: 360px; height: 480px; 
            border: none; border-radius: 16px; z-index: 9999; 
            transition: all 0.3s ease; overflow: hidden; display: none;">
    
    <!-- Header -->
    <div id="bridget-header" class="card-header d-flex justify-content-between align-items-center border-0" 
         style="background: linear-gradient(135deg, #696cff 0%, #5a5fda 100%); 
                color: white; padding: 16px 20px; cursor: pointer;">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-sm me-2">
                <div class="avatar-initial rounded-circle bg-white bg-opacity-20">
                    <i class="ti ti-robot text-white"></i>
                </div>
            </div>
            <div>
                <h6 class="mb-0 text-white">Bridget Assistant</h6>
                <small class="text-white-50">Online</small>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <button id="bridget-minimize" class="btn btn-sm p-1 me-2" 
                    style="color: white; background: rgba(255,255,255,0.1); border: none; border-radius: 6px;"
                    title="Minimize">
                <i class="ti ti-minus" style="font-size: 16px;"></i>
            </button>
            <button id="bridget-close" class="btn btn-sm p-1" 
                    style="color: white; background: rgba(255,255,255,0.1); border: none; border-radius: 6px;"
                    title="Close">
                <i class="ti ti-x" style="font-size: 16px;"></i>
            </button>
        </div>
    </div>

    <!-- Chat messages container -->
    <div id="bridget-messages" class="flex-grow-1 p-3 overflow-auto" 
         style="background: #f8f9fa; display: flex; flex-direction: column; height: calc(100% - 140px);">
        <!-- Welcome message will be added by JavaScript -->
    </div>

    <!-- Typing indicator (hidden by default) -->
    <div id="bridget-typing" class="px-3 pb-2" style="display: none;">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-xs me-2">
                <div class="avatar-initial rounded-circle bg-primary">
                    <i class="ti ti-robot text-white" style="font-size: 12px;"></i>
                </div>
            </div>
            <div class="bg-white rounded px-3 py-2">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Input area -->
    <div class="card-footer border-0 p-3" style="background: white;">
        <div class="input-group">
            <input type="text" id="bridget-input" class="form-control border-0" 
                   placeholder="Type your message..." style="border-radius: 25px 0 0 25px; padding: 12px 16px;">
            <button id="bridget-send" class="btn btn-primary border-0" 
                    style="border-radius: 0 25px 25px 0; padding: 12px 16px; min-width: 50px;">
                <i class="ti ti-send" style="font-size: 16px;"></i>
            </button>
        </div>
        <div class="d-flex justify-content-center mt-2">
            <small class="text-muted">Powered by AI</small>
        </div>
    </div>
</div>

<style>
/* Typing indicator animation */
.typing-indicator {
    display: flex;
    align-items: center;
    gap: 2px;
}

.typing-indicator span {
    height: 4px;
    width: 4px;
    background-color: #696cff;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-indicator span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Hover effects */
#bridget-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 12px 35px rgba(105, 108, 255, 0.6);
}

#bridget-send:hover {
    background: #5a5fda !important;
    transform: translateY(-1px);
}

#bridget-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
}

/* Scrollbar styling */
#bridget-messages::-webkit-scrollbar {
    width: 4px;
}

#bridget-messages::-webkit-scrollbar-track {
    background: transparent;
}

#bridget-messages::-webkit-scrollbar-thumb {
    background: rgba(105, 108, 255, 0.3);
    border-radius: 2px;
}

#bridget-messages::-webkit-scrollbar-thumb:hover {
    background: rgba(105, 108, 255, 0.5);
}

/* Message bubbles styling */
.message-bubble {
    animation: fadeInUp 0.3s ease;
    margin-bottom: 12px;
    max-width: 80%;
    word-wrap: break-word;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chat = document.getElementById('bridget-chat');
    const toggleBtn = document.getElementById('bridget-toggle-btn');
    const header = document.getElementById('bridget-header');
    const closeBtn = document.getElementById('bridget-close');
    const minimizeBtn = document.getElementById('bridget-minimize');
    const messagesContainer = document.getElementById('bridget-messages');
    const input = document.getElementById('bridget-input');
    const sendBtn = document.getElementById('bridget-send');
    const typingIndicator = document.getElementById('bridget-typing');
    const notificationBadge = document.getElementById('bridget-notification-badge');
    

    // Initialize state
    let isOpen = false;
    let isMinimized = false;
    let step = 0;
    let initialQuestionLoaded = false;

    // Show toggle button when chat is closed
    toggleBtn.addEventListener('click', () => {
        showChat();
        // Load initial question only when chat is first opened
        if (!initialQuestionLoaded) {
            loadInitialQuestion();
        }
    });

    // Minimize chat (collapse to header only)
    minimizeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMinimize();
    });

    // Close chat completely
    closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        hideChat();
    });

    // Header click to toggle minimize
    header.addEventListener('click', () => {
        if (!isMinimized) {
            toggleMinimize();
        }
    });

    function showChat() {
        chat.style.display = 'block';
        toggleBtn.style.display = 'none';
        isOpen = true;
        isMinimized = false;
        chat.style.height = '480px';
        messagesContainer.style.display = 'flex';
        input.parentElement.parentElement.style.display = 'block';
        notificationBadge.style.display = 'none';
    }

    function hideChat() {
        chat.style.display = 'none';
        toggleBtn.style.display = 'flex';
        isOpen = false;
        isMinimized = false;
    }

    function toggleMinimize() {
        isMinimized = !isMinimized;
        if (isMinimized) {
            chat.style.height = '72px';
            messagesContainer.style.display = 'none';
            input.parentElement.parentElement.style.display = 'none';
        } else {
            chat.style.height = '480px';
            messagesContainer.style.display = 'flex';
            input.parentElement.parentElement.style.display = 'block';
        }
    }

    // Show typing indicator
    function showTyping() {
        typingIndicator.style.display = 'block';
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Hide typing indicator
    function hideTyping() {
        typingIndicator.style.display = 'none';
    }

    // Format text with line breaks and emojis
    function formatText(text) {
        return text
            .replace(/\n/g, '<br>')
            .replace(/(\d+\.)/g, '<strong>$1</strong>')
            .replace(/(•)/g, '<span style="color: #696cff;">$1</span>');
    }

    // Append chat bubbles with improved styling
    function appendBridgetBubble(text, type = 'bot') {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message-bubble d-flex align-items-start';
        messageDiv.style.marginBottom = '12px';
        
        const formattedText = formatText(text);
        
        if (type === 'bot') {
            messageDiv.innerHTML = `
                <div class="avatar avatar-xs me-2 flex-shrink-0">
                    <div class="avatar-initial rounded-circle bg-primary">
                        <i class="ti ti-robot text-white" style="font-size: 12px;"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="bg-white rounded p-3 shadow-sm" style="border-radius: 12px 12px 12px 4px; line-height: 1.5;">
                        <div style="font-size: 13px; color: black;">${formattedText}</div>
                    </div>
                    <small class="text-muted ms-2" style="font-size: 10px;">${getCurrentTime()}</small>
                </div>
            `;
        } else {
            messageDiv.style.justifyContent = 'flex-end';
            messageDiv.innerHTML = `
                <div class="text-end flex-grow-1">
                    <div class="d-inline-block bg-primary text-white rounded p-3 shadow-sm" 
                         style="border-radius: 12px 12px 4px 12px; max-width: 80%; line-height: 1.5;">
                        <div style="font-size: 13px;">${formattedText}</div>
                    </div>
                    <div><small class="text-muted me-2" style="font-size: 10px;">${getCurrentTime()}</small></div>
                </div>
            `;
        }
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function getCurrentTime() {
        return new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }

    // Load initial question (only called when chat is first opened)
    function loadInitialQuestion() {
        if (initialQuestionLoaded) return;
        
        initialQuestionLoaded = true;
        showTyping();

        fetch('{{ route("chatbot.start") }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            hideTyping();
            step = data.step;
            appendBridgetBubble(data.question, 'bot');
        })
        .catch(error => {
            console.error('Error fetching initial question:', error);
            hideTyping();
            appendBridgetBubble('Sorry, I encountered an error. Please try again.', 'bot');
        });
    }

    // Send answer
    function sendMessage() {
        const answer = input.value.trim();
        if (!answer) return;

        appendBridgetBubble(answer, 'user');
        input.value = '';
        sendBtn.disabled = true;
        showTyping();

        fetch('{{ route("chatbot.next") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                step: step, 
                answer: answer 
            })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            hideTyping();
            sendBtn.disabled = false;
            
            if (data.finished) {
                appendBridgetBubble("Thanks! Here's a summary:", 'bot');
                setTimeout(() => {
                    for (const [s, a] of Object.entries(data.answers)) {
                        appendBridgetBubble(`Q${s}: ${a}`, 'bot');
                    }
                }, 500);
            } else {
                step = data.step;
                appendBridgetBubble(data.question, 'bot');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            hideTyping();
            sendBtn.disabled = false;
            appendBridgetBubble('Sorry, I encountered an error. Please try again.', 'bot');
        });
    }

    sendBtn.addEventListener('click', sendMessage);

    // Enter key sends message
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Input focus handling
    input.addEventListener('focus', function() {
        if (isMinimized) {
            toggleMinimize();
        }
    });

    // Simulate notification (you can trigger this from your backend)
    function showNotification() {
        if (!isOpen) {
            notificationBadge.style.display = 'block';
            toggleBtn.style.animation = 'bounce 0.6s infinite';
        }
    }

    // Remove bounce animation when chat opens
    function removeNotificationAnimation() {
        toggleBtn.style.animation = '';
    }
});
</script>