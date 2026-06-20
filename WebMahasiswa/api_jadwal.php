<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ilegal. Silakan login.']);
    exit;
}

include 'koneksi.php';

$action = $_GET['action'] ?? '';

// READ ALL (dengan JOIN untuk menampilkan nama dosen dan matkul)
if ($action == 'list') {
    $sql = "SELECT jadwal.*, dosen.nama as nama_dosen, matkul.nama_matkul, matkul.sks 
            FROM jadwal 
            JOIN dosen ON jadwal.id_dosen = dosen.id 
            JOIN matkul ON jadwal.id_matkul = matkul.id 
            ORDER BY jadwal.id DESC";
    $query = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

// GET SINGLE
if ($action == 'get_single') {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM jadwal WHERE id = $id";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($query);
    echo json_encode($data);
    exit;
}

// CREATE & UPDATE
if ($action == 'save') {
    $id = $_POST['id'] ?? '';
    $id_dosen = intval($_POST['id_dosen']);
    $id_matkul = intval($_POST['id_matkul']);
    $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);
    $ruang = mysqli_real_escape_string($conn, $_POST['ruang']);

    if (empty($id)) {
        $sql = "INSERT INTO jadwal (id_dosen, id_matkul, waktu, ruang) 
                VALUES ($id_dosen, $id_matkul, '$waktu', '$ruang')";
    } else {
        $sql = "UPDATE jadwal SET id_dosen=$id_dosen, id_matkul=$id_matkul, waktu='$waktu', ruang='$ruang' 
                WHERE id=$id";
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

// DELETE
if ($action == 'delete') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM jadwal WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

// GET DOSEN LIST (untuk dropdown di form jadwal)
if ($action == 'get_dosen_list') {
    $query = mysqli_query($conn, "SELECT id, nama FROM dosen ORDER BY nama");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

// GET MATKUL LIST (untuk dropdown di form jadwal)
if ($action == 'get_matkul_list') {
    $query = mysqli_query($conn, "SELECT id, nama_matkul FROM matkul ORDER BY nama_matkul");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}
?>