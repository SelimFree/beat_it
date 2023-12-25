<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/init.php");
?>
<?php
include("./includes/head.php")
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
    <h1>Welcome to User profile</h1>
    <?php
    include("./scripts/script.php")
    ?>
</body>

</html>