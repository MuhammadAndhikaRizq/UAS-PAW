<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campaignId = $_POST['campaign_id'];
    $token = $_POST['token'];

    // Validate the token for the selected campaign
    $stmt = $conn->prepare("SELECT * FROM campaigns WHERE id = ? AND token = ?");
    $stmt->bind_param("is", $campaignId, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Valid token, grant access or redirect to the campaign page
        $_SESSION['user_campaign_id'] = $campaignId;
        header('Location: campaign.php'); // Redirect to the user's campaign page
        exit;
    } else {
        // Invalid token, handle accordingly (e.g., display an error message)
        $_SESSION['error_message'] = 'Invalid token for the selected campaign. Campaign ID: ' . $campaignId . ', Token: ' . $token;
        header('Location: user.php'); // Redirect to the user's page with an error message
        exit;
    }
}
?>
