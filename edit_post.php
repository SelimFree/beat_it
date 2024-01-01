<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/head.php")
?>
<?php
include("./handlers/edit_post_process.php")
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
    <div class="EditPost">
        <div class="container">
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="auth-logo">
                    <img src="assets/logo.png" alt="Logo">
                </div>
                <h2>That was awesome</h2>
                <div class="input-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" placeholder="Title..." maxlength="256" value="<?php echo $currentPost["title"]; ?>" required>
                </div>
                <div class="input-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" rows="3" placeholder="Description..." maxlength="1024" required><?php echo $currentPost["description"]; ?></textarea>
                </div>
                <div class="media">
                    <div class="input-group">
                        <label for="cover_url">Cover image:</label>
                        <input type="file" id="cover_url" name="cover_url" accept="image/png, image/jpg, image/jpeg">
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
                        <input type="file" id="audio_url" name="audio_url" accept="audio/mp3">
                        <?php
                        if ($_SESSION['errors']['audio']) {
                        ?>
                            <span class="error-message"><?php echo $_SESSION['errors']['audio'][0]; ?></span>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <input type="hidden" name="action" value="edit_post">
                <input type="hidden" name="id" value="<?php echo $currentPost["id"]; ?>">
                <div class="edit-buttons">
                    <button type="submit">Save</button>
                    <button onclick="cancel()">Cancel</button>
                </div>
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