<script>
  //dashboard
  let audioContext, analyser, dataArray, source, duration, animationFrameId, current, currentId;
  const player = document.getElementById('player');
  const visualizer = player.querySelector('.visualizer');
  const currentTime = player.querySelector("#current-time");
  const trackSlider = player.querySelector(".animate-track");

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
      return
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
    const player = document.getElementById('player');
    const playerTrackCover = player.querySelector(".track-cover img");
    const trackName = player.querySelector(".track-name");
    const trackCreator = player.querySelector(".track-creator");

    post.classList.add("active-song");

    player.classList.remove("hidden");

    playerTrackCover.src = coverUrl;

    trackName.innerText = audioTitle;
    trackCreator.innerText = audioSubtitle;

    playerTrackCover.classList.add("spin");
    playerTrackCover.classList.remove("not-spin");
    playPause.src = "./assets/pause.png"

    //Audio visualizer
    if (!audioContext) {
      const audioContextClass = window.AudioContext || window.webkitAudioContext;
      audioContext = new audioContextClass();
    } else {
      // Suspend the existing context if it's running
      if (audioContext.state === 'running') {
        audioContext.suspend();
      }
    }

    loadAndDecodeAudio(audioUrl)
      .then(buffer => {
        loadDuration(buffer.duration);
        playAudio(buffer)
      })
      .catch(error => console.error('Error loading audio:', error));
  }

  function updateTime() {
    clearInterval(currentId);

    currentId = setInterval(() => {

      currentTime.innerText = formatTime(current);
      const animationPercentage = Math.round((current / duration) * 100);
      trackSlider.style.width = `${animationPercentage}%`;
      current += 0.1;

      if (parseFloat(current.toFixed(1)) >= parseFloat(duration.toFixed(1))) {
        stopUpdateTime();
        nextSong();
      }
    }, 100);
  }

  function stopUpdateTime() {
    clearInterval(currentId);
  }

  function playPause() {
    //player elements
    const player = document.getElementById('player');
    const playPause = document.querySelector('#play-pause img');
    const playerTrackCover = document.querySelector(".track-cover img");

    if (audioContext.state === 'running') {
      audioContext.suspend().then(() => stopUpdateTime());
      playerTrackCover.classList.add("not-spin");
      playerTrackCover.classList.remove("spin");
      playPause.src = "./assets/play.png"
    } else {
      audioContext.resume().then(() => {
        updateVisualizer();
        updateTime();
      });
      playerTrackCover.classList.add("spin");
      playerTrackCover.classList.remove("not-spin");
      playPause.src = "./assets/pause.png";
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
    if (source) {
      const prevBuffer = source.buffer;
      source.stop();
      stopUpdateTime();
      source = audioContext.createBufferSource();
      source.connect(analyser);
      analyser.connect(audioContext.destination);
      source.buffer = prevBuffer;
      source.start(0, event.target.value);
      current = parseInt(event.target.value);
      currentTime.innerText = formatTime(current);
      const animationPercentage = Math.round((current / duration) * 100);
      trackSlider.style.width = `${animationPercentage}%`;
      updateTime();
      // Update visualizer after seeking
      updateVisualizer();
    }
  }

  function loadDuration(audioDuration) {
    const player = document.getElementById('player');
    const currentTime = player.querySelector("#current-time");
    const totalTime = player.querySelector("#total-time");
    const trackSlider = player.querySelector(".track input");

    trackSlider.min = 0;
    currentTime.innerText = formatTime(0);

    duration = audioDuration;
    trackSlider.max = duration;
    totalTime.innerText = formatTime(duration);

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

  function loadAndDecodeAudio(audioUrl) {
    return fetch(audioUrl)
      .then(response => response.arrayBuffer())
      .then(data => audioContext.decodeAudioData(data));
  }

  function playAudio(buffer) {
    current = 0;
    analyser = audioContext.createAnalyser();

    if (source) {
      source.stop();
    }

    source = audioContext.createBufferSource();

    source.connect(analyser);
    analyser.connect(audioContext.destination);

    source.buffer = buffer;
    source.start(0);

    if (audioContext.state !== 'running') {
      playPause();
    }

    analyser.fftSize = 256;
    const bufferLength = analyser.frequencyBinCount;
    dataArray = new Uint8Array(bufferLength);

    updateTime();
    updateVisualizer();
  }

  function updateVisualizer() {

    if (audioContext.state !== 'running') {
      return;
    }

    analyser.getByteFrequencyData(dataArray);

    // Calculate the average amplitude
    const sum = dataArray.reduce((acc, value) => acc + value, 0);
    const average = sum / dataArray.length;

    const scale = 1 + average / 500;
    visualizer.style.transform = `scale(${scale > 1 ? scale : 1})`;


    cancelAnimationFrame(animationFrameId);

    animationFrameId = requestAnimationFrame(updateVisualizer);
  }
  //dashboard
</script>