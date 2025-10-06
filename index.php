<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {

    $message = trim($_POST['message']);
    
    if (!empty($message)) {
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'anonymous_forum';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $user_id = bin2hex(random_bytes(5));
            $adjectives = ['Mysterious', 'Silent', 'Brave', 'Clever', 'Witty', 'Quick', 'Calm', 'Eager'];
            $nouns = ['Panda', 'Tiger', 'Eagle', 'Dolphin', 'Wolf', 'Owl', 'Lion', 'Fox'];
            $username = $adjectives[array_rand($adjectives)] . $nouns[array_rand($nouns)] . rand(100, 999);
            
            $stmt = $pdo->prepare("INSERT INTO messages (user_id, username, message) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $username, $message]);
            
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit;
            
        } catch(PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ICCT Anonymous Forum</title>
<style>
:root {
    --primary-blue: #2563EB;
    --primary-red: #DC2626;
    --blue-light: #3B82F6;
    --red-light: #EF4444;
    --bg-warm: #FEF7ED;
    --bg-card: #FFFFFF;
    --text-dark: #1F2937;
    --text-muted: #6B7280;
    --border: #E5E7EB;
    --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

[data-theme="dark"] {
    --primary-blue: #3B82F6;
    --primary-red: #EF4444;
    --blue-light: #60A5FA;
    --red-light: #F87171;
    --bg-warm: #111827;
    --bg-card: #1F2937;
    --text-dark: #F9FAFB;
    --text-muted: #9CA3AF;
    --border: #374151;
    --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: var(--bg-warm);
    color: var(--text-dark);
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden;
    position: relative;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.animated-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
    background: linear-gradient(-45deg, #e0f2fe, #fef2f2, #dbeafe, #fef7ed);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    transition: background 0.5s ease;
}

[data-theme="dark"] .animated-bg {
    background: linear-gradient(-45deg, #0c4a6e, #7f1d1d, #1e3a8a, #431407);
    background-size: 400% 400%;
}

.bg-decoration {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

.circle {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
    transition: opacity 0.3s ease;
}

[data-theme="dark"] .circle {
    opacity: 0.05;
}

.circle-1 {
    width: 300px;
    height: 300px;
    top: -150px;
    right: -150px;
    background: var(--primary-blue);
    animation-delay: 0s;
}

.circle-2 {
    width: 200px;
    height: 200px;
    bottom: -100px;
    left: -100px;
    background: var(--primary-red);
    animation-delay: 2s;
}

.circle-3 {
    width: 150px;
    height: 150px;
    top: 50%;
    left: 10%;
    background: var(--primary-blue);
    animation-delay: 4s;
}

.app-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    max-width: 500px;
    margin: 0 auto;
    width: 100%;
}

.logo-section {
    text-align: center;
    margin-bottom: 2rem;
    animation: fadeInDown 0.8s ease-out;
}

.school-logo {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    margin: 0 auto 1rem;
    box-shadow: var(--shadow);
    border: 3px solid white;
    object-fit: cover;
}

[data-theme="dark"] .school-logo {
    border: 3px solid #374151;
}

.app-title {
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-red));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 0.5rem;
    animation: colorPulse 3s ease-in-out infinite;
}

.app-subtitle {
    font-size: 1rem;
    color: var(--text-muted);
    font-weight: 400;
}

.chat-card {
    width: 100%;
    background: var(--bg-card);
    border-radius: 20px;
    box-shadow: var(--shadow);
    overflow: hidden;
    animation: fadeInUp 0.8s ease-out 0.2s both;
    border: 1px solid var(--border);
    transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.chat-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-red));
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.chat-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: shimmer 3s infinite;
}

.chat-status {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    position: relative;
    z-index: 1;
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
    display: inline-block;
    animation: pulse 2s infinite;
}

#chat-container {
    height: 300px;
    padding: 1.5rem;
    overflow-y: auto;
    display: none;
    background: #fafafa;
    transition: background-color 0.3s ease;
}

[data-theme="dark"] #chat-container {
    background: #111827;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
    color: var(--text-muted);
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-title {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.empty-text {
    font-size: 0.9rem;
    max-width: 300px;
    line-height: 1.5;
}

.message {
    display: flex;
    margin: 15px 0;
    animation: fadeIn 0.3s ease-out;
}

.message.user {
    justify-content: flex-end;
}

.message-content {
    background: white;
    padding: 12px 16px;
    border-radius: 18px;
    max-width: 80%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border);
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

[data-theme="dark"] .message-content {
    background: #374151;
}

.message.user .message-content {
    background: linear-gradient(135deg, var(--blue-light), var(--primary-blue));
    color: white;
}

.chat-input-section {
    padding: 1.5rem;
    border-top: 1px solid var(--border);
    transition: border-color 0.3s ease;
}

#chat-form {
    display: flex;
    gap: 10px;
}

#chat-input {
    flex: 1;
    padding: 14px 18px;
    font-size: 1rem;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: white;
    color: var(--text-dark);
    outline: none;
    transition: all 0.3s ease;
}

