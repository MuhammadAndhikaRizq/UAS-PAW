<?php
session_start();
include('db.php');

if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $audio_id = $_GET['id'];

    // Hapus audio dari database
    $result = $conn->query("SELECT * FROM audios WHERE id = $audio_id");
    $audio = $result->fetch_assoc();

    if ($audio) {
        // Hapus file audio dari sistem file
        unlink($audio['path']);

        // Hapus record audio dari database
        $conn->query("DELETE FROM audios WHERE id = $audio_id");

        header('Location: edit.php?id=' . $audio['campaign_id']);
        exit;
    } else {
        echo "Audio tidak ditemukan.";
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
