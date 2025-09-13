<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TutorMatch</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/3.3.0/tailwind.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --glass-bg: rgba(255, 255, 255, 0.12);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            background: var(--primary-gradient);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 118, 117, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(79, 172, 254, 0.2) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        .login-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: slide 25s linear infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }

        @keyframes slide {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(-20px) translateY(-20px); }
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            position: relative;
            z-index: 1;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
        }

        .login-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .login-title {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 0.5rem;
            position: relative;
            letter-spacing: -0.02em;
        }

        .login-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            margin-bottom: 2.5rem;
            font-weight: 400;
        }

        .login-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--accent-gradient);
            border-radius: 2px;
            box-shadow: 0 2px 8px rgba(79, 172, 254, 0.4);
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            padding: 16px 50px 16px 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            backdrop-filter: blur(10px);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(79, 172, 254, 0.6);
            background: rgba(255, 255, 255, 0.1);
            box-shadow:
                0 0 0 4px rgba(79, 172, 254, 0.1),
                0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .form-input.error {
            border-color: rgba(239, 68, 68, 0.6);
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .form-label {
            position: absolute;
            left: 20px;
            top: 16px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            padding: 0 8px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .form-input:focus + .form-label,
        .form-input:not(:placeholder-shown) + .form-label {
            top: -12px;
            font-size: 12px;
            color: rgba(79, 172, 254, 0.9);
            font-weight: 600;
            background: rgba(255, 255, 255, 0.15);
        }

        .error-message {
            color: #fca5a5;
            font-size: 14px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(239, 68, 68, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 2rem 0;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .checkbox {
            width: 20px;
            height: 20px;
            accent-color: #4facfe;
            cursor: pointer;
            border-radius: 4px;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: var(--accent-gradient);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s ease;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(79, 172, 254, 0.4);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .register-link a {
            color: #4facfe;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .register-link a:hover {
            color: #00f2fe;
            background: rgba(79, 172, 254, 0.1);
            text-decoration: underline;
        }

        .icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            z-index: 1;
        }

        .form-input:focus ~ .icon {
            color: #4facfe;
            transform: translateY(-50%) scale(1.1);
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .login-container {
                padding: 0.5rem;
            }

            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 20px;
                margin: 0;
                max-width: none;
            }

            .login-title {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }

            .login-subtitle {
                font-size: 0.9rem;
                margin-bottom: 2rem;
            }

            .form-input {
                padding: 14px 45px 14px 16px;
                font-size: 16px; /* Prevents zoom on iOS */
            }

            .form-label {
                left: 16px;
                top: 14px;
            }

            .form-input:focus + .form-label,
            .form-input:not(:placeholder-shown) + .form-label {
                top: -10px;
            }

            .icon {
                right: 12px;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem 1rem;
            }

            .login-title {
                font-size: 1.75rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }
        }

        /* Landscape mobile orientation */
        @media (max-height: 600px) and (orientation: landscape) {
            .login-container {
                padding: 1rem 0;
            }

            .login-card {
                max-height: 90vh;
                overflow-y: auto;
                padding: 1.5rem;
            }

            .login-title {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .login-subtitle {
                margin-bottom: 1.5rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .remember-checkbox {
                margin: 1rem 0;
            }
        }

        /* High-resolution displays */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .login-card {
                backdrop-filter: blur(25px);
            }
        }

        /* Animation for form validation */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .form-input.error {
            animation: shake 0.6s ease-in-out;
        }

        /* Loading state for submit button */
        .submit-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .submit-btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Focus improvements for accessibility */
        .form-input:focus,
        .checkbox:focus,
        .submit-btn:focus,
        .register-link a:focus {
            outline: 2px solid #4facfe;
            outline-offset: 2px;
        }

        /* Dark mode support (if needed) */
        @media (prefers-color-scheme: dark) {
            .login-card {
                background: rgba(0, 0, 0, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <main class="max-w-7xl mx-auto">
        <div class="login-container">
            <div class="login-card">
                <h2 class="login-title">Connexion</h2>
                <p class="login-subtitle">Accédez à votre espace personnel</p>

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <input id="email"
                               name="email"
                               type="email"
                               required
                               placeholder=" "
                               class="form-input @error('email') error @enderror"
                               value="{{ old('email') }}"
                               autocomplete="email">
                        <label for="email" class="form-label">Adresse email</label>
                        <div class="icon">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="password"
                               name="password"
                               type="password"
                               required
                               placeholder=" "
                               class="form-input @error('password') error @enderror"
                               autocomplete="current-password">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="icon">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="remember-checkbox">
                        <input id="remember" name="remember" type="checkbox" class="checkbox">
                        <label for="remember">Se souvenir de moi</label>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        Se connecter
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Amélioration de l'expérience utilisateur
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');

            // Animation de soumission
            form.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.textContent = '';
            });

            // Gestion des labels flottants
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.classList.remove('has-value');
                    } else {
                        this.classList.add('has-value');
                    }
                });
            });

            // Animation d'entrée de la carte
            setTimeout(() => {
                document.querySelector('.login-card').style.opacity = '1';
                document.querySelector('.login-card').style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
