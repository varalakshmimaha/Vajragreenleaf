<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Successful - Vajra Green Leaf</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body { 
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #052d00 0%, #063d00 50%, #052d00 100%);
            background-attachment: fixed;
            overflow-x: hidden;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .glow-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .glow-orb-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 50%;
            height: 50%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
            filter: blur(80px);
            animation: pulse 4s ease-in-out infinite;
        }
        .glow-orb-2 {
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 50%;
            height: 50%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, transparent 70%);
            filter: blur(80px);
            animation: pulse 4s ease-in-out infinite 2s;
        }
        .glow-center {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at center, rgba(16, 185, 129, 0.15) 0%, transparent 50%);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 0.8; }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        .sponsor-id-display {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        }
        .copy-btn:hover {
            transform: scale(1.05);
        }
        .btn-primary {
            background: linear-gradient(to right, #10b981, #059669);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #34d399, #10b981);
            transform: scale(1.02);
        }
        .btn-primary:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">

    <!-- Background Glows -->
    <div class="glow-bg">
        <div class="glow-center"></div>
        <div class="glow-orb-1"></div>
        <div class="glow-orb-2"></div>
    </div>

    <!-- Main Content -->
    <div style="position: relative; z-index: 10; width: 100%; max-width: 42rem;">
        <div class="glass-card" style="border-radius: 1.5rem; padding: 3rem; text-align: center; border: 1px solid rgba(16, 185, 129, 0.3);">
            
            <!-- Success Icon -->
            <div class="float-animation" style="margin: 0 auto 2rem; width: 7rem; height: 7rem; background: linear-gradient(135deg, #34d399, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);">
                <svg style="width: 3.5rem; height: 3.5rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Title -->
            <h2 style="font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 1rem; letter-spacing: -0.025em;">
                Registration Successful!
            </h2>
            <div style="margin-bottom: 2.5rem;">
                <p id="userNameDisplay" style="font-size: 1.25rem; color: #34d399; font-weight: 700; margin-bottom: 0.5rem;">
                    Welcome!
                </p>
                <p style="font-size: 1.125rem; color: #d1d5db;">
                    Your account has been created successfully
                </p>
            </div>

            <!-- Sponsor ID Display - Premium Design -->
            <div style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05)); border: 2px solid rgba(16, 185, 129, 0.3); border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);">
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, #34d399, #10b981); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-id-badge" style="color: white; font-size: 1.125rem;"></i>
                    </div>
                    <span style="font-size: 0.875rem; color: #6ee7b7; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;">
                        Your Unique Sponsor ID
                    </span>
                </div>
                
                <div class="sponsor-id-display" style="margin-bottom: 1rem;">
                    <span id="userIdDisplay" style="font-size: 3rem; font-family: 'Courier New', monospace; font-weight: 900; color: #111827; letter-spacing: -0.025em; display: block;">
                        LOADING...
                    </span>
                </div>

                <button onclick="copySponsorId()" class="copy-btn" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-copy"></i>
                    <span>Copy Sponsor ID</span>
                </button>
            </div>

            <!-- Important Note -->
            <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 0.75rem; padding: 1rem; margin-bottom: 2rem;">
                <p style="font-size: 0.875rem; color: #fcd34d; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <span>Save this Sponsor ID - You'll need it for referrals</span>
                </p>
            </div>

            <!-- CTA Button -->
            <a href="{{ route('home') }}" class="btn-primary" style="display: block; width: 100%; border-radius: 1rem; padding: 1.25rem 1.5rem; font-size: 1.125rem; font-weight: 900; color: white; text-decoration: none; margin-bottom: 1rem; transition: all 0.3s ease;">
                <i class="fas fa-home" style="margin-right: 0.5rem;"></i>
                Go to Home Page
            </a>
            
            <p style="font-size: 0.875rem; color: #9ca3af;">
                Redirecting automatically in <span id="countdown" style="color: #34d399; font-weight: 700;">8</span> seconds...
            </p>
        </div>
    </div>

    <script>
        // Get Info from URL
        const params = new URLSearchParams(window.location.search);
        const userId = params.get('id');
        const userName = params.get('name');
        
        const userIdEl = document.getElementById('userIdDisplay');
        const userNameEl = document.getElementById('userNameDisplay');
        
        if (userId) {
            userIdEl.textContent = userId;
        } else {
            userIdEl.textContent = 'N/A';
        }
        
        if (userName) {
            userNameEl.textContent = "Welcome, " + userName + "!";
        }

        // Copy function
        function copySponsorId() {
            const id = userIdEl.textContent;
            navigator.clipboard.writeText(id).then(() => {
                alert('✅ Sponsor ID copied to clipboard!\n\n' + id);
            }).catch(err => {
                console.error('Copy failed:', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = id;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    alert('✅ Sponsor ID copied to clipboard!\n\n' + id);
                } catch (err) {
                    alert('❌ Failed to copy. Please copy manually: ' + id);
                }
                document.body.removeChild(textArea);
            });
        }

        // Auto Redirect with longer timer
        let seconds = 8;
        const countdownEl = document.getElementById('countdown');
        const timer = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = "{{ route('home') }}";
            }
        }, 1000);
    </script>
</body>
</html>
