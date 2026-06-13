<?php if (!isset($error)) $error = ''; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIA PKL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #2563EB;
            --primary-dark: #003CCF;
            --primary-light: #60A5FA;
            --success: #10B981;
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --border: #E2E8F0;
            --text-primary: #0F172A;
            --text-secondary: #64748B;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .split-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== LEFT PANEL ===== */
        .panel-left {
            flex: 0 0 50%;
            background: linear-gradient(135deg, #003CCF 0%, #2563EB 50%, #60A5FA 100%);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            padding: 40px 48px;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .blob-1 {
            position: absolute;
            top: 15%;
            left: 60%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            pointer-events: none;
        }

        .blob-2 {
            position: absolute;
            bottom: 30%;
            right: 10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(30px);
            pointer-events: none;
        }

        .dot-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        .panel-left .brand {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .panel-left .brand .brand-icon {
            width: 52px;
            height: 52px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 26px;
            border: 1px solid rgba(255,255,255,0.12);
        }

        .panel-left .brand .brand-name {
            font-size: 48px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -1px;
        }

        .panel-left .brand .brand-sub {
            font-size: 20px;
            color: rgba(255,255,255,0.85);
            margin-top: -4px;
        }

        .panel-left .hero-content {
            position: relative;
            z-index: 2;
            margin-top: 60px;
            flex: 1;
        }

        .panel-left .hero-content .headline {
            font-size: 72px;
            font-weight: 800;
            color: #fff;
            line-height: 1.05;
            letter-spacing: -2px;
        }

        .panel-left .hero-content .headline-sub {
            font-size: 28px;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
            margin-top: 16px;
            line-height: 1.3;
        }

        .panel-left .bottom-area {
            position: relative;
            z-index: 2;
            margin-top: auto;
            padding-top: 20px;
        }

        .panel-left .bottom-area .stat-row {
            display: flex;
            gap: 16px;
        }

        .panel-left .bottom-area .stat-row .glass-stat {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
            transition: transform 0.2s;
        }

        .panel-left .bottom-area .stat-row .glass-stat:hover {
            transform: translateY(-4px);
        }

        .panel-left .bottom-area .stat-row .glass-stat .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .panel-left .bottom-area .stat-row .glass-stat .stat-icon.blue { background: rgba(37,99,235,0.3); color: #93C5FD; }
        .panel-left .bottom-area .stat-row .glass-stat .stat-icon.green { background: rgba(16,185,129,0.3); color: #6EE7B7; }
        .panel-left .bottom-area .stat-row .glass-stat .stat-icon.orange { background: rgba(245,158,11,0.3); color: #FCD34D; }

        .panel-left .bottom-area .stat-row .glass-stat .stat-info .stat-num {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            display: block;
        }

        .panel-left .bottom-area .stat-row .glass-stat .stat-info .stat-label {
            font-size: 12px;
            color: rgba(255,255,255,0.75);
            font-weight: 500;
            display: block;
            margin-top: 2px;
        }

        /* ===== RIGHT PANEL ===== */
        .panel-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 40px 16px;
            background: var(--bg);
        }

        .login-card {
            width: 100%;
            max-width: 600px;
            background: var(--card);
            border-radius: 32px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.08);
            padding: 48px 40px;
        }

        .login-card .login-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .login-card .login-header .login-logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-card .login-header .login-sys-name {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .login-card .login-header .login-sys-sub {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .login-card .welcome {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-card .welcome h1 {
            font-size: 56px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -1px;
        }

        .login-card .welcome p {
            font-size: 16px;
            color: var(--text-secondary);
            margin-top: 6px;
        }

        .login-card .error-msg {
            display: <?= $error ? 'flex' : 'none' ?>;
            align-items: center;
            gap: 8px;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            color: #DC2626;
            margin-bottom: 20px;
        }

        .login-card .error-msg i { font-size: 16px; flex-shrink: 0; }

        .login-card .input-group {
            margin-bottom: 18px;
        }

        .login-card .input-group .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .login-card .input-group .input-wrap .input-icon {
            position: absolute;
            left: 18px;
            color: var(--text-secondary);
            font-size: 20px;
            pointer-events: none;
            z-index: 1;
        }

        .login-card .input-group .input-wrap input {
            width: 100%;
            height: 64px;
            padding: 0 18px 0 52px;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            font-size: 15px;
            font-family: inherit;
            background: var(--card);
            transition: all 0.2s;
            outline: none;
            color: var(--text-primary);
        }

        .login-card .input-group .input-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
        }

        .login-card .input-group .input-wrap input::placeholder {
            color: #94A3B8;
        }

        .login-card .input-group .input-wrap .toggle-pass {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 6px;
            font-size: 20px;
            z-index: 1;
            display: flex;
        }

        .login-card .input-group .input-wrap .toggle-pass:hover {
            color: var(--text-primary);
        }

        .login-card .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .login-card .form-options .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-secondary);
        }

        .login-card .form-options .remember input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            border-radius: 4px;
            cursor: pointer;
        }

        .login-card .form-options .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-card .form-options .forgot-link:hover {
            text-decoration: underline;
        }

        .login-card .btn-login {
            width: 100%;
            height: 64px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), #0048FF);
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
        }

        .login-card .btn-login:hover:not(:disabled) {
            box-shadow: 0 8px 28px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        .login-card .btn-login:active:not(:disabled) {
            transform: translateY(0);
        }

        .login-card .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .login-card .btn-login .spinner {
            display: none;
            width: 22px;
            height: 22px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .login-card .btn-login.loading .spinner { display: inline-block; }
        .login-card .btn-login.loading .btn-text { display: none; }

        @keyframes spin { to { transform: rotate(360deg); } }

        .login-card .trust-row {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-top: 32px;
            flex-wrap: wrap;
        }

        .login-card .trust-row .trust-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .login-card .trust-row .trust-item .trust-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .login-card .trust-row .trust-item .trust-circle.blue {
            background: #EFF6FF;
            color: var(--primary);
        }

        .login-card .trust-row .trust-item .trust-circle.green {
            background: #ECFDF5;
            color: var(--success);
        }

        .login-card .trust-row .trust-item .trust-circle.purple {
            background: #F5F3FF;
            color: #7C3AED;
        }

        .login-footer {
            text-align: center;
            padding: 16px 20px 20px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .login-footer span {
            display: block;
            margin-top: 2px;
            font-size: 12px;
            opacity: 0.7;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .panel-left .hero-content .headline {
                font-size: 48px;
            }
            .panel-left .hero-content .headline-sub {
                font-size: 20px;
            }
            .panel-left .brand .brand-name {
                font-size: 32px;
            }
            .login-card .welcome h1 {
                font-size: 40px;
            }
        }

        @media (max-width: 768px) {
            .split-layout {
                flex-direction: column;
            }
            .panel-left {
                flex: none;
                padding: 32px 24px;
                min-height: auto;
            }
            .panel-left .hero-content {
                margin-top: 32px;
            }
            .panel-left .hero-content .headline {
                font-size: 36px;
            }
            .panel-left .hero-content .headline-sub {
                font-size: 16px;
            }
            .panel-left .bottom-area .stat-row {
                flex-direction: column;
                gap: 10px;
            }
            .panel-left .bottom-area .stat-row .glass-stat {
                padding: 14px 18px;
            }
            .panel-right {
                padding: 24px 16px;
            }
            .login-card {
                padding: 32px 24px;
                border-radius: 24px;
                max-width: 100%;
            }
            .login-card .welcome h1 {
                font-size: 32px;
            }
            .login-card .trust-row {
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .panel-left {
                padding: 20px 16px;
            }
            .panel-left .brand .brand-name {
                font-size: 24px;
            }
            .panel-left .hero-content .headline {
                font-size: 28px;
            }
            .login-card {
                padding: 24px 16px;
            }
            .login-card .welcome h1 {
                font-size: 28px;
            }
            .login-card .input-group .input-wrap input {
                height: 52px;
                font-size: 14px;
            }
            .login-card .btn-login {
                height: 52px;
                font-size: 15px;
            }
            .login-card .trust-row .trust-item {
                font-size: 10px;
            }
            .login-card .trust-row .trust-item .trust-circle {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
        }

        .animate-fade-up {
            animation: fadeUp 0.6s ease forwards;
            opacity: 0;
        }
        .animate-fade-up-d1 { animation-delay: 0.1s; }
        .animate-fade-up-d2 { animation-delay: 0.2s; }
        .animate-fade-up-d3 { animation-delay: 0.3s; }
        .animate-fade-up-d4 { animation-delay: 0.4s; }
        .animate-fade-up-d5 { animation-delay: 0.5s; }
        .animate-fade-up-d6 { animation-delay: 0.6s; }
        .animate-fade-up-d7 { animation-delay: 0.7s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="split-layout">
        <!-- LEFT PANEL -->
        <div class="panel-left">
            <div class="dot-grid"></div>
            <div class="blob-1"></div>
            <div class="blob-2"></div>

            <div class="brand animate-fade-up animate-fade-up-d1">
                <div class="brand-icon"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div class="brand-name">SIA PKL</div>
                    <div class="brand-sub">Sistem Informasi Absensi PKL</div>
                </div>
            </div>

            <div class="hero-content">
                <div class="headline animate-fade-up animate-fade-up-d2">
                    Sistem Informasi<br>Absensi PKL
                </div>
                <div class="headline-sub animate-fade-up animate-fade-up-d3">
                    Absensi Real-Time dengan<br>Verifikasi Lokasi dan Selfie
                </div>
            </div>

            <div class="bottom-area animate-fade-up animate-fade-up-d4">
                <div class="stat-row">
                    <div class="glass-stat">
                        <div class="stat-icon blue"><i class="bi bi-people"></i></div>
                        <div class="stat-info">
                            <span class="stat-num">500+</span>
                            <span class="stat-label">Siswa Aktif</span>
                        </div>
                    </div>
                    <div class="glass-stat">
                        <div class="stat-icon green"><i class="bi bi-building"></i></div>
                        <div class="stat-info">
                            <span class="stat-num">50+</span>
                            <span class="stat-label">Perusahaan Mitra</span>
                        </div>
                    </div>
                    <div class="glass-stat">
                        <div class="stat-icon orange"><i class="bi bi-graph-up"></i></div>
                        <div class="stat-info">
                            <span class="stat-num">98%</span>
                            <span class="stat-label">Kehadiran Tercatat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="panel-right">
            <div class="login-card animate-fade-up animate-fade-up-d1">
                <div class="login-header">
                    <div class="login-logo"><i class="bi bi-shield-check"></i></div>
                    <div class="login-sys-name">SIA PKL</div>
                    <div class="login-sys-sub">Sistem Informasi Absensi PKL</div>
                </div>

                <div class="welcome animate-fade-up animate-fade-up-d2">
                    <h1>Selamat Datang 👋</h1>
                    <p>Masuk untuk mengakses sistem absensi PKL</p>
                </div>

                <div class="error-msg" id="errorMsg">
                    <i class="bi bi-exclamation-circle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>

                <form method="POST" id="loginForm">
                    <div class="input-group animate-fade-up animate-fade-up-d3">
                        <div class="input-wrap">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" id="username" name="username" placeholder="Masukkan Username" required autofocus autocomplete="username">
                        </div>
                    </div>

                    <div class="input-group animate-fade-up animate-fade-up-d4">
                        <div class="input-wrap">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" id="password" name="password" placeholder="Masukkan Password" required autocomplete="current-password">
                            <button type="button" class="toggle-pass" id="togglePass" tabindex="-1" aria-label="Tampilkan password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options animate-fade-up animate-fade-up-d5">
                        <label class="remember">
                            <input type="checkbox" name="remember" id="remember" checked>
                            Ingat Saya
                        </label>
                        <a href="#" class="forgot-link">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn-login animate-fade-up animate-fade-up-d5" id="btnLogin">
                        <span class="spinner"></span>
                        <span class="btn-text"><i class="bi bi-arrow-right"></i> Masuk</span>
                    </button>
                </form>

                <div class="trust-row animate-fade-up animate-fade-up-d6">
                    <div class="trust-item">
                        <div class="trust-circle blue"><i class="bi bi-geo-alt"></i></div>
                        GPS Verification
                    </div>
                    <div class="trust-item">
                        <div class="trust-circle green"><i class="bi bi-camera"></i></div>
                        Selfie Verification
                    </div>
                    <div class="trust-item">
                        <div class="trust-circle purple"><i class="bi bi-activity"></i></div>
                        Real-Time Monitoring
                    </div>
                </div>
            </div>

            <div class="login-footer animate-fade-up animate-fade-up-d7">
                &copy; 2026 Sistem Informasi Absensi PKL
                <span>Powered by SMK Negeri 1 Contoh</span>
            </div>
        </div>
    </div>

    <script>
        const togglePass = document.getElementById('togglePass');
        const passInput = document.getElementById('password');
        togglePass.addEventListener('click', function() {
            const type = passInput.type === 'password' ? 'text' : 'password';
            passInput.type = type;
            this.querySelector('i').className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        });

        const loginForm = document.getElementById('loginForm');
        const btnLogin = document.getElementById('btnLogin');
        loginForm.addEventListener('submit', function() {
            btnLogin.classList.add('loading');
            btnLogin.disabled = true;
        });

        <?php if ($error): ?>
        document.getElementById('errorMsg').style.display = 'flex';
        <?php endif; ?>
    </script>
</body>
</html>
