<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Login - Lab Business Analytics</title>
  <meta name="description" content="Login page for Lab Business Analytics">
  <meta name="keywords" content="login, lab, business analytics">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

  <style>
    :root {
      --primary-blue: #e3f2fd;
      --secondary-blue: #bbdefb;
      --accent-blue: #0d83fd;
      --dark-blue: #70b9f5;
      --text-blue: #4f9cf4;
      --shadow-blue: rgba(66, 165, 245, 0.2);
    }
    
    * {
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 50%, #90caf9 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 15px;
      line-height: 1.5;
    }
    
    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 
        0 10px 25px var(--shadow-blue),
        0 4px 10px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      width: 100%;
      max-width: 360px;
      border: 1px solid rgba(255, 255, 255, 0.5);
      transition: all 0.3s ease;
    }
    
    /* Responsive container */
    @media (max-width: 480px) {
      .login-container {
        max-width: 100%;
        margin: 0 10px;
        border-radius: 12px;
      }
    }
    
    @media (max-width: 360px) {
      .login-container {
        margin: 0 5px;
      }
    }
    
    .login-header {
      background: linear-gradient(135deg, var(--dark-blue), var(--accent-blue));
      color: white;
      padding: 25px 20px 20px;
      text-align: center;
      position: relative;
    }
    
    /* Responsive header */
    @media (max-width: 480px) {
      .login-header {
        padding: 20px 15px 15px;
      }
    }
    
    .logo-container {
      width: 60px;
      height: 60px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 25px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    /* Responsive logo */
    @media (max-width: 480px) {
      .logo-container {
        width: 50px;
        height: 50px;
        margin-bottom: 12px;
      }
    }
    
    .logo-lab {
      width: 35px;
      height: 45px;
      max-width: 100%;
      height: auto;
    }
    
    /* Responsive logo image */
    @media (max-width: 480px) {
      .logo-lab {
        width: 30px;
        height: 38px;
      }
    }
    
    .logo-placeholder {
      color: white;
      font-size: 18px;
      font-weight: bold;
    }
    
    .login-header h2 {
      margin: 0;
      font-size: 1.3rem;
      font-weight: 600;
    }
    
    /* Responsive title */
    @media (max-width: 480px) {
      .login-header h2 {
        font-size: 1.2rem;
      }
    }
    
    @media (max-width: 360px) {
      .login-header h2 {
        font-size: 1.1rem;
      }
    }
    
    .login-header p {
      margin: 6px 0 0 0;
      opacity: 0.9;
      font-size: 0.85rem;
      font-weight: 400;
    }
    
    /* Responsive subtitle */
    @media (max-width: 480px) {
      .login-header p {
        font-size: 0.8rem;
        margin-top: 4px;
      }
    }
    
    .login-body {
      padding: 25px;
    }
    
    /* Responsive body */
    @media (max-width: 480px) {
      .login-body {
        padding: 20px;
      }
    }
    
    @media (max-width: 360px) {
      .login-body {
        padding: 15px;
      }
    }
    
    .form-control {
      border-radius: 15px;
      padding: 10px 40px 10px 12px;
      font-size: 0.85rem;
      border: 1.5px solid var(--secondary-blue);
      background: rgba(255, 255, 255, 0.9);
      transition: all 0.3s ease;
      margin-bottom: 15px;
      height: 42px;
      width: 100%;
    }
    
    /* Responsive form controls */
    @media (max-width: 480px) {
      .form-control {
        height: 40px;
        font-size: 0.8rem;
        padding: 8px 35px 8px 12px;
        margin-bottom: 12px;
      }
    }
    
    @media (max-width: 360px) {
      .form-control {
        height: 38px;
        padding: 6px 32px 6px 10px;
      }
    }
    
    .form-control:focus {
      border-color: var(--dark-blue);
      box-shadow: 0 3px 10px var(--shadow-blue);
      background: white;
    }
    
    .input-group {
      position: relative;
      width: 100%;
    }
    
    .input-icon {
      position: absolute;
      right: 12px;
      top: 38%;
      transform: translateY(-50%);
      color: var(--dark-blue);
      font-size: 0.9rem;
    }
    
    /* Responsive icons */
    @media (max-width: 480px) {
      .input-icon {
        right: 10px;
        font-size: 0.8rem;
      }
    }
    
    .btn-login {
      background: linear-gradient(135deg, var(--dark-blue), var(--accent-blue));
      border: none;
      width: 100%;
      padding: 10px;
      font-weight: 500;
      font-size: 0.9rem;
      margin-top: 10px;
      border-radius: 10px;
      color: white;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px var(--shadow-blue);
      height: 42px;
      cursor: pointer;
    }
    
    /* Responsive button */
    @media (max-width: 480px) {
      .btn-login {
        height: 40px;
        font-size: 0.85rem;
        margin-top: 8px;
      }
    }
    
    @media (max-width: 360px) {
      .btn-login {
        height: 38px;
        font-size: 0.8rem;
      }
    }
    
    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 15px var(--shadow-blue);
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    .alert {
      margin-top: 12px;
      text-align: center;
      border-radius: 8px;
      padding: 10px;
      font-size: 0.8rem;
      border: none;
      width: 100%;
    }
    
    /* Responsive alerts */
    @media (max-width: 480px) {
      .alert {
        padding: 8px;
        font-size: 0.75rem;
        margin-top: 10px;
      }
    }
    
    .form-check-label {
      font-size: 0.8rem;
      color: var(--text-blue);
      font-weight: 500;
    }
    
    /* Responsive labels */
    @media (max-width: 480px) {
      .form-check-label {
        font-size: 0.75rem;
      }
    }
    
    .form-label {
      font-weight: 600;
      margin-bottom: 6px;
      font-size: 0.85rem;
      color: var(--text-blue);
      display: block;
    }
    
    /* Responsive form labels */
    @media (max-width: 480px) {
      .form-label {
        font-size: 0.8rem;
        margin-bottom: 4px;
      }
    }
    
    .additional-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 15px;
      font-size: 0.8rem;
      flex-wrap: wrap;
      gap: 10px;
    }
    
    /* Responsive options */
    @media (max-width: 480px) {
      .additional-options {
        font-size: 0.75rem;
        margin-top: 12px;
      }
    }
    
    @media (max-width: 360px) {
      .additional-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }
    }
    
    .forgot-password {
      color: var(--dark-blue);
      text-decoration: none;
      font-size: 0.8rem;
      font-weight: 500;
      white-space: nowrap;
    }
    
    /* Responsive links */
    @media (max-width: 480px) {
      .forgot-password {
        font-size: 0.75rem;
      }
    }
    
    .forgot-password:hover {
      color: var(--text-blue);
      text-decoration: underline;
    }
    
    .footer-text {
      text-align: center;
      margin-top: 20px;
      color: var(--text-blue);
      font-size: 0.75rem;
      opacity: 0.8;
      width: 100%;
    }
    
    /* Responsive footer */
    @media (max-width: 480px) {
      .footer-text {
        font-size: 0.7rem;
        margin-top: 15px;
      }
    }
    
    .success-message {
      background: linear-gradient(135deg, #d4edda, #c3e6cb);
      color: #155724;
      border: none;
      border-radius: 8px;
      padding: 10px;
      margin-top: 12px;
      text-align: center;
      font-size: 0.8rem;
      display: none;
      width: 100%;
    }
    
    /* Responsive success message */
    @media (max-width: 480px) {
      .success-message {
        padding: 8px;
        font-size: 0.75rem;
      }
    }
    
    .loading-spinner {
      display: none;
    }
    
    .spinner-border {
      width: 1rem;
      height: 1rem;
      border-width: 2px;
    }
    
    /* Responsive spinner */
    @media (max-width: 480px) {
      .spinner-border {
        width: 0.9rem;
        height: 0.9rem;
      }
    }
    
    .form-check-input {
      width: 0.9rem;
      height: 0.9rem;
      margin-top: 0.1rem;
      border: 1.5px solid var(--secondary-blue);
    }
    
    /* Responsive checkbox */
    @media (max-width: 480px) {
      .form-check-input {
        width: 0.8rem;
        height: 0.8rem;
      }
    }
    
    .form-check-input:checked {
      background-color: var(--dark-blue);
      border-color: var(--dark-blue);
    }
    
    /* Orientation support */
    @media (max-height: 500px) and (orientation: landscape) {
      body {
        padding: 10px;
        align-items: flex-start;
      }
      
      .login-container {
        margin: 10px 0;
      }
    }
    
    /* High DPI screens */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
      .login-container {
        border-width: 0.5px;
      }
    }
    
    /* Reduced motion for accessibility */
    @media (prefers-reduced-motion: reduce) {
      * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
      
      .btn-login:hover {
        transform: none;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-header">
      <div class="logo-container">
        <img src="../assets/img/logo-lab.png" class="logo-lab" alt="Lab Business Analytics Logo">
        <div class="logo-placeholder" style="display: none;"></div>
      </div>
      <h2>Lab Business Analytics</h2>
      <p>Silakan masuk ke akun Anda</p>
    </div>
    
    <div class="login-body">
      <form id="loginForm" method="post" action="login_action.php">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <div class="input-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
            <span class="input-icon"><i class="bi bi-person-fill"></i></span>
          </div>
        </div>
        
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
          </div>
        </div>
        
        <div class="additional-options">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe">
            <label class="form-check-label" for="rememberMe">Ingat saya</label>
          </div>
          <a href="#" class="forgot-password">Lupa password?</a>
        </div>
        
        <button type="submit" class="btn btn-login">
          <span id="loginText">Masuk</span>
          <div class="loading-spinner" id="loadingSpinner">
            <div class="spinner-border text-light" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </button>
        
        <div id="errorMessage" class="alert alert-danger d-none" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>Username atau password salah!
        </div>
        
        <div id="successMessage" class="success-message">
          <i class="bi bi-check-circle-fill me-2"></i>Login berhasil! Mengalihkan...
        </div>
      </form>
      
      <div class="footer-text">
        Lab Business Analytics. &copy; 2025
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Enhancements only: hide error while typing
    document.getElementById('username').addEventListener('input', function() {
      document.getElementById('errorMessage').classList.add('d-none');
    });
    document.getElementById('password').addEventListener('input', function() {
      document.getElementById('errorMessage').classList.add('d-none');
    });
    // Keep logo placeholder fallback
    window.addEventListener('load', function() {
      const logo = document.querySelector('.logo-lab');
      if (logo && logo.naturalHeight === 0) {
        logo.style.display = 'none';
        document.querySelector('.logo-placeholder').style.display = 'flex';
      }
    });
  </script>
</body>

</html>