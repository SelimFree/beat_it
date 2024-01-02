<div class="posts">

    <?php
    foreach ($posts as $key => $post) {
    ?>
        <div class="container">
            <div class="container-header">
                <img src="<?php echo $post["avatar_url"] ?  $post["avatar_url"] : './assets/avatar.png' ?>" alt="Profile image">
                <div class="header-info">
                    <h4><?php echo $post["email"] ?></h4>
                    <span><?php echo formatDate($post["created_at"]) ?></span>
                </div>
                <?php
                if ($_SESSION["id"] == $post["user_id"]) {
                ?>
                    <div class="modify">
                        <button class="edit" onclick="editPost(event)" data-post="<?php echo $post["id"] ?>">Edit</button>
                        <button class="delete" onclick="deletePost(event)" data-post="<?php echo $post["id"] ?>">Delete</button>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="container-body">
                <div class="cover">
                    <img src="<?php echo $post["cover_url"] ?>" alt="">
                </div>
                <div class="action-bar">
                    <span><?php echo formatNumber(43243) ?></span>
                    <img src="./assets/comment.png" alt="Comment">
                    <span><?php echo formatNumber($post["totalLikes"]) ?></span>
                    <img src="<?php echo $post["liked"] ? "./assets/like_red.png" : "./assets/like.png" ?>" class="<?php echo $post["liked"] ? "like" : "unlike" ?>" data-post="<?php echo $post["id"] ?>" onclick="likePost(event)" alt="Like">
                </div>
                <div class="description" onclick="showMore(event)">
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
</div>
<button class="load-more" onclick="loadMore(event)" data-page="0">
    More
</button>
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
                <input type="range" onchange="moveSong(event)" />
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