<?php
include("./includes/init.php");
?>
<?php
include("./classes/FieldType.php");
?>

<?php
function formatNumber($num)
{
    return number_format($num, 0, '.', ' ');
}

function formatDate($date)
{
    $dateTime = new DateTime($date);
    $formattedDate = $dateTime->format('d.m.Y, H:i');
    return $formattedDate;
}

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

function deletePost($conn, $id)
{
    try {

        //getting post data 
        $query = $conn->prepare(' SELECT * FROM posts WHERE id = ? AND user_id = ?');
        $query->execute([$id, $_SESSION[FieldType::UserID]]);
        $delPost = $query->fetch();

        //trying to delete
        $query = $conn->prepare(' DELETE FROM posts WHERE id = ? AND user_id = ?');
        $res = $query->execute([$id, $_SESSION[FieldType::UserID]]);

        if ($res) {
            unlink($delPost[FieldType::CoverImage]);
            unlink($delPost[FieldType::Audio]);
            deleteLikes($conn, $id);
            deleteComments($conn, $id);
        }
    } catch (Exception $e) {
        // pass
    }
}

function deleteLikes($conn, $id)
{
    try {
        //trying to delete
        $query = $conn->prepare(' DELETE FROM likes WHERE post_id = ?');
        $res = $query->execute([$id]);
    } catch (Exception $e) {
        // pass
    }
}

function deleteComments($conn, $id)
{
    try {
        //trying to delete
        $query = $conn->prepare(' DELETE FROM comments WHERE post_id = ?');
        $res = $query->execute([$id]);
    } catch (Exception $e) {
        // pass
    }
}

function likePost($conn, $id)
{
    try {
        $query = $conn->prepare('INSERT INTO likes(post_id, user_id) VALUES(?, ?)');
        $query->execute([$id, $_SESSION[FieldType::UserID]]);
    } catch (Exception $e) {
        // pass
    }
}

function unlikePost($conn, $id)
{
    try {
        $query = $conn->prepare('DELETE FROM likes WHERE post_id = ? AND user_id = ?');
        $query->execute([$id, $_SESSION[FieldType::UserID]]);
    } catch (Exception $e) {
        // pass
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
