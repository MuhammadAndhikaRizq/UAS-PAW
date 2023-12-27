<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM audios WHERE id = $id");
    $audio = $result->fetch_assoc();

    if ($audio) {
        $filename = $audio['filename'];
        $filepath = $audio['path'];

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filepath);
        exit;
    } else {
        echo 'File tidak ditemukan.';
    }
}
?>
