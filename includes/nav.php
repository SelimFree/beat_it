<nav class="navbar">
    <a class="logo" href="/webDevelopment/beat_it/">
        <img src="assets/logo.png" alt="Logo">
        <h1>BeatIt</h1>
    </a>
    <div class="actions">
        <div class="user-profile">
            <img src="<?php echo $_SESSION["avatar_url"] ? $_SESSION["avatar_url"] : './assets/avatar.png' ?>" alt="Profile Image" loading="lazy">
            <span class="username"><?php echo $_SESSION["email"] ? $_SESSION["email"] : "Guest" ?></span>
        </div>
        <?php
        if ($_SESSION['authorized']) {
        ?>
            <div class="menu-container">
                <div class="menu-icon" onclick="toggleMenu()">☰</div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <form action="#" method="post">
                        <ul>
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="create_post.php">Create post</a></li>
                            <li><a href="my_posts.php">My posts</a></li>
                            <li>
                                <button class="logout" type="submit">
                                    Logout
                                </button>
                            </li>
                        </ul>
                        <input type="hidden" value="logout" name="action">
                    </form>
                </div>
            </div>
        <?php
        } else {
        ?>
            <a class="login" href="login.php">Login</a>
        <?php
        }
        ?>
    </div>
</nav>