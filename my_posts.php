<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/init.php");
?>
<?php
include("./includes/head.php")
?>
<?php
include("./handlers/my_posts_process.php")
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
    <div class="MyPosts">
        <?php
        include("./includes/post_list.php")
        ?>
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
</body>

</html>