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

function fetchComments($conn, $id, $offset, $limit)
{
    try {

        $query = $conn->prepare(' SELECT comments.*, users.email, users.avatar_url
                                    FROM comments
                                    JOIN users ON comments.user_id = users.id
                                    WHERE comments.post_id = ?
                                    ORDER BY comments.created_at DESC
                                    LIMIT ' . $limit . ' OFFSET ' . $offset * $limit);
        $query->execute([$id]);
        return $query->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function fetchData($conn, $id)
{
    try {

        $query = $conn->prepare(' SELECT posts.*, users.email, users.avatar_url, likes.user_id AS liked,
                                    (SELECT count(*) FROM likes WHERE likes.post_id = posts.id) as totalLikes,
                                    (SELECT count(*) FROM comments WHERE comments.post_id = posts.id) as totalComments
                                    FROM posts
                                    JOIN users ON posts.user_id = users.id
                                    LEFT JOIN likes ON posts.id = likes.post_id AND likes.user_id = ?
                                    WHERE posts.id = ?
                                    ORDER BY posts.created_at DESC');
        $query->execute([$_SESSION[FieldType::UserID], $id]);
        return $query->fetch();
    } catch (Exception $e) {
    }
}

function postComment($conn, $id, $comment)
{
    try {
        $query = $conn->prepare('INSERT INTO comments(post_id, user_id, comment) VALUES(?, ?, ?)');
        $query->execute([$id, $_SESSION[FieldType::UserID], $comment]);
    } catch (Exception $e) {
        // pass
    }
}

?>

<?php
$currentPost;
$comments = [];
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
    } else if ($action === "post_comment") {
        postComment($connection, $_POST["id"], $_POST["comment"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $currentPost = fetchData($connection, $_GET["id"]);
    $comments = fetchComments($connection, $_GET["id"], (int)$_GET["page"], $commentPerPage);
}
