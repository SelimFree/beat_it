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