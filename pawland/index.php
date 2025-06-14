<?php 
include 'db.php'; 


if (isset($_POST['login'])) {     
    $username = $_POST['username'];     
    $password = $_POST['password'];      

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");     
    $stmt->bind_param("s", $username);     
    $stmt->execute();     
    $result = $stmt->get_result();      

    if ($result->num_rows > 0) {         
        $user = $result->fetch_assoc();         
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user; // Simpan user ke session

            // Redirect sesuai role
            if ($user['role'] === 'admin') {
                header("Location: admin.php"); 
            } else {
                header("Location: dashboard.php"); 
            }
            exit();
        } else {             
            $error = "Password salah!";         
        }     
    } else {         
        $error = "User tidak ditemukan";     
    } 
} 
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawLand - Sistem Penitipan Hewan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #7f5539;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;

    overflow-x: hidden; /* optional: hanya sembunyikan scroll horizontal */
}


        /* Animated background elements */
        .bg-animals {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            z-index: 1;
        }

        .animal-icon {
            position: absolute;
            font-size: 2rem;
            color: white;
            animation: float 6s ease-in-out infinite;
        }

        .animal-icon:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .animal-icon:nth-child(2) { top: 20%; right: 15%; animation-delay: 1s; }
        .animal-icon:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 2s; }
        .animal-icon:nth-child(4) { bottom: 20%; right: 10%; animation-delay: 3s; }
        .animal-icon:nth-child(5) { top: 50%; left: 5%; animation-delay: 4s; }
        .animal-icon:nth-child(6) { top: 70%; right: 20%; animation-delay: 5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(5deg); }
            66% { transform: translateY(-10px) rotate(-5deg); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            background: #7f5539;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .logo i {
            font-size: 2rem;
            color: white;
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: bold;
            background: #7f5539;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .tagline {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: #7f5539;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-message {
            background: rgba(255, 107, 107, 0.1);
            color: #ff4757;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #ff4757;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .error-message i {
            margin-right: 8px;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .features {
            display: flex;
            justify-content: space-around;
            margin: 1.5rem 0;
            padding: 1rem 0;
            border-top: 1px solid #e1e5e9;
            border-bottom: 1px solid #e1e5e9;
        }

        .feature {
            text-align: center;
            flex: 1;
        }

        .feature i {
            font-size: 1.5rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .feature span {
            font-size: 0.8rem;
            color: #666;
            display: block;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .logo-text {
                font-size: 2rem;
            }
        }

        /* Loading animation */
        .btn-login.loading {
            pointer-events: none;
        }

        .btn-login.loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="bg-animals">
        <div class="animal-icon">🐶</div>
        <div class="animal-icon">🐱</div>
        <div class="animal-icon">🐰</div>
        <div class="animal-icon">🐹</div>
        <div class="animal-icon">🐦</div>
        <div class="animal-icon">🐠</div>
    </div>

    <div class="login-container">
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-paw"></i>
            </div>
            <h1 class="logo-text">PawLand</h1>
            <p class="tagline">Sistem Penitipan Hewan Terpercaya</p>
        </div>

        <div class="features">
            <div class="feature">
                <i class="fas fa-shield-alt"></i>
                <span>Aman</span>
            </div>
            <div class="feature">
                <i class="fas fa-heart"></i>
                <span>Peduli</span>
            </div>
            <div class="feature">
                <i class="fas fa-clock"></i>
                <span>24/7</span>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" id="loginForm">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="form-control" required 
                           placeholder="Masukkan username Anda">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" required 
                           placeholder="Masukkan password Anda">
                </div>
            </div>

            <button type="submit" name="login" class="btn-login" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i> Masuk ke PawLand
            </button>
        </form>

        <div class="register-link">
            <p>Belum punya akun? <a href="register.php"><i class="fas fa-user-plus"></i> Daftar Sekarang</a></p>
        </div>
    </div>

    <script>
        // Add loading animation on form submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.innerHTML = 'Memproses...';
        });

        // Add floating animation to form elements
        const formElements = document.querySelectorAll('.form-control');
        formElements.forEach(element => {
            element.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            element.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>