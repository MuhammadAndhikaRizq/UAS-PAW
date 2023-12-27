<?php
session_start();
include('db.php');

// Fetch the list of campaigns
$query = "SELECT id, name FROM campaigns";
$result = $conn->query($query);

$campaigns = [];
while ($row = $result->fetch_assoc()) {
    $campaigns[] = $row;
}

// Check for error message in session
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the error message after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="assets/user.css">
    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512...">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
        <?php if (isset($error_message) && !empty($error_message)): ?>
            <p class="text-danger"><?= $error_message ?></p>
        <?php endif; ?>

        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container">
                <a class="navbar-brand" href="index.php">VoxQuest</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="user.php">Sound Gallery</a>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="images/BgCyclees.png" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Welcome, User!</h1>
                </div>
            </div>
        </div>

        <div class="campaigns-container mt-3">
            <form action="backend.php" method="post">
                <div class="mb-3">
                    <label for="campaign_id" class="form-label text-dark">Select Campaign:</label>
                    <select name="campaign_id" class="form-select" required>
                        <?php foreach ($campaigns as $campaign): ?>
                            <option value="<?= $campaign['id'] ?>"><?= $campaign['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="token" class="form-label text-dark">Enter Token:</label>
                    <input type="text" name="token" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Access Campaign</button>
            </form>
        </div>
        
        <footer class="text-center text-white" style="background-color: #0E2954;">
        <!-- Grid container -->
            <div class="containe-footer pt-4">
            <!-- Section: Social media -->
            <section class="mb-4">

                <!-- Instagram -->
                <a
                class="btn btn-link btn-floating btn-lg text-light m-1"
                href="https://www.instagram.com/andhikarizqh/"
                role="button"
                data-mdb-ripple-color=light
                ><i class="fab fa-instagram"></i
                ></a>

                <!-- Linkedin -->
                <a
                class="btn btn-link btn-floating btn-lg text-light m-1"
                href="https://www.linkedin.com/in/muhammad-andhika-rizq-392256222/"
                role="button"
                data-mdb-ripple-color=light
                ><i class="fab fa-linkedin"></i
                ></a>
                <!-- Github -->
                <a
                class="btn btn-link btn-floating btn-lg text-light m-1"
                href="https://github.com/MuhammadAndhikaRizq"
                role="button"
                data-mdb-ripple-color=light
                ><i class="fab fa-github"></i
                ></a>
            </section>
            <!-- Section: Social media -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center text-light p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2023 Copyright
            </div>
            <!-- Copyright -->
        </footer>

        

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
