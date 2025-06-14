<?php 
include 'db.php'; 
if (isset($_POST['register'])) {     
    $username = trim($_POST['username']);     
    $email = trim($_POST['email']);     
    $password = $_POST['password'];
    
    // Validasi input
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Semua field harus diisi!";
    } elseif (strlen($username) < 3) {
        $error = "Username minimal 3 karakter!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        // Cek apakah username sudah ada
        $check_username = $conn->prepare("SELECT username FROM user WHERE username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $result_username = $check_username->get_result();
        
        // Cek apakah email sudah ada
        $check_email = $conn->prepare("SELECT email FROM user WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result_email = $check_email->get_result();
        
        if ($result_username->num_rows > 0) {
            $error = "Username sudah digunakan! Silakan pilih username lain.";
        } elseif ($result_email->num_rows > 0) {
            $error = "Email sudah terdaftar! Silakan gunakan email lain atau login.";
        } else {
            // Hash password dan insert data
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");     
            $stmt->bind_param("sss", $username, $email, $hashed_password);     
            
            if ($stmt->execute()) {
                $success = "Pendaftaran berhasil! Silakan login.";
                // Redirect setelah 2 detik
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000);
                </script>";
            } else {         
                $error = "Gagal mendaftar. Silakan coba lagi.";     
            }
        }
    }
} 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - PawLand</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #7f5539 0%, #a0714b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 2rem 1rem;
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

        .animal-icon:nth-child(1) { top: 15%; left: 8%; animation-delay: 0s; }
        .animal-icon:nth-child(2) { top: 25%; right: 12%; animation-delay: 1.5s; }
        .animal-icon:nth-child(3) { bottom: 35%; left: 15%; animation-delay: 3s; }
        .animal-icon:nth-child(4) { bottom: 15%; right: 8%; animation-delay: 4.5s; }
        .animal-icon:nth-child(5) { top: 55%; left: 3%; animation-delay: 6s; }
        .animal-icon:nth-child(6) { top: 75%; right: 25%; animation-delay: 7.5s; }
        .animal-icon:nth-child(7) { top: 40%; left: 85%; animation-delay: 9s; }
        .animal-icon:nth-child(8) { bottom: 50%; right: 45%; animation-delay: 10.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-25px) rotate(8deg); }
            66% { transform: translateY(-12px) rotate(-8deg); }
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            background: linear-gradient(135deg, #7f5539, #a0714b);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .logo i {
            font-size: 2rem;
            color: white;
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, #7f5539, #a0714b);
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

        .welcome-text {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: linear-gradient(135deg, rgba(127, 85, 57, 0.1), rgba(160, 113, 75, 0.1));
            border-radius: 10px;
            border-left: 4px solid #7f5539;
        }

        .welcome-text h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .welcome-text p {
            color: #666;
            font-size: 0.9rem;
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
            border-color: #7f5539;
            box-shadow: 0 0 0 3px rgba(127, 85, 57, 0.1);
            background: white;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }

        .strength-weak { color: #ff4757; }
        .strength-medium { color: #ffa502; }
        .strength-strong { color: #2ed573; }

        .btn-register {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #7f5539 0%, #a0714b 100%);
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(127, 85, 57, 0.3);
        }

        .btn-register:active {
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

        .success-message {
            background: rgba(46, 213, 115, 0.1);
            color: #2ed573;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #2ed573;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .success-message i {
            margin-right: 8px;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .login-link a {
            color: #7f5539;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #a0714b;
            text-decoration: underline;
        }

        .benefits {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1.5rem 0;
            padding: 1rem 0;
            border-top: 1px solid #e1e5e9;
            border-bottom: 1px solid #e1e5e9;
        }

        .benefit {
            text-align: center;
            padding: 0.5rem;
        }

        .benefit i {
            font-size: 1.2rem;
            color: #7f5539;
            margin-bottom: 0.5rem;
            display: block;
        }

        .benefit span {
            font-size: 0.8rem;
            color: #666;
            display: block;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .register-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .logo-text {
                font-size: 2rem;
            }

            .benefits {
                grid-template-columns: 1fr;
            }
        }

        /* Loading animation */
        .btn-register.loading {
            pointer-events: none;
        }

        .btn-register.loading::after {
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

        .form-tips {
            background: rgba(127, 85, 57, 0.05);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid rgba(127, 85, 57, 0.2);
        }

        .form-tips h4 {
            color: #7f5539;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-tips h4 i {
            margin-right: 0.5rem;
        }

        .form-tips ul {
            font-size: 0.8rem;
            color: #666;
            margin-left: 1rem;
        }

        .form-tips li {
            margin-bottom: 0.3rem;
        }
    </style>
</head>
<body>
    <!-- Background Animals -->
    <div class="bg-animals">
        <div class="animal-icon">🐕</div>
        <div class="animal-icon">🐈</div>
        <div class="animal-icon">🐇</div>
        <div class="animal-icon">🐹</div>
        <div class="animal-icon">🐦</div>
        <div class="animal-icon">🐠</div>
        <div class="animal-icon">🐢</div>
        <div class="animal-icon">🦜</div>
    </div>

    <div class="register-container">
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-paw"></i>
            </div>
            <h1 class="logo-text">PawLand</h1>
            <p class="tagline">Bergabunglah dengan Keluarga PawLand</p>
        </div>

        <div class="welcome-text">
            <h3><i class="fas fa-star"></i> Selamat Datang!</h3>
            <p>Daftar sekarang dan nikmati layanan penitipan hewan terbaik</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div class="form-tips">
            <h4><i class="fas fa-info-circle"></i> Tips Keamanan</h4>
            <ul>
                <li>Gunakan username yang unik dan mudah diingat</li>
                <li>Pastikan email yang valid untuk verifikasi akun</li>
                <li>Buat password minimal 8 karakter dengan kombinasi huruf dan angka</li>
            </ul>
        </div>

        <form method="post" id="registerForm">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="form-control" required 
                           placeholder="Pilih username unik Anda" minlength="3" maxlength="20"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" required 
                           placeholder="email@contoh.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" required 
                           placeholder="Buat password yang kuat" minlength="6">
                </div>
                <div class="password-strength" id="passwordStrength"></div>
            </div>

            <div class="benefits">
                <div class="benefit">
                    <i class="fas fa-shield-alt"></i>
                    <span>Keamanan Terjamin</span>
                </div>
                <div class="benefit">
                    <i class="fas fa-heart"></i>
                    <span>Perawatan Terbaik</span>
                </div>
                <div class="benefit">
                    <i class="fas fa-clock"></i>
                    <span>Layanan 24 Jam</span>
                </div>
                <div class="benefit">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Pantau Real-time</span>
                </div>
            </div>

            <button type="submit" name="register" class="btn-register" id="registerBtn">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </button>
        </form>

        <div class="login-link">
            <p>Sudah punya akun? <a href="index.php"><i class="fas fa-sign-in-alt"></i> Masuk di sini</a></p>
        </div>
    </div>

    <script>
        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthIndicator = document.getElementById('passwordStrength');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let feedback = '';

            // Check password strength
            if (password.length >= 6) strength += 1;
            if (password.length >= 10) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            if (password.length === 0) {
                feedback = '';
            } else if (strength <= 2) {
                feedback = '<span class="strength-weak"><i class="fas fa-times-circle"></i> Password lemah</span>';
            } else if (strength <= 4) {
                feedback = '<span class="strength-medium"><i class="fas fa-exclamation-circle"></i> Password sedang</span>';
            } else {
                feedback = '<span class="strength-strong"><i class="fas fa-check-circle"></i> Password kuat</span>';
            }

            strengthIndicator.innerHTML = feedback;
        });

        // Add loading animation on form submit
        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('registerBtn');
            btn.classList.add('loading');
            btn.innerHTML = 'Mendaftarkan...';
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

        // Username validation
        const usernameInput = document.getElementById('username');
        usernameInput.addEventListener('input', function() {
            // Remove special characters except underscore and dash
            this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, '');
        });

        // Email validation feedback
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('blur', function() {
            if (this.value && !this.checkValidity()) {
                this.style.borderColor = '#ff4757';
            } else if (this.value) {
                this.style.borderColor = '#2ed573';
            }
        });
    </script>
</body>
</html>