[data-theme="dark"] #chat-input {
    background: #374151;
}

#chat-input:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

#chat-input::placeholder {
    color: var(--text-muted);
}

#chat-submit {
    padding: 14px 20px;
    font-size: 1rem;
    border: none;
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-red));
    color: white;
    cursor: pointer;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

#chat-submit::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

#chat-submit:hover::before {
    left: 100%;
}

#chat-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

#chat-submit:active {
    transform: translateY(0);
}

.send-icon {
    width: 20px;
    height: 20px;
    fill: currentColor;
}

.theme-toggle-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 100;
    animation: fadeInUp 0.8s ease-out 0.4s both;
}

.theme-toggle {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 50px;
    padding: 8px;
    display: flex;
    align-items: center;
    gap: 0;
    box-shadow: var(--shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

.theme-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.theme-btn {
    padding: 10px;
    border: none;
    border-radius: 50%;
    background: transparent;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

.theme-btn.active {
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-red));
    color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.theme-icon {
    width: 20px;
    height: 20px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

@keyframes colorPulse {
    0%, 100% { filter: hue-rotate(0deg); }
    50% { filter: hue-rotate(10deg); }
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

#chat-container::-webkit-scrollbar {
    width: 6px;
}

#chat-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 3px;
}

#chat-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-red));
    border-radius: 3px;
}

@media (max-width: 768px) {
    .app-container { padding: 1rem; }
    .app-title { font-size: 1.75rem; }
    .chat-card { border-radius: 16px; }
    .school-logo { width: 70px; height: 70px; }
    .theme-toggle-container { bottom: 15px; right: 15px; }
    .theme-toggle { padding: 6px; }
    .theme-btn { width: 36px; height: 36px; padding: 8px; }
    .theme-icon { width: 18px; height: 18px; }
}
</style>
</head>
<body>

<div class="animated-bg"></div>
<div class="bg-decoration">
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>
</div>

<div class="app-container">
    <div class="logo-section">
        <img src="icct.jpg" alt="ICCT Logo" class="school-logo">
        <h1 class="app-title">ICCT Anonymous Forum</h1>
        <p class="app-subtitle">Secure • Private • Welcoming</p>
    </div>

    <div class="chat-card">
        <div class="chat-header">
            <div class="chat-status">
                <span class="status-indicator"></span>
                <span>Connected Securely</span>
            </div>
        </div>
        <div id="chat-container">
            <div class="empty-state">
                <div class="empty-icon">💬</div>
                <h3 class="empty-title">Your Private Space</h3>
                <p class="empty-text">Share your thoughts freely. All messages are anonymous and secure.</p>
            </div>
        </div>

        <div class="chat-input-section">
            <form id="chat-form" method="POST">
                <input type="text" id="chat-input" name="message" placeholder="Type a message..." autocomplete="off" required />
                <button type="submit" id="chat-submit">
                    <svg class="send-icon" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="theme-toggle-container">
    <div class="theme-toggle">
        <button class="theme-btn active" id="light-mode-btn" title="Light Mode">
            <svg class="theme-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 17C14.7614 17 17 14.7614 17 12C17 9.23858 14.7614 7 12 7C9.23858 7 7 9.23858 7 12C7 14.7614 9.23858 17 12 17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 1V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 21V23" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4.22 4.22L5.64 5.64" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M18.36 18.36L19.78 19.78" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M1 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12H23" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4.22 19.78L5.64 18.36" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M18.36 5.64L19.78 4.22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <button class="theme-btn" id="dark-mode-btn" title="Dark Mode">
            <svg class="theme-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 12.79C20.8427 14.4922 20.2039 16.1144 19.1582 17.4668C18.1126 18.8192 16.7035 19.8458 15.0957 20.4265C13.4879 21.0073 11.748 21.1181 10.0795 20.7461C8.41104 20.3741 6.88299 19.5345 5.67422 18.3258C4.46545 17.117 3.62593 15.589 3.2539 13.9205C2.88187 12.252 2.99274 10.5121 3.57348 8.9043C4.15422 7.29651 5.18085 5.88737 6.53323 4.84175C7.88562 3.79614 9.50782 3.15731 11.21 3C10.2134 4.34827 9.73375 6.00945 9.85849 7.68141C9.98324 9.35338 10.7039 10.9251 11.8894 12.1106C13.0749 13.2961 14.6466 14.0168 16.3186 14.1415C17.9906 14.2663 19.6517 13.7866 21 12.79Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</div>

<script>
const chatContainer = document.getElementById('chat-container');
const chatForm = document.getElementById('chat-form');
const chatInput = document.getElementById('chat-input');
const lightModeBtn = document.getElementById('light-mode-btn');
const darkModeBtn = document.getElementById('dark-mode-btn');
const body = document.body;

function setTheme(theme) {
    body.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    
    if (theme === 'light') {
        lightModeBtn.classList.add('active');
        darkModeBtn.classList.remove('active');
    } else {
        darkModeBtn.classList.add('active');
        lightModeBtn.classList.remove('active');
    }
}

const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    setTheme(savedTheme);
} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    setTheme('dark');
}


