<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ilegal']);
    exit;
}

include 'koneksi.php';

$action = $_GET['action'] ?? '';

if ($action == 'list') {
    $query = mysqli_query($conn, "SELECT * FROM dosen ORDER BY id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

if ($action == 'get_single') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM dosen WHERE id = $id");
    $data = mysqli_fetch_assoc($query);
    echo json_encode($data);
    exit;
}

if ($action == 'save') {
    $id = $_POST['id'] ?? '';
    $nidn = mysqli_real_escape_string($conn, $_POST['nidn']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    if (empty($id)) {
        $sql = "INSERT INTO dosen (nidn, nama, alamat) VALUES ('$nidn', '$nama', '$alamat')";
    } else {
        $sql = "UPDATE dosen SET nidn='$nidn', nama='$nama', alamat='$alamat' WHERE id=$id";
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

if ($action == 'delete') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM dosen WHERE id = $id";
    echo json_encode(['status' => mysqli_query($conn, $sql) ? 'success' : 'error']);
    exit;
}
?>