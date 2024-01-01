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
                                        WHERE posts.user_id = ?
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
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $posts = fetchData($connection, (int)$_GET["page"], $postPerPage);
}
