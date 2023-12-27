<?php
include('db.php');

if (!isset($_GET['id'])) {
    // Redirect jika ID tidak diberikan
    header('Location: admin.php');
    exit;
}

$campaignId = $_GET['id'];

// Ambil informasi campaign dari database
$result = $conn->query("SELECT * FROM campaigns WHERE id = $campaignId");
$campaign = $result->fetch_assoc();

if (!$campaign) {
    // Redirect jika campaign tidak ditemukan
    header('Location: admin.php');
    exit;
}

// Jika admin mengonfirmasi penghapusan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus campaign dari database
    $conn->query("DELETE FROM campaigns WHERE id = $campaignId");
    // Redirect kembali ke halaman admin setelah menghapus
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Campaign</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h1>Hapus Campaign</h1>

                <p>Anda yakin ingin menghapus campaign <strong><?= $campaign['name'] ?></strong>?</p>

                <form action="delete.php?id=<?= $campaignId ?>" method="post">
                    <div class="mt-3">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <a href="admin.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <img src="<?= $campaign['image_path'] ?>" class="img-fluid" alt="Campaign Image">
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
