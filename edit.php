<!-- edit.php -->

<?php
session_start();
include('db.php');

if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}

// Check if Campaign ID is set in the URL
if (!isset($_GET['id'])) {
    echo "Campaign ID is not set in the URL.";
    exit;
}

$campaign_id = $_GET['id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campaignName = $_POST['campaign_name'];
    $campaignDescription = $_POST['campaign_description'];
    $token = $_POST['token']; // Add this line to retrieve the token

    // Update campaign details including the token
    $stmtCampaign = $conn->prepare("UPDATE campaigns SET name = ?, description = ?, token = ? WHERE id = ?");
    $stmtCampaign->bind_param("sssi", $campaignName, $campaignDescription, $token, $campaign_id);

    if (!$stmtCampaign->execute()) {
        $error_message = 'Failed to update campaign details: ' . $stmtCampaign->error;
    } else {
        // Handle image update if a new image is provided
        if ($_FILES['campaign_image']['size'] > 0) {
            $uploadDir = 'campaign_images/';
            $newImagePath = $uploadDir . basename($_FILES['campaign_image']['name']);

            if (move_uploaded_file($_FILES['campaign_image']['tmp_name'], $newImagePath)) {
                // Update image path in the database
                $stmtUpdateImage = $conn->prepare("UPDATE campaigns SET image_path = ? WHERE id = ?");
                $stmtUpdateImage->bind_param("si", $newImagePath, $campaign_id);

                if (!$stmtUpdateImage->execute()) {
                    $error_message = 'Failed to update image path: ' . $stmtUpdateImage->error;
                }
            } else {
                $error_message = 'Failed to upload new image.';
            }
        }

        // Handle audio file upload if provided
        if ($_FILES['audiofile']['size'] > 0) {
            $uploadDir = 'audios/';
            $audioFile = $uploadDir . basename($_FILES['audiofile']['name']);

            if (move_uploaded_file($_FILES['audiofile']['tmp_name'], $audioFile)) {
                // Insert new audio record
                $filename = $_FILES['audiofile']['name'];
                $audioPath = $audioFile;

                $stmtAudio = $conn->prepare("INSERT INTO audios (filename, path, campaign_id, created_at) VALUES (?, ?, ?, NOW())");
                $stmtAudio->bind_param("ssi", $filename, $audioPath, $campaign_id);

                if (!$stmtAudio->execute()) {
                    $error_message = 'Failed to insert new audio record: ' . $stmtAudio->error;
                }
            } else {
                $error_message = 'Failed to upload audio file.';
            }
        }

        // Redirect to avoid form resubmission on page refresh
        header('Location: edit.php?id=' . $campaign_id);
        exit;
    }
}

// Fetch campaign details
$result = $conn->query("SELECT * FROM campaigns WHERE id = $campaign_id");

// Check if the query was successful
if (!$result) {
    echo "Error fetching campaign information: " . $conn->error;
    exit;
}

$campaign = $result->fetch_assoc();

// Check if the campaign is found
if (!$campaign) {
    echo "Campaign not found.";
    exit;
}

// Fetch audio details
$result = $conn->query("SELECT * FROM audios WHERE campaign_id = $campaign_id");

// Check if the query was successful
if (!$result) {
    echo "Error fetching audio information: " . $conn->error;
    exit;
}

$audios = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Campaign</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
    <a href="admin.php" class="btn btn-secondary mb-3">Back</a>

        <h1>Edit Campaign</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        <form action="edit.php?id=<?= $campaign['id'] ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="campaign_id" value="<?= $campaign['id'] ?>">

            <div class="mb-3">
                <label for="campaign_name" class="form-label">Campaign Name:</label>
                <input type="text" class="form-control" name="campaign_name" value="<?= $campaign['name'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="campaign_description" class="form-label">Campaign Description:</label>
                <textarea class="form-control" name="campaign_description" rows="4" required><?= $campaign['description'] ?></textarea>
            </div>

            <div class="mb-3">
                <label for="token" class="form-label">Token:</label>
                <input type="text" class="form-control" name="token" value="<?= $campaign['token'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="campaign_image" class="form-label">Select campaign image (leave empty if not changing):</label>
                <input type="file" class="form-control" name="campaign_image" accept=".jpg, .png, .jpeg">
            </div>

            <div class="mb-3">
                <label for="audiofile" class="form-label">Select audio file (leave empty if not adding audio):</label>
                <input type="file" class="form-control" name="audiofile" accept=".mp3, .wav">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>

        <h2 class="mt-4">Audio Campaign</h2>
        <?php foreach ($audios as $audio): ?>
            <div class="mb-2">
                - <?= $audio['filename'] ?>
                <a href="download.php?id=<?= $audio['id'] ?>" class="btn btn-secondary btn-sm">Download</a>
                <a href="delete_audio.php?id=<?= $audio['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

