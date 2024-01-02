<script>
  //dashboard

  function loadMore(event) {
    let page = parseInt(event.target.dataset.page) + 1;
    event.target.dataset.page = page;
    const location = window.location.pathname.split("/").pop();
    fetch(`${location}?page=` + page, {
        method: 'GET',
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
        const tempElement = document.createElement('div');
        tempElement.innerHTML = data;
        const newPosts = tempElement.querySelector(".posts");
        const currentPosts = document.querySelector(".posts");
        currentPosts.innerHTML += newPosts.innerHTML;
      })
      .catch(error => {
        // Handle errors during the fetch operation
        console.error('Fetch error:', error);
      });
  }

  function deletePost(event) {
    const deleteButton = event.target;
    const postId = deleteButton.dataset.post;
    switch (deleteButton.innerText) {
      case "Confirm":
        deletePostHandler(postId);
        break;
      default:
        deleteButton.innerText = "Confirm";
        setTimeout(() => {
          deleteButton.innerText = "Delete";
        }, 5000)
        break;
    }
  }

  function deletePostHandler(id) {
    const formData = new FormData();
    formData.append("id", id);
    formData.append("action", "delete_post");

    fetch("dashboard.php", {
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
        location.reload();
      })
      .catch(error => {
        // Handle errors during the fetch operation
        console.error('Fetch error:', error);
      });
  }

  function editPost(event) {
    const deleteButton = event.target;
    const postId = deleteButton.dataset.post;
    switch (deleteButton.innerText) {
      case "Confirm":
        window.location.href = `edit_post.php?id=${postId}`;
        break;
      default:
        deleteButton.innerText = "Confirm";
        setTimeout(() => {
          deleteButton.innerText = "Edit";
        }, 5000)
        break;
    }
  }

  function likePost(event) {
    const likeButton = event.target;
    
    const postId = likeButton.dataset.post;
    let mode = "unlike";
    if (likeButton.classList.contains("unlike")) {
      mode = "like";
      likeButton.classList.remove("unlike")
      likeButton.classList.add("like")
      likeButton.src = "./assets/like_red.png"
    } else if (likeButton.classList.contains("like")) {
      likeButton.classList.remove("like")
      likeButton.classList.add("unlike")
      likeButton.src = "./assets/like.png"
    }
    likePostHandler(postId, mode);
  }

  function likePostHandler(id, mode) {
    const formData = new FormData();
    formData.append("id", id);
    formData.append("action", mode == "like" ? "like_post" : "unlike_post");

    fetch("dashboard.php", {
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
        //location.reload();
        console.log(data);
      })
      .catch(error => {
        // Handle errors during the fetch operation
        console.error('Fetch error:', error);
      });
  }


  function showMore(event) {
    event.target.classList.toggle('show-more');
  }


  function startSong(event) {
    const player = document.getElementById('player');
    const parent = event.target.parentElement.parentElement.parentElement;
    const current = document.querySelector(".active-song");

    if (!current) {
      initSong(parent);
    }

    if (parent === current) {
      player.classList.remove("hidden");
    } else {
      current.classList.remove("active-song");
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
    // playerTrackCover.classList.add("spin");

    trackName.innerText = audioTitle;
    trackCreator.innerText = audioSubtitle;
    // audioPlayer.play();
    playPause();
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