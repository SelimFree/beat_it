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

function fetchData($conn, $offset, $limit)
{
    try {

        $query = $conn->prepare(' SELECT posts.*, users.email, users.avatar_url
                                        FROM posts
                                        JOIN users ON posts.user_id = users.id
                                        WHERE posts.user_id IS NOT NULL
                                        ORDER BY posts.created_at DESC
                                        LIMIT ' . $limit . ' OFFSET ' . $offset * $limit);
        $query->execute();
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
        }
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
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $posts = fetchData($connection, (int)$_GET["page"], $postPerPage);
}
