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
    <title>Data Jadwal - SIAKAD Universitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
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
        .btn-logout {
            background: #ef4444;
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .btn-logout:hover { background: #dc2626; transform: translateY(-2px); color: white; }
        .main-container { padding: 30px 20px; }
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
        .nav-item-custom i { margin-right: 8px; }
        .nav-item-custom:hover { background: #f0f0f0; color: #667eea; }
        .nav-item-custom.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
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
        .btn-add:hover { background: white; color: #667eea; transform: translateY(-2px); }
        .table-custom { margin-bottom: 0; }
        .table-custom thead th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 15px;
        }
        .table-custom tbody td { padding: 12px 15px; vertical-align: middle; }
        .table-custom tbody tr:hover { background: #f8f9fa; }
        .btn-edit {
            background: #f59e0b;
            border: none;
            border-radius: 8px;
            padding: 6px 15px;
            color: white;
            margin-right: 5px;
        }
        .btn-delete {
            background: #ef4444;
            border: none;
            border-radius: 8px;
            padding: 6px 15px;
            color: white;
        }
        .ruang-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        .waktu-badge {
            background: #fff3e0;
            color: #f57c00;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 30px;
        }
        @media (max-width: 768px) {
            .card-header-custom { flex-direction: column; gap: 15px; text-align: center; }
            .nav-item-custom { padding: 8px 15px; font-size: 12px; }
        }
    </style>
</head>
<body>
    <nav class="navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap"></i> SIAKAD Universitas</a>
            <div class="d-flex align-items-center gap-3">
                <div class="user-info"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['username']); ?></div>
                <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="container">
            <div class="nav-menu text-center">
                <a href="index.php" class="nav-item-custom"><i class="fas fa-user-graduate"></i> Mahasiswa</a>
                <a href="dosen.php" class="nav-item-custom"><i class="fas fa-chalkboard-user"></i> Dosen</a>
                <a href="matkul.php" class="nav-item-custom"><i class="fas fa-book"></i> Mata Kuliah</a>
                <a href="jadwal.php" class="nav-item-custom active"><i class="fas fa-calendar-alt"></i> Jadwal</a>
            </div>

            <div class="table-card">
                <div class="card-header-custom">
                    <h2><i class="fas fa-calendar-alt"></i> Daftar Jadwal Kuliah</h2>
                    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#jadwalModal" onclick="siapkanTambah()"><i class="fas fa-plus-circle"></i> Tambah Jadwal</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Dosen</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Waktu</th>
                                <th>Ruang</th>
                                <th style="text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tempat-data-jadwal">
                            <tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary"></div><br>Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="footer"><i class="fas fa-copyright"></i> 2024 SIAKAD Universitas | Dibangun dengan <i class="fas fa-heart text-danger"></i> untuk Pendidikan</div>
        </div>
    </div>

    <!-- Modal Jadwal -->
    <div class="modal fade" id="jadwalModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                    <h5 class="modal-title" id="modalTitle"><i class="fas fa-calendar-plus"></i> Form Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formJadwal" onsubmit="simpanData(event)">
                    <div class="modal-body">
                        <input type="hidden" id="jadwal_id" name="id">
                        <div class="mb-3">
                            <label>Dosen</label>
                            <select class="form-control" id="id_dosen" name="id_dosen" required>
                                <option value="">-- Pilih Dosen --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Mata Kuliah</label>
                            <select class="form-control" id="id_matkul" name="id_matkul" required>
                                <option value="">-- Pilih Mata Kuliah --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Waktu Kuliah</label>
                            <input type="text" class="form-control" id="waktu" name="waktu" placeholder="Contoh: Senin 08:00-10:00" required>
                        </div>
                        <div class="mb-3">
                            <label>Ruang</label>
                            <input type="text" class="form-control" id="ruang" name="ruang" placeholder="Contoh: Ruang A-101" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script_jadwal.js"></script>
</body>
</html>