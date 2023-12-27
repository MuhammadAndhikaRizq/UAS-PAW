document.addEventListener('DOMContentLoaded', function () {
    var playButtons = document.querySelectorAll('.play-button');

    playButtons.forEach(function (playButton) {
        playButton.addEventListener('click', function () {
            console.log("Button clicked"); // <-- Tambahkan log ini
            var audioPath = this.getAttribute('data-audio-path');
            var audioTarget = this.getAttribute('data-audio-target');

            console.log("Audio path:", audioPath); // <-- Tambahkan log ini
            console.log("Audio target:", audioTarget); // <-- Tambahkan log ini

            // Cek apakah audio player dengan ID tersebut ada
            var audioPlayer = document.getElementById(audioTarget);
            if (audioPlayer) {
                console.log("Audio player ditemukan"); // <-- Tambahkan log ini

                // Set source pada audio player yang sesuai
                audioPlayer.src = audioPath;

                // Memastikan bahwa audio sudah dimuat sebelum diputar
                audioPlayer.load();

                // Play audio
                audioPlayer.play().then(() => {
                    console.log("Audio sedang diputar");
                }).catch((error) => {
                    console.error("Gagal memutar audio:", error);
                });
            } else {
                console.error("Audio player dengan ID " + audioTarget + " tidak ditemukan.");
            }
        });
    });
});
