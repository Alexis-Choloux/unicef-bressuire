const video = document.getElementById("heroVideo");

const btn = document.getElementById("heroBtn");

function playPause() {
  if (video.paused) {
    video.play();
    btn.innerHTML = "<i class=\"fas fa-pause-circle fa-7x\"></i>";
  } else {
    video.pause();
    btn.innerHTML = "<i class=\"fas fa-play-circle fa-7x\"></i>";
  }
}