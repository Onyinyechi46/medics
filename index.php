<?php
declare(strict_types=1);

require_once __DIR__ . '/auth-app/includes/auth.php';
startSecureSession();
$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Coxygen · advanced healthcare on Cardano</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root{
      --navy:#0a1628;--navy-mid:#112244;--blue:#1565c0;--sky:#0ea5e9;--sky-light:#38bdf8;
      --teal:#0d9488;--gold:#c9a84c;--gold-light:#e4c97a;--white:#fff;--off-white:#f0f4f8;
      --slate:#64748b;--success:#10b981;--danger:#ef4444;--warning:#f59e0b;
      --font-display:'Playfair Display',Georgia,serif;--font-body:'DM Sans',system-ui,sans-serif;--font-mono:'DM Mono','Courier New',monospace;
      --radius-sm:8px;--radius-md:14px;--radius-lg:22px;--radius-xl:32px;
      --shadow-sm:0 2px 12px rgba(0,0,0,.06);--shadow-md:0 8px 32px rgba(0,0,0,.10);
      --shadow-lg:0 20px 60px rgba(0,0,0,.16);--shadow-blue:0 8px 32px rgba(21,101,192,.28);
    }
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:var(--font-body);background:var(--white);color:var(--navy);line-height:1.65}

    .btn{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:var(--radius-sm);font-size:15px;font-weight:600;cursor:pointer;transition:all .25s;border:none;text-decoration:none}
    .btn-primary{background:linear-gradient(135deg,var(--sky),var(--blue));color:#fff;box-shadow:var(--shadow-blue)}
    .btn-primary:hover{transform:translateY(-3px);box-shadow:0 12px 36px rgba(14,165,233,.5)}

    .btn-nav-login,.btn-nav-register{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;white-space:nowrap;text-decoration:none}
    .btn-nav-login{background:transparent;color:rgba(255,255,255,.82);border:1.5px solid rgba(255,255,255,.22)}
    .btn-nav-login:hover{background:rgba(255,255,255,.09);border-color:rgba(255,255,255,.5);color:#fff}
    .btn-nav-register{background:rgba(14,165,233,.14);color:var(--sky-light);border:1.5px solid rgba(14,165,233,.42)}
    .btn-nav-register:hover{background:rgba(14,165,233,.3);border-color:var(--sky);color:#fff;transform:translateY(-1px);box-shadow:0 4px 14px rgba(14,165,233,.25)}

    .launch-app-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 22px;background:linear-gradient(135deg,var(--sky),var(--blue));color:#fff;border-radius:var(--radius-sm);font-size:13px;font-weight:600;border:none;cursor:pointer;transition:all .25s;box-shadow:0 4px 16px rgba(14,165,233,.3);text-decoration:none;margin-left:4px}
    .launch-app-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(14,165,233,.45)}

    .landing-wrapper{background:linear-gradient(160deg,var(--navy) 0%,var(--navy-mid) 70%,#162a50 100%);position:relative;overflow:hidden}
    .deco-ring{position:absolute;border-radius:50%;pointer-events:none;border:1px solid rgba(14,165,233,.12)}
    .ring1{width:580px;height:580px;right:-140px;top:-120px;animation:rotateRing 20s linear infinite}
    .ring2{width:400px;height:400px;right:0;top:40px;border-color:rgba(14,165,233,.18);animation:rotateRing 14s linear infinite reverse}
    @keyframes rotateRing{from{transform:rotate(0)}to{transform:rotate(360deg)}}

    .landing-nav{position:relative;z-index:10;display:flex;align-items:center;justify-content:space-between;padding:24px 48px;max-width:1300px;margin:0 auto}
    .nav-logo{display:flex;align-items:center;gap:12px;text-decoration:none}
    .logo-icon{width:44px;height:44px;background:linear-gradient(135deg,var(--sky),var(--blue));border-radius:12px;display:grid;place-items:center;font-size:20px;color:#fff;box-shadow:0 4px 16px rgba(14,165,233,.4)}
    .logo-text .name{font-family:var(--font-display);font-size:17px;font-weight:700;color:#fff;letter-spacing:-.2px}
    .logo-text .tagline{font-size:10px;font-weight:500;color:var(--sky-light);letter-spacing:.6px;text-transform:uppercase}
    .nav-actions{display:flex;align-items:center;gap:14px}

    .hero-section{position:relative;z-index:5;max-width:1300px;margin:0 auto;padding:30px 48px 60px;display:grid;grid-template-columns:1.1fr .9fr;gap:50px;align-items:center}
    .hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(14,165,233,.15);border:1px solid rgba(14,165,233,.35);color:var(--sky-light);font-size:12px;font-weight:600;letter-spacing:.8px;text-transform:uppercase;padding:6px 14px;border-radius:50px;margin-bottom:24px}
    .hero-content h1{font-family:var(--font-display);font-size:clamp(44px,4.5vw,64px);font-weight:800;color:#fff;line-height:1.1;letter-spacing:-1px;margin-bottom:22px}
    .hero-content h1 .highlight{background:linear-gradient(90deg,var(--sky-light),var(--gold-light));-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
    .hero-description{font-size:18px;color:rgba(255,255,255,.72);line-height:1.7;margin-bottom:38px;max-width:550px}
    .cta-row{display:flex;flex-wrap:wrap;gap:16px;align-items:center;margin-bottom:48px}
    .trust-pill{display:inline-flex;align-items:center;gap:8px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);border-radius:50px;padding:8px 18px;color:#34d399;font-size:13px;font-weight:500}

    .hero-stats{display:flex;gap:40px;border-top:1px solid rgba(255,255,255,.1);padding-top:32px}
    .stat-item .num{font-family:var(--font-display);font-size:28px;font-weight:700;color:#fff;line-height:1}
    .stat-item .label{font-size:12px;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.5px}

    .hero-visual{background:linear-gradient(145deg,rgba(255,255,255,.04),rgba(255,255,255,.01));border:1px solid rgba(255,255,255,.08);border-radius:var(--radius-xl);padding:20px;backdrop-filter:blur(6px);animation:float 6s ease-in-out infinite}
    @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
    .card-preview{background:rgba(0,0,0,.3);border-radius:var(--radius-lg);padding:28px;border-left:4px solid var(--sky)}
    .card-preview p{color:rgba(255,255,255,.8);font-size:14px;font-family:var(--font-mono);margin-bottom:12px}
    .card-preview .ada-badge{background:var(--navy-mid);border-radius:40px;padding:6px 12px;display:inline-block;color:var(--sky);font-weight:600}

    /* MODALS */
    .auth-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.78);backdrop-filter:blur(10px);z-index:10000;align-items:center;justify-content:center;padding:18px}
    .auth-modal-overlay.open{display:flex}
    .auth-modal-box{background:#fff;border-radius:var(--radius-xl);width:min(460px,94vw);box-shadow:0 32px 80px rgba(0,0,0,.28);overflow:hidden;animation:authModalIn .32s cubic-bezier(.22,.68,0,1.1)}
    @keyframes authModalIn{from{opacity:0;transform:scale(.93) translateY(14px)}to{opacity:1;transform:none}}
    .auth-modal-header{background:linear-gradient(145deg,var(--navy),var(--navy-mid));padding:32px 36px 28px;position:relative}
    .auth-modal-header .close-x{position:absolute;top:18px;right:20px;width:30px;height:30px;background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.14);border-radius:8px;display:grid;place-items:center;cursor:pointer;color:rgba(255,255,255,.6);font-size:13px}
    .auth-modal-header .close-x:hover{background:rgba(239,68,68,.3);color:#fca5a5}
    .auth-modal-logo{width:50px;height:50px;background:linear-gradient(135deg,var(--sky),var(--blue));border-radius:14px;display:grid;place-items:center;font-size:22px;color:#fff;margin-bottom:16px;box-shadow:0 6px 20px rgba(14,165,233,.4)}
    .auth-modal-header h2{font-family:var(--font-display);font-size:24px;font-weight:700;color:#fff;margin-bottom:6px}
    .auth-modal-header p{font-size:14px;color:rgba(255,255,255,.58)}
    .auth-modal-body{padding:32px 36px 36px}
    .auth-field{margin-bottom:18px}
    .auth-field label{display:block;font-size:13px;font-weight:600;color:var(--navy);margin-bottom:7px}
    .auth-input{width:100%;padding:12px 16px;background:var(--off-white);border:1.5px solid #dde5f0;border-radius:var(--radius-sm);font-size:14px;color:var(--navy)}
    .auth-input:focus{outline:none;border-color:var(--sky);box-shadow:0 0 0 3px rgba(14,165,233,.12)}
    .auth-btn-primary{width:100%;padding:14px;background:linear-gradient(135deg,var(--sky),var(--blue));color:#fff;border:none;border-radius:var(--radius-sm);font-size:15px;font-weight:700;cursor:pointer;transition:.22s;box-shadow:var(--shadow-blue);margin-top:8px}
    .auth-btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(14,165,233,.45)}
    .auth-switch{text-align:center;margin-top:20px;font-size:13.5px;color:var(--slate)}
    .auth-switch a{color:var(--blue);font-weight:600;cursor:pointer;text-decoration:none}

    @media (max-width:700px){
      .landing-nav{padding:20px 24px;flex-wrap:wrap;gap:12px}
      .nav-actions .btn-nav-login,.nav-actions .btn-nav-register{display:none}
      .hero-section{grid-template-columns:1fr;text-align:center}
      .hero-description{margin-left:auto;margin-right:auto}
      .cta-row{justify-content:center}
      .hero-stats{justify-content:center;flex-wrap:wrap}
    }
  </style>
</head>
<body>

<div class="landing-wrapper">
  <div class="deco-ring ring1"></div>
  <div class="deco-ring ring2"></div>

  <div class="landing-nav">
    <a class="nav-logo" href="#">
      <div class="logo-icon"><i class="fas fa-heartbeat"></i></div>
      <div class="logo-text">
        <div class="name">Coxygen Medical Center</div>
        <div class="tagline">Advanced Healthcare on Cardano</div>
      </div>
    </a>

    <div class="nav-actions">
      <button class="btn-nav-login" type="button" onclick="openAuthModal('login')"><i class="fas fa-sign-in-alt"></i> Login</button>
      <button class="btn-nav-register" type="button" onclick="openAuthModal('register')"><i class="fas fa-user-plus"></i> Register</button>
      <a href="auth-app/application.php" class="launch-app-btn"><i class="fas fa-rocket"></i> Launch App</a>
    </div>
  </div>

  <div class="hero-section">
    <div class="hero-content">
      <div class="hero-badge"><i class="fas fa-shield-alt"></i> Cardano blockchain · medical lending</div>
      <h1>Instant care, <span class="highlight">collateral-backed.</span> No banks. No delays.</h1>
      <p class="hero-description">
        Coxygen combines a world-class private hospital with a decentralised credit protocol.
        Deposit ADA as collateral, receive immediate medical treatment, repay with full on-chain transparency —
        <strong>no credit checks, no hidden fees, no intermediaries.</strong>
      </p>
      <div class="cta-row">
        <a class="btn btn-primary" href="auth-app/application.php"><i class="fas fa-arrow-right"></i> Launch dApp & open credit</a>
        <span class="trust-pill"><i class="fas fa-circle-check"></i> Plutus V2 · audited</span>
      </div>

      <div class="hero-stats">
        <div class="stat-item"><div class="num">2,700+</div><div class="label">active loans</div></div>
        <div class="stat-item"><div class="num">₳5.2M</div><div class="label">credit issued</div></div>
        <div class="stat-item"><div class="num">12</div><div class="label">medical depts</div></div>
        <div class="stat-item"><div class="num">24/7</div><div class="label">emergency</div></div>
      </div>
    </div>

    <div class="hero-visual">
      <div class="card-preview">
        <p style="font-family:var(--font-mono);color:var(--sky);">· smart contract snapshot ·</p>
        <p><i class="fas fa-lock" style="color:var(--gold);"></i> collateral: 1,200 ADA</p>
        <p><i class="fas fa-hand-holding-medical" style="color:var(--sky);"></i> bill: 850 ADA</p>
        <p><i class="fas fa-coins" style="color:var(--success);"></i> interest: 42 ADA</p>
        <div class="ada-badge"><i class="fas fa-link"></i> repaid 3,200 times</div>
        <p style="margin-top:16px;font-size:12px;">🔗 latest tx: 0x3f7a...b8e2</p>
      </div>
    </div>
  </div>
</div>

<!-- LOGIN MODAL -->
<div class="auth-modal-overlay" id="loginModal" aria-hidden="true">
  <div class="auth-modal-box" role="dialog" aria-modal="true" aria-label="Login">
    <div class="auth-modal-header">
      <div class="close-x" role="button" tabindex="0" onclick="closeAuthModal('login')"><i class="fas fa-times"></i></div>
      <div class="auth-modal-logo"><i class="fas fa-heartbeat"></i></div>
      <h2>Welcome Back</h2>
      <p>Sign in to your Coxygen patient account</p>
    </div>

    <div class="auth-modal-body">
      <form method="post" action="auth-app/login.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="auth-field">
          <label>Email Address</label>
          <input class="auth-input" type="email" name="email" placeholder="you@example.com" required>
        </div>

        <div class="auth-field">
          <label>Password</label>
          <input class="auth-input" type="password" name="password" placeholder="••••••••" required>
        </div>

        <button class="auth-btn-primary" type="submit"><i class="fas fa-sign-in-alt"></i> Sign In</button>
      </form>

      <div class="auth-switch">Don't have an account? <a onclick="openAuthModal('register')">Create one free →</a></div>
    </div>
  </div>
</div>

<!-- REGISTER MODAL -->
<div class="auth-modal-overlay" id="registerModal" aria-hidden="true">
  <div class="auth-modal-box" role="dialog" aria-modal="true" aria-label="Register">
    <div class="auth-modal-header">
      <div class="close-x" role="button" tabindex="0" onclick="closeAuthModal('register')"><i class="fas fa-times"></i></div>
      <div class="auth-modal-logo"><i class="fas fa-user-plus"></i></div>
      <h2>Create Account</h2>
      <p>Join Coxygen Medical Center today</p>
    </div>

    <div class="auth-modal-body">
      <form method="post" action="auth-app/register.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="auth-field">
          <label>Email</label>
          <input class="auth-input" type="email" name="email" placeholder="john@example.com" required>
        </div>

        <div class="auth-field">
          <label>Password</label>
          <input class="auth-input" type="password" name="password" placeholder="min. 6 characters" minlength="6" required>
        </div>

        <div style="margin:10px 0 20px;display:flex;gap:10px;align-items:flex-start;">
          <input type="checkbox" id="agreeTerms" required style="accent-color:var(--blue);margin-top:3px;">
          <label for="agreeTerms" style="font-size:13px;color:var(--slate);">I agree to Terms & Privacy</label>
        </div>

        <button class="auth-btn-primary" type="submit"><i class="fas fa-user-check"></i> Create My Account</button>
      </form>

      <div class="auth-switch">Already have an account? <a onclick="openAuthModal('login')">Sign in →</a></div>
    </div>
  </div>
</div>

<script>
  (function () {
    const login = document.getElementById('loginModal');
    const register = document.getElementById('registerModal');

    function openAuthModal(type) {
      if (!login || !register) return;
      login.classList.remove('open');
      register.classList.remove('open');
      (type === 'login' ? login : register).classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeAuthModal(type) {
      if (!login || !register) return;
      (type === 'login' ? login : register).classList.remove('open');
      if (!login.classList.contains('open') && !register.classList.contains('open')) {
        document.body.style.overflow = '';
      }
    }

    window.openAuthModal = openAuthModal;
    window.closeAuthModal = closeAuthModal;

    [login, register].forEach((el) => {
      if (!el) return;
      el.addEventListener('click', (e) => {
        if (e.target === el) {
          el.classList.remove('open');
          document.body.style.overflow = '';
        }
      });
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        if (login) login.classList.remove('open');
        if (register) register.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  })();
</script>

</body>
</html>