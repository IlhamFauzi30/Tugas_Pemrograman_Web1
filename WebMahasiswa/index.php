<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIAKAD Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .user-info {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 8px 16px;
            border-radius: 30px;
            color: white;
            font-weight: 500;
        }

        .user-info i {
            margin-right: 8px;
        }

        .btn-logout {
            background: #ef4444;
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Main Container */
        .main-container {
            padding: 30px 20px;
        }

        /* Card Stats */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: none;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .stat-icon i {
            font-size: 30px;
            color: white;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        /* Navigation Menu */
        .nav-menu {
            background: white;
            border-radius: 15px;
            padding: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .nav-item-custom {
            display: inline-block;
            padding: 12px 25px;
            margin: 0 5px;
            border-radius: 12px;
            color: #555;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-item-custom i {
            margin-right: 8px;
        }

        .nav-item-custom:hover {
            background: #f0f0f0;
            color: #667eea;
        }

        .nav-item-custom.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        /* Card Tabel */
        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .card-header-custom h2 {
            color: white;
            font-size: 1.3rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-add {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }

        /* Tabel Styling */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom thead th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .table-custom tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background: #f8f9fa;
        }

        .btn-edit {
            background: #f59e0b;
            border: none;
            border-radius: 8px;
            padding: 6px 15px;
            color: white;
            margin-right: 5px;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #ef4444;
            border: none;
            border-radius: 8px;
            padding: 6px 15px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Modal Styling */
        .modal-content-custom {
            border-radius: 20px;
            border: none;
            overflow: hidden;
        }

        .modal-header-custom {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border: none;
        }

        .modal-header-custom .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body-custom {
            padding: 25px;
        }

        .form-label-custom {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control-custom {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .btn-save {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            padding: 12px;
            color: white;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 30px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header-custom {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .nav-item-custom {
                padding: 8px 15px;
                font-size: 12px;
            }
            .stat-card {
                text-align: center;
            }
            .stat-icon {
                margin: 0 auto 15px auto;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap"></i> SIAKAD Universitas
            </a>
            <div class="d-flex align-items-center gap-3">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['username']); ?>
                </div>
                <a href="logout.php" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="container">
            
            <!-- Statistics Cards -->
            <div class="row" id="statistics">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number" id="total-mahasiswa">0</div>
                        <div class="stat-label">Total Mahasiswa</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                        <div class="stat-number" id="total-dosen">0</div>
                        <div class="stat-label">Total Dosen</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-number" id="total-matkul">0</div>
                        <div class="stat-label">Total Mata Kuliah</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-number" id="total-jadwal">0</div>
                        <div class="stat-label">Total Jadwal</div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="nav-menu text-center">
                <a href="index.php" class="nav-item-custom active">
                    <i class="fas fa-user-graduate"></i> Mahasiswa
                </a>
                <a href="dosen.php" class="nav-item-custom">
                    <i class="fas fa-chalkboard-user"></i> Dosen
                </a>
                <a href="matkul.php" class="nav-item-custom">
                    <i class="fas fa-book"></i> Mata Kuliah
                </a>
                <a href="jadwal.php" class="nav-item-custom">
                    <i class="fas fa-calendar-alt"></i> Jadwal
                </a>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <div class="card-header-custom">
                    <h2>
                        <i class="fas fa-table"></i> Daftar Mahasiswa
                    </h2>
                    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#mahasiswaModal" onclick="siapkanTambah()">
                        <i class="fas fa-plus-circle"></i> Tambah Mahasiswa
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Lengkap</th>
                                <th>Jurusan</th>
                                <th>Email</th>
                                <th style="text-align: center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tempat-data-mahasiswa">
                            <tr><td colspan="6" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <br>Memuat data...
                            </td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="footer">
                <i class="fas fa-copyright"></i> 2024 SIAKAD Universitas | Dibangun dengan <i class="fas fa-heart text-danger"></i> untuk Pendidikan
            </div>
        </div>
    </div>

    <!-- Modal Form Mahasiswa -->
    <div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-user-plus"></i> Form Mahasiswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formMahasiswa" onsubmit="simpanData(event)">
                    <div class="modal-body modal-body-custom">
                        <input type="hidden" id="mahasiswa_id" name="id">
                        
                        <div class="mb-3">
                            <label for="nim" class="form-label-custom">NIM</label>
                            <input type="text" class="form-control form-control-custom" id="nim" name="nim" placeholder="Masukkan NIM" required autocomplete="off">
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label-custom">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-custom" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" required autocomplete="off">
                        </div>
                        
                        <div class="mb-3">
                            <label for="jurusan" class="form-label-custom">Jurusan</label>
                            <select class="form-control form-control-custom" id="jurusan" name="jurusan" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Teknik Komputer">Teknik Komputer</option>
                                <option value="Manajemen Informatika">Manajemen Informatika</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label-custom">Email</label>
                            <input type="email" class="form-control form-control-custom" id="email" name="email" placeholder="Masukkan Email" required autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: none; padding: 0 25px 25px 25px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
        // Tambahan fungsi untuk menghitung total data
        function loadStatistics() {
            // Hitung total mahasiswa dari data yang sudah dimuat
            fetch('api.php?action=list')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-mahasiswa').innerText = data.length;
                });
            
            fetch('api_dosen.php?action=list')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-dosen').innerText = data.length;
                });
            
            fetch('api_matkul.php?action=list')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-matkul').innerText = data.length;
                });
            
            fetch('api_jadwal.php?action=list')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-jadwal').innerText = data.length;
                });
        }
        
        // Override loadData yang asli untuk menambah update statistik
        const originalLoadData = loadData;
        window.loadData = function() {
            originalLoadData();
            setTimeout(loadStatistics, 100);
        };
        
        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            loadStatistics();
        });
    </script>
</body>
</html>