<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/head.php")
?>
<?php
include("./handlers/create_post_process.php")
?>
<?php
if (!$_SESSION['authorized']) {
    header('Location: ' . $BASE_URL . 'login.php');
    exit();
}
?>

<body>
    <?php
    include("./includes/nav.php")
    ?>
    <div class="CreatePost">
        <div class="container">
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="auth-logo">
                    <img src="assets/logo.png" alt="Logo">
                </div>
                <h2>Share your creativity</h2>
                <div class="input-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" placeholder="Title..." required>
                </div>
                <div class="input-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" rows="3" placeholder="Description..." required></textarea>
                </div>
                <div class="media">
                    <div class="input-group">
                        <label for="cover_url">Cover image:</label>
                        <input type="file" id="cover_url" name="cover_url" accept="image/png, image/jpg, image/jpeg" required>
                        <?php
                        if ($_SESSION['errors']['cover']) {
                        ?>
                            <span class="error-message"><?php echo $_SESSION['errors']['cover'][0]; ?></span>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="input-group">
                        <label for="audio_url">Audio</label>
                        <input type="file" id="audio_url" name="audio_url" accept="audio/mp3" required>
                        <?php
                        if ($_SESSION['errors']['audio']) {
                        ?>
                            <span class="error-message"><?php echo $_SESSION['errors']['audio'][0]; ?></span>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <input type="hidden" name="action" value="create_post">
                <button type="submit">Post</button>
            </form>
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