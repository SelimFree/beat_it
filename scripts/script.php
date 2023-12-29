<script>
  //navigation
  function toggleMenu() {
    var dropdownMenu = document.getElementById("dropdownMenu");
    dropdownMenu.classList.toggle("show");
  }

  // Close the dropdown menu if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches(".menu-icon")) {
      var dropdownMenu = document.getElementById("dropdownMenu");
      if (dropdownMenu.classList.contains("show")) {
        dropdownMenu.classList.remove("show");
      }
    }
  };
  //navigation

  //profile
  function openFileInput() {
    const changeButton = document.getElementById('changeAvatar');

    switch (changeButton.innerText) {
      case "Save":
        handleUpdateAvatar();
        break;
      default:
        document.getElementById('fileInput').click();
        break;
    }
  }

  function displaySelectedImage() {
    const fileInput = document.getElementById('fileInput');
    const previewImage = document.getElementById('avatar-img');
    const changeButton = document.getElementById('changeAvatar');

    const selectedFile = fileInput.files[0];

    if (selectedFile) {
      const reader = new FileReader();

      reader.onload = function(e) {
        previewImage.src = e.target.result;
      };
      reader.readAsDataURL(selectedFile);

      changeButton.innerText = "Save"
    }
  }

  function handleUpdateAvatar() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    const formData = new FormData();
    const uniqueFileName = 'user_avatar_' + Date.now() + '_' + file.name;
    formData.append('avatar_url', file, uniqueFileName);

    fetch('profile.php', {
        method: 'POST',
        body: formData,
      })
      .then(response => {
        // Check if the request was successful (status code 200)
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        // Parse the response as JSON or text, depending on the content type
        return response.text(); // or response.text() if it's not JSON
      })
      .then(data => {
        // Handle the data from the response
        console.log(data);
        location.reload();
      })
      .catch(error => {
        // Handle errors during the fetch operation
        console.error('Fetch error:', error);
      });
  }
  //profile

  //dashboard
  function startSong(event) {
    const player = document.getElementById('player');
    const parent = event.target.parentElement.parentElement.parentElement;
    const current = document.querySelector(".active-song");
    if (parent === current) {
      player.classList.remove("hidden");
    } else {
      initSong(parent);
    }
  }

  function initSong(post) {
    //post elements
    const coverUrl = post.querySelector(".cover img").src;
    const audioUrl = post.querySelector(".url-holder").innerText;
    const audioTitle = post.querySelector(".audio-title").innerText;
    const audioSubtitle = post.querySelector(".audio-subtitle").innerText;

    //player elements
    const audioPlayer = document.getElementById('audioPlayer');
    const player = document.getElementById('player');
    const playerTrackCover = player.querySelector(".track-cover img");
    const trackName = player.querySelector(".track-name");
    const trackCreator = player.querySelector(".track-creator");

    post.classList.add("active-song");

    player.classList.remove("hidden");

    if (!audioPlayer.paused) {
      audioPlayer.paused = true;
    }
    audioPlayer.src = audioUrl;
    playerTrackCover.src = coverUrl;
    playerTrackCover.classList.add("spin");

    trackName.innerText = audioTitle;
    trackCreator.innerText = audioSubtitle;
    audioPlayer.play();
  }

  function playPause() {
    //player elements
    const audioPlayer = document.getElementById('audioPlayer');
    const player = document.getElementById('player');
    const playPause = document.querySelector('#play-pause img');
    const playerTrackCover = document.querySelector(".track-cover img");

    if (audioPlayer.paused) {
      audioPlayer.play();
      playerTrackCover.classList.add("spin");
      playerTrackCover.classList.remove("not-spin");
      playPause.src = "./assets/pause.png"
    } else {
      audioPlayer.pause();
      playerTrackCover.classList.add("not-spin");
      playerTrackCover.classList.remove("spin");
      playPause.src = "./assets/play.png"
    }
  }


  function prevSong() {
    const current = document.querySelector(".active-song");
    const prev = current.previousElementSibling;
    if (prev?.classList?.contains("container")) {
      current.classList.remove("active-song");
      initSong(prev);
    }
  }

  function nextSong() {
    const current = document.querySelector(".active-song");
    current.classList.remove("active-song");
    const next = current.nextElementSibling;
    if (next?.classList?.contains("container")) {
      initSong(next);
    } else {
      initSong(document.querySelector(".container"));
    }
  }

  function moveSong(event) {
    const player = document.getElementById('player');
    const audioPlayer = document.getElementById('audioPlayer');
    const currentTime = player.querySelector("#current-time");
    const trackSlider = player.querySelector(".animate-track");

    audioPlayer.pause();
    const animationPercentage = Math.round((event.target.value / audioPlayer.duration) * 100);
    audioPlayer.currentTime = event.target.value;
    trackSlider.style.width = `${animationPercentage}%`;
    audioPlayer.play();
  }

  function loadMeta(event) {
    const player = document.getElementById('player');
    const currentTime = player.querySelector("#current-time");
    const totalTime = player.querySelector("#total-time");
    const trackSlider = player.querySelector(".track input");

    trackSlider.min = 0;
    currentTime.innerText = formatTime(0);

    trackSlider.max = event.target.duration;
    totalTime.innerText = formatTime(event.target.duration);

  }

  function updateTime(event) {
    const player = document.getElementById('player');
    const currentTime = player.querySelector("#current-time");
    const trackSlider = player.querySelector(".animate-track");
    const duration = event.target.duration;

    currentTime.innerText = formatTime(event.target.currentTime);
    const animationPercentage = Math.round((event.target.currentTime / duration) * 100);
    trackSlider.style.width = `${animationPercentage}%`;

  }

  function formatTime(decimalSeconds) {
    decimalSeconds = Math.round(decimalSeconds);
    const minutes = Math.floor(decimalSeconds / 60);
    const seconds = Math.floor(decimalSeconds % 60);
    const formattedMinutes = minutes.toString().padStart(2, "0");
    const formattedSeconds = seconds.toString().padStart(2, "0");
    return `${formattedMinutes}:${formattedSeconds}`;
  };

  function closePlayer() {
    const player = document.getElementById('player');
    player.classList.add("hidden");
  }
  //dashboard
</script>