lightModeBtn.addEventListener('click', () => setTheme('light'));
darkModeBtn.addEventListener('click', () => setTheme('dark'));

chatForm.addEventListener('submit', async e => {
    e.preventDefault();
    const message = chatInput.value.trim();
    if (!message) return;

    if (chatContainer.style.display === 'none') {
        chatContainer.style.display = 'block';
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            emptyState.style.display = 'none';
        }
    }


    const sendingDiv = document.createElement('div');
    sendingDiv.className = 'message user';
    const sendingContent = document.createElement('div');
    sendingContent.className = 'message-content';
    sendingContent.textContent = 'Sending anonymously...';
    sendingDiv.appendChild(sendingContent);
    chatContainer.appendChild(sendingDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    try {
        const formData = new FormData();
        formData.append('message', message);
        
        const response = await fetch('', {  
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        
        sendingDiv.remove();
        
        if (result.status === 'success') {
            const successDiv = document.createElement('div');
            successDiv.className = 'message user';
            const successContent = document.createElement('div');
            successContent.className = 'message-content';
            successContent.textContent = 'Message sent anonymously ✓';
            successDiv.appendChild(successContent);
            chatContainer.appendChild(successDiv);
            
            chatInput.value = '';
            chatContainer.scrollTop = chatContainer.scrollHeight;
            
            setTimeout(() => {
                successDiv.remove();
            }, 2000);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        sendingDiv.remove();
        console.error('Error posting message:', error);
        alert('Error posting message');
    }
});

chatInput.focus();
chatInput.addEventListener('focus', function() {
    this.parentElement.style.transform = 'translateY(-2px)';
});

chatInput.addEventListener('blur', function() {
    this.parentElement.style.transform = 'translateY(0)';
});
</script>

</body>
</html>