<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/init.php");
?>
<?php
include("./includes/head.php")
?>
<?php
include("./handlers/dashboard_process.php")
?>

<body>
    <?php
    include("./includes/nav.php")
    ?>
    <div class="Dashboard">
        <?php
        foreach ($posts as $key => $post) {
        ?>
            <div class="container">
                <div class="container-header">
                    <img src="<?php echo $post["avatar_url"] ?  $post["avatar_url"] : './assets/avatar.png' ?>" alt="Profile image">
                    <div class="header-info">
                        <h4><?php echo $post["email"] ?></h4>
                        <span><?php echo $post["created_at"] ?></span>
                    </div>
                </div>
                <div class="container-body">
                    <div class="cover">
                        <img src="<?php echo $post["cover_url"] ?>" alt="">
                    </div>
                    <div class="action-bar">
                        <span><?php echo formatNumber(43243) ?></span>
                        <img src="./assets/comment.png" alt="Comment">
                        <span><?php echo formatNumber(1231) ?></span>
                        <img src="./assets/like.png" alt="Comment">
                    </div>
                    <div class="description">
                        <?php echo $post["description"] ?>
                    </div>
                    <div class="audio-content">
                        <button class="play-btn" onclick="startSong(event)">Play</button>
                        <div class="audio-info">
                            <div class="audio-title"><?php echo $post["title"] ?></div>
                            <div class="audio-subtitle"><?php echo $post["email"] ?></div>
                        </div>
                        <span style="display: none" class="url-holder"><?php echo $post["audio_url"] ?></span>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="player hidden" id="player">
            <button class="close-player" onclick="closePlayer()">
                <img src="./assets/close.png">
            </button>
            <div class="track-cover">
                <img>
            </div>
            <div class="track-info">
                <div class="track-name"></div>
                <div class="track-creator"></div>
            </div>
            <div class="song-controls">
                <div class="time-control">
                    <p id="current-time">0:00</p>
                    <div class="track">
                        <input type="range" onchange="moveSong(event)"/>
                        <div class="animate-track"></div>
                    </div>
                    <p id="total-time">0:00</p>
                </div>
                <div class="buttons">
                    <button id="left" onclick="prevSong()">
                        <img src="./assets/left.png">
                    </button>
                    <button id="play-pause" onclick="playPause()">
                        <img src="./assets/pause.png">
                    </button>
                    <button id="right" onclick="nextSong()">
                        <img src="./assets/right.png">
                    </button>
                </div>
            </div>
        </div>
        <audio id="audioPlayer" onloadedmetadata="loadMeta(event)" ontimeupdate="updateTime(event)" onended="nextSong()"></audio>
    </div>
    <?php
    include("./includes/footer.php")
    ?>
    <?php
    include("./scripts/script.php")
    ?>
</body>

</html>