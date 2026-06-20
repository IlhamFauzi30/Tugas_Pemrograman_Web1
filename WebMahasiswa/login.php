<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = false;

if (isset($_POST['submit_login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password == $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit;
        }
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAKAD Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Animasi background bergerak */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .bg-animation .circle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: move 20s infinite linear;
        }

        .circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
        }

        .circle:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: 100px;
            right: 50px;
            animation-duration: 15s;
        }

        .circle:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: -50px;
            left: 30%;
            animation-duration: 25s;
        }

        .circle:nth-child(4) {
            width: 400px;
            height: 400px;
            top: 30%;
            right: -150px;
            animation-duration: 18s;
        }

        @keyframes move {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }
            50% {
                transform: translate(30px, 20px) rotate(180deg);
            }
            100% {
                transform: translate(0, 0) rotate(360deg);
            }
        }

        /* Container utama */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        /* Card dengan efek glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }

        /* Side kiri (branding) */
        .brand-side {
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
            padding: 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .brand-side h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .brand-side .icon {
            font-size: 80px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
        }

        .brand-side p {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            line-height: 1.6;
        }

        .features {
            margin-top: 30px;
            text-align: left;
        }

        .features li {
            color: rgba(255,255,255,0.8);
            margin-bottom: 12px;
            list-style: none;
        }

        .features li i {
            margin-right: 10px;
            color: #ffd700;
        }

        /* Side kanan (form login) */
        .form-side {
            padding: 50px 40px;
            background: rgba(255, 255, 255, 0.95);
        }

        .form-side h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .form-side .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.1rem;
        }

        .input-group-custom input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .input-group-custom input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-custom {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-custom i {
            color: #ef4444;
            font-size: 1.2rem;
        }

        .alert-custom span {
            color: #991b1b;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .brand-side {
                display: none;
            }
            .form-side {
                padding: 40px 30px;
            }
            .glass-card {
                max-width: 450px;
            }
        }

        /* Floating shapes */
        .floating-shapes {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 0;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>

    <div class="login-container">
        <div class="glass-card">
            <div class="row g-0">
                <!-- Left Side - Branding -->
                <div class="col-md-6">
                    <div class="brand-side">
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h1>SIAKAD</h1>
                        <p>Sistem Informasi Akademik<br>Universitas Teknologi Digital</p>
                        <ul class="features">
                            <li><i class="fas fa-check-circle"></i> Kelola Data Mahasiswa</li>
                            <li><i class="fas fa-check-circle"></i> Kelola Data Dosen</li>
                            <li><i class="fas fa-check-circle"></i> Kelola Mata Kuliah</li>
                            <li><i class="fas fa-check-circle"></i> Kelola Jadwal Kuliah</li>
                        </ul>
                    </div>
                </div>

                <!-- Right Side - Form Login -->
                <div class="col-md-6">
                    <div class="form-side">
                        <h2>Selamat Datang</h2>
                        <p class="subtitle">Silakan login untuk melanjutkan</p>

                        <?php if ($error) : ?>
                            <div class="alert-custom">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Username atau password salah! Silakan coba lagi.</span>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="form-group">
                                <label>Username</label>
                                <div class="input-group-custom">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group-custom">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                                </div>
                            </div>

                            <button type="submit" name="submit_login" class="btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk
                            </button>
                        </form>

                        <hr class="my-4" style="border-color: #e0e0e0;">

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> Sistem Aman & Terpercaya
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>