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
                <h2>Welcome to BeatIt</h2>
                <div class="input-group">
                    <label for="username">Email:</label>
                    <input type="email" id="email" name="email" placeholder="example@mail.com" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="1234..." required>
                </div>
                <div class="input-group">
                    <label for="password">Repat password:</label>
                    <input type="password" id="password-repeat" name="password-repeat" placeholder="1234..." required>
                    <?php
                    if ($_SESSION['errors']['general']) {
                    ?>
                        <span class="error-message"><?php echo $_SESSION['errors']['general']; ?></span>
                    <?php
                    }
                    ?>
                </div>
                <input type="hidden" name="action" value="register">
                <button type="submit">Register</button>
            </form>
            <p>Have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
    <?php
    include("./includes/footer.php")
    ?>
</body>

</html>