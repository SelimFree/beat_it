<?php
include("./includes/init.php");
?>
<?php
include("./classes/FieldType.php");
?>
<?php
include("./handlers/general_process.php")
?>

<?php
function fetchData($conn, $offset, $limit)
{
    try {

        $query = $conn->prepare(' SELECT posts.*, users.email, users.avatar_url, likes.user_id AS liked,
                                    (SELECT count(*) FROM likes WHERE likes.post_id = posts.id) as totalLikes,
                                    (SELECT count(*) FROM comments WHERE comments.post_id = posts.id) as totalComments
                                    FROM posts
                                    JOIN users ON posts.user_id = users.id
                                    LEFT JOIN likes ON posts.id = likes.post_id AND likes.user_id = ?
                                    WHERE posts.user_id IS NOT NULL
                                    ORDER BY posts.created_at DESC
                                    LIMIT ' . $limit . ' OFFSET ' . $offset * $limit);
        $query->execute([$_SESSION[FieldType::UserID]]);
        return $query->fetchAll();
    } catch (Exception $e) {
        return [];
    }
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
    } else if ($action === 'delete_post') {
        deletePost($connection, $_POST["id"]);
    } else if ($action === "like_post") {
        likePost($connection, $_POST["id"]);
    } else if ($action === "unlike_post") {
        unlikePost($connection, $_POST["id"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $posts = fetchData($connection, (int)$_GET["page"], $postPerPage);
}
