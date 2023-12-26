<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/init.php");
?>
<?php
include("./includes/head.php")
?>
<?php
include("./handlers/profile_process.php")
?>
<?php
if (!$_SESSION['authorized']) {
    header('Location: /webDevelopment/beat_it/login.php');
    exit();
}
?>

<body>
    <?php
    include("./includes/nav.php")
    ?>
    <div class="profile-container">
        <div class="profile-info">
            <img src="<?php echo $_SESSION["avatar_url"] ?  $_SESSION["avatar_url"] : './assets/avatar.png' ?>" alt="Profile Image" class="avatar" id="avatar-img">
            <div class="username-wrapper">
                <h2 class="profile-username"><?php echo $_SESSION["email"] ? $_SESSION["email"] : "Guest" ?></h2>
                <h4 class="profile-subtitle">One of our creative users</h4>
            </div>
            <div class="cards">
                <div class="card-item">
                    <img src="./assets/post.png" alt="Posts">
                    <span>123 Posts</span>
                </div>
                <div class="card-item">
                    <img src="./assets/comment.png" alt="Comment">
                    <span>123 Comments</span>
                </div>
                <div class="card-item">
                    <img src="./assets/like.png" alt="Like">
                    <span>123 Likes</span>
                </div>
            </div>

            <input type="file" id="fileInput" style="display: none;" accept="image/png, image/jpg, image/jpeg" onchange="displaySelectedImage()">
            <button class="change-avatar-btn" onclick="openFileInput()" id="changeAvatar">Change Picture</button>
            <?php
            if ($_SESSION['errors']['general']) {
            ?>
                <span class="error-message"><?php echo $_SESSION['errors']['general']; ?></span>
            <?php
            }
            ?>
        </div>
    </div>
    <?php
    include("./includes/footer.php")
    ?>
    <?php
    include("./scripts/script.php")
    ?>
</body>

</html>