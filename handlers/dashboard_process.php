<?php
include("./includes/init.php");
?>
<?php
include("./classes/FieldType.php");
?>

<?php 
function formatNumber($num) {
    return number_format($num, 0, '.', ' ');
}
?>

<?php
$posts = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: ' . $BASE_URL);
        exit();
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $query = $connection->prepare(' SELECT posts.*, users.email, users.avatar_url
                                    FROM posts
                                    JOIN users ON posts.user_id = users.id
                                    WHERE posts.user_id IS NOT NULL
                                    ORDER BY posts.created_at DESC');
    $query->execute();
    $posts = $query->fetchAll();
}
