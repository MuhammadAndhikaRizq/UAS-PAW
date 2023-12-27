<?php
session_start();
include('db.php');

if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campaignName = isset($_POST['campaign_name']) ? $_POST['campaign_name'] : '';
    $campaignDescription = isset($_POST['campaign_description']) ? $_POST['campaign_description'] : '';
    $token = isset($_POST['token']) ? $_POST['token'] : '';
    $uploadDir = 'audios/';

    // Handle campaign image upload
    $campaign_image_path = '';
    if (isset($_FILES['campaign_image']['name']) && !empty($_FILES['campaign_image']['name'])) {
        $campaign_image_path = $uploadDir . $_FILES['campaign_image']['name'];
        if (!move_uploaded_file($_FILES['campaign_image']['tmp_name'], $campaign_image_path)) {
            $error_message = 'Failed to upload campaign image.';
        }
    } else {
        $error_message = 'Campaign image is required.';
    }

    if (!isset($error_message)) {
        // Insert campaign data into the campaigns table, including the token
        $sqlCampaign = "INSERT INTO campaigns (name, description, image_path, token) VALUES (?, ?, ?, ?)";
        $stmtCampaign = $conn->prepare($sqlCampaign);
        $stmtCampaign->bind_param("ssss", $campaignName, $campaignDescription, $campaign_image_path, $token);

        if (!$stmtCampaign->execute()) {
            $error_message = 'Failed to save campaign data: ' . $stmtCampaign->error;
        } else {
            $campaignId = $conn->insert_id;

            // Handle audio file upload
            foreach ($_FILES['audiofiles']['tmp_name'] as $key => $tmp_name) {
                $audioFile = $uploadDir . uniqid() . '_' . basename($_FILES['audiofiles']['name'][$key]);

                if (move_uploaded_file($tmp_name, $audioFile)) {
                    // Insert audio data into the audios table
                    $filename = $_FILES['audiofiles']['name'][$key];
                    $audioPath = $audioFile;

                    $sqlAudio = "INSERT INTO audios (filename, path, campaign_id, created_at) VALUES (?, ?, ?, NOW())";
                    $stmtAudio = $conn->prepare($sqlAudio);
                    $stmtAudio->bind_param("sss", $filename, $audioPath, $campaignId);

                    if (!$stmtAudio->execute()) {
                        $error_message = 'Failed to save audio data: ' . $stmtAudio->error;
                    }
                } else {
                    $error_message = 'Failed to upload audio file.';
                }
            }

            if (!isset($error_message)) {
                header('Location: admin.php');
                exit;
            }
        }
    }
}

$result = $conn->query("SELECT * FROM campaigns");
$campaigns = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Audio (Admin)</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <a href="admin.php" class="btn btn-secondary mb-3">Back</a>
        <h1>Upload Audio</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <!-- ... (form fields) ... -->
            <div class="mb-3">
                <label for="campaign_name" class="form-label">Campaign Name:</label>
                <input type="text" class="form-control" name="campaign_name" required>
            </div>

            <div class="mb-3">
                <label for="campaign_description" class="form-label">Campaign Description:</label>
                <textarea class="form-control" name="campaign_description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="token" class="form-label">Token:</label>
                <input type="text" class="form-control" name="token" required>
            </div>

            <div class="mb-3">
                <label for="campaign_image" class="form-label">Select campaign image:</label>
                <input type="file" class="form-control" name="campaign_image" accept=".jpg, .png, .jpeg" required>
            </div>

            <div class="mb-3">
                <label for="audiofiles[]" class="form-label">Select audio file(s):</label>
                <input type="file" class="form-control" name="audiofiles[]" accept=".mp3, .wav" multiple required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>