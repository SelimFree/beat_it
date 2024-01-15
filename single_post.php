<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/init.php");
?>
<?php
include("./includes/head.php")
?>
<?php
include("./handlers/single_post_process.php")
?>

<body>
    <?php
    include("./includes/nav.php")
    ?>
    <div class="Post">
        <div class="container">
            <div class="container-header">
                <img src="<?php echo $currentPost["avatar_url"] ?  $currentPost["avatar_url"] : './assets/avatar.png' ?>" alt="Profile image" loading="lazy">
                <div class="header-info">
                    <h4><?php echo $currentPost["email"] ?></h4>
                    <span><?php echo formatDate($currentPost["created_at"]) ?></span>
                </div>
                <?php
                if ($_SESSION["id"] == $currentPost["user_id"]) {
                ?>
                    <div class="modify">
                        <button class="edit" onclick="editPost(event)" data-post="<?php echo $currentPost["id"] ?>">Edit</button>
                        <button class="delete" onclick="deletePost(event)" data-post="<?php echo $currentPost["id"] ?>">Delete</button>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="container-body">
                <div class="cover">
                    <img src="<?php echo $currentPost["cover_url"] ?>" alt="" loading="lazy">
                </div>
                <div class="action-bar">
                    <span><?php echo formatNumber($currentPost["totalComments"]) ?></span>
                    <img src="./assets/comment.png" alt="Comment">
                    <span><?php echo formatNumber($currentPost["totalLikes"]) ?></span>
                    <img src="<?php echo $currentPost["liked"] ? "./assets/like_red.png" : "./assets/like.png" ?>" class="<?php echo $currentPost["liked"] ? "like" : "unlike" ?>" data-post="<?php echo $currentPost["id"] ?>" onclick="<?php echo $_SESSION["authorized"] ? "likePost(event)" : "window.location.href = 'login.php'" ?>" alt="Like">
                </div>
                <div class="description" onclick="showMore(event)">
                    <?php echo $currentPost["description"] ?>
                </div>
                <div class="audio-content">
                    <button class="play-btn" onclick="startSong(event)">Play</button>
                    <div class="audio-info">
                        <div class="audio-title"><?php echo $currentPost["title"] ?></div>
                        <div class="audio-subtitle"><?php echo $currentPost["email"] ?></div>
                    </div>
                    <span style="display: none" class="url-holder"><?php echo $currentPost["audio_url"] ?></span>
                </div>
            </div>
            <div class="comments">
                <h2>Comments</h2>
                <div class="comment-input">
                    <?php
                    if ($_SESSION['authorized']) {
                    ?>
                        <input type="text" id="title" name="title" placeholder="Comment..." maxlength="256">
                        <button onclick="postComment(event)" data-id="<?php echo $currentPost["id"] ?>">Send</button>
                    <?php
                    } else {
                    ?>
                        <input type="text" id="title" name="title" placeholder="Comment..." maxlength="256" disabled>
                        <button disabled>Send</button>
                    <?php
                    }
                    ?>
                </div>
                <div class="comments-content">
                    <?php
                    foreach ($comments as $key => $comment) {
                    ?>
                        <div class="comment">
                            <div class="user-img">
                                <img src="<?php echo $comment["avatar_url"] ?  $comment["avatar_url"] : './assets/avatar.png' ?>" alt="Profile image" loading="lazy">
                            </div>
                            <div class="comment-body">
                                <div class="author">
                                    <span class="author-email"><?php echo $comment["email"] ?></span>
                                    <span class="created-at"><?php echo formatDate($comment["created_at"]) ?></span>
                                </div>
                                <div class="content">
                                    <?php echo $comment["comment"] ?>
                                </div>
                            </div>

                        </div>
                    <?php
                    }
                    ?>
                </div>
                <button class="<?php echo count($comments) == 0 ? "hide" : "load-more" ?>" onclick="loadMoreComments(event)" data-page="0" data-id="<?php echo $currentPost["id"] ?>">
                    More
                </button>
            </div>
        </div>
        <div class="player hidden" id="player">
            <button class="close-player" onclick="closePlayer()">
                <img src="./assets/close.png">
            </button>
            <div class="track-cover">
                <img loading="lazy">
                <div class="visualizer"></div>
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
    </div>
    <?php
    include("./includes/footer.php")
    ?>
    <?php
    include("./scripts/script.php")
    ?>
    <?php
    include("./scripts/dashboard_script.php")
    ?>
    <?php
    include("./scripts/single_post_script.php")
    ?>
</body>

</html>