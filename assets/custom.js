// File JavaScript terpisah untuk logika kustom
document.addEventListener('DOMContentLoaded', function () {
    var playButtons = document.querySelectorAll('.play-button');

    playButtons.forEach(function (playButton) {
        playButton.addEventListener('click', function () {
            console.log("Button clicked");
            var audioPath = this.getAttribute('data-audio-path');
            var audioTarget = this.getAttribute('data-audio-target');

            console.log("Audio path:", audioPath);
            console.log("Audio target:", audioTarget);

            var audioPlayer = document.getElementById(audioTarget);
            if (audioPlayer) {
                console.log("Audio player found");

                audioPlayer.src = audioPath;
                audioPlayer.load();
                audioPlayer.play().then(() => {
                    console.log("Audio is playing");
                }).catch((error) => {
                    console.error("Failed to play audio:", error);
                });
            } else {
                console.error("Audio player with ID " + audioTarget + " not found.");
            }
        });
    });
});
