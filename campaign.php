<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Page</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="path/to/reset.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512...">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.8/plyr.css" />
</head>

<body>
    <?php
    session_start();
    include('db.php');

    if (!isset($_SESSION['user_campaign_id'])) {
        header('Location: user.php');
        exit;
    }

    $userCampaignId = $_SESSION['user_campaign_id'];

    // Fetch campaign details
    $result = $conn->query("SELECT * FROM campaigns WHERE id = $userCampaignId");

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
    $result = $conn->query("SELECT * FROM audios WHERE campaign_id = $userCampaignId");

    // Check if the query was successful
    if (!$result) {
        echo "Error fetching audio information: " . $conn->error;
        exit;
    }

    $audios = $result->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: user.php');
        exit;
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
        <a class="navbar-brand" href="#campaign-card">VoxQuest</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <form action="campaign.php" method="post" class="d-inline">
                    <button type="submit" name="logout" class="btn btn-danger">Reset</button>
                </form>
            </li>
            </ul>
        </div>
        </div>
    </nav>

    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="images/BgCyles2.png" alt="Campaign Image">
            <div class="carousel-caption d-none d-md-block">
                <h5 class="campaign-title">Welcome to <?= $campaign['name'] ?? 'Campaign' ?>'s Campaign!</h5>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="campaign-card">
            <img class="campaign-image" src="<?= $campaign['image_path'] ?? '' ?>" alt="<?= $campaign['name'] ?? 'Campaign' ?> Image">
            <div class="campaign-info">
                <p class="campaign-description"><?= $campaign['description'] ?? '' ?></p>
            </div>

            <div class="audio-player-container">
                <!-- Audio player code goes here -->
                <audio controls class="audio-player plyr--audio" id="audioPlayer<?= $campaign['id'] ?>"
                    data-plyr-config='{"controls": ["play", "next", "progress", "current-time", "mute", "volume", "settings", "fullscreen"]}'>
                    Your browser does not support the audio tag.
                </audio>
            </div>

            <ul class="audio-list">
                <?php foreach ($audios as $index => $audio): ?>
                    <!-- Audio item code goes here -->
                    <li class="audio-item">
                        <button class="play-button" data-audio-path="<?= $audio['path'] ?>" data-audio-target="audioPlayer<?= $campaign['id'] ?>" data-audio-index="<?= $index ?>">Play</button>
                        <a class="download-button" href="download.php?id=<?= $audio['id'] ?>">Download</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
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


    <!-- Plyr JS -->
    <script src="https://cdn.plyr.io/3.6.8/plyr.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const players = Array.from(document.querySelectorAll('.audio-player')).map(player => new Plyr(player, {
                controls: ['play', 'next', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen']
            }));

            // Handle Play button clicks
            const playButtons = document.querySelectorAll('.play-button');
            playButtons.forEach((playButton, index) => {
                playButton.addEventListener('click', () => {
                    // Pause other players before playing the selected one
                    players.forEach((player, i) => {
                        if (i !== index) {
                            player.pause();
                        }
                    });
                    // Play the selected player
                    players[index].play();
                });
            });
        });
    </script>
    <script src="assets/play.js"></script>
</body>

</html>
