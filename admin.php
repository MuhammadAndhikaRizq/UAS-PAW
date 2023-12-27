<?php
session_start();
include('db.php');

if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}

// Ambil data kampanye dari database
$result = $conn->query("SELECT * FROM campaigns");
$campaigns = $result->fetch_all(MYSQLI_ASSOC);

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Area</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/admin.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h1 class="mb-4">Selamat datang, Admin!</h1>
        <a href="upload.php" class="btn btn-primary mb-3">Unggah Audio</a>

        <form action="admin.php" method="post" class="d-inline">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
        </form>

        <?php foreach ($campaigns as $campaign): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= $campaign['name'] ?></h5>
                    <p class="card-text"><?= $campaign['description'] ?></p>
                    <img src="<?= $campaign['image_path'] ?>" alt="Campaign Image" class="img-fluid mb-3" style="max-width: 300px; height: auto;">

                    <a href="edit.php?id=<?= $campaign['id'] ?>" class="btn btn-warning me-2">Edit</a>
                    <a href="delete.php?id=<?= $campaign['id'] ?>" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>