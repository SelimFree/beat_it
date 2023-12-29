<!DOCTYPE html>
<html lang="en">
<?php
include("./includes/head.php")
?>
<?php
include("./handlers/auth_process.php")
?>
<?php
if ($_SESSION['authorized']) {
    header('Location: ' . $BASE_URL . 'dashboard.php');
    exit();
}
?>

<body>
    <div class="Auth">
        <div class="container">
            <form action="#" method="post">
                <div class="auth-logo">
                    <img src="assets/logo.png" alt="Logo">
                </div>
                <h2>Welcome Back</h2>
                <div class="input-group">
                    <label for="username">Email:</label>
                    <input type="email" id="email" name="email" placeholder="example@mail.com" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="1234..." required>
                    <?php
                    if ($_SESSION['errors']['general']) {
                    ?>
                        <span class="error-message"><?php echo $_SESSION['errors']['general']; ?></span>
                    <?php
                    }
                    ?>
                </div>
                <input type="hidden" name="action" value="login">
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
    <?php
    include("./includes/footer.php")
    ?>
</body>

</html>