<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ilegal']);
    exit;
}

include 'koneksi.php';

$action = $_GET['action'] ?? '';

// READ ALL
if ($action == 'list') {
    $query = mysqli_query($conn, "SELECT * FROM matkul ORDER BY id DESC");
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
    $query = mysqli_query($conn, "SELECT * FROM matkul WHERE id = $id");
    $data = mysqli_fetch_assoc($query);
    echo json_encode($data);
    exit;
}

// CREATE & UPDATE
if ($action == 'save') {
    $id = $_POST['id'] ?? '';
    $kode_matkul = mysqli_real_escape_string($conn, $_POST['kode_matkul']);
    $nama_matkul = mysqli_real_escape_string($conn, $_POST['nama_matkul']);
    $sks = intval($_POST['sks']);

    if (empty($id)) {
        $sql = "INSERT INTO matkul (kode_matkul, nama_matkul, sks) VALUES ('$kode_matkul', '$nama_matkul', $sks)";
    } else {
        $sql = "UPDATE matkul SET kode_matkul='$kode_matkul', nama_matkul='$nama_matkul', sks=$sks WHERE id=$id";
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
    $sql = "DELETE FROM matkul WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}
?>