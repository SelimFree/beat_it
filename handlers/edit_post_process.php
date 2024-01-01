<?php
include("./includes/init.php");
?>
<?php
include("./classes/Validator.php");
?>
<?php
include("./classes/ValidationRule.php");
?>
<?php
include("./classes/FieldType.php");
?>
<?php

function preparePostUpdateQuery($params, $id) {
    $res = [];

    $str = "";
    $vals = [];
    foreach ($params as $key => $value) {
        $str = $str . $key . "= ?,";
        array_push($vals, $value);
    }
    $str = substr($str, 0, -1);
    $str = 'UPDATE posts SET ' . $str . 'WHERE id = ? AND user_id = ?';
    array_push($vals, $id);
    array_push($vals, $_SESSION[FieldType::UserID]);
    
    $res["query"] = $str;
    $res["params"] = $vals;
    return $res;
}

function update($connection, $id)
{
    $result = [];
    $queryParams = [];
    //Creating validator object
    $prevalidator = new Validator([
        FieldType::Title => [
            ValidationRule::Required => true,
        ],
        FieldType::Description => [
            ValidationRule::Required => true
        ]
    ]);
    $prevalidator->validate($_POST);

    $queryParams[FieldType::Title] = $_POST[FieldType::Title];
    $queryParams[FieldType::Description] = $_POST[FieldType::Description];

    if ($_FILES[FieldType::CoverImage]["name"]) {
        $coverImagePath = $prevalidator->validateFile([$_FILES[FieldType::CoverImage]], "cover");
        $queryParams[FieldType::CoverImage] = $coverImagePath;
    }
    if ($_FILES[FieldType::Audio]["name"]) {
        $audioPath = $prevalidator->validateFile([$_FILES[FieldType::Audio]], "audio");
        $queryParams[FieldType::Audio] = $audioPath;
    }
    $errors = $prevalidator->getMessages();

    foreach ($errors as $error) {
        if (count($error) > 0) {
            $result["errors"] = $errors;
            return $result;
        }
    }

    try {
        //getting post data 
        $query = $connection->prepare(' SELECT * FROM posts WHERE id = ? AND user_id = ?');
        $query->execute([$id, $_SESSION[FieldType::UserID]]);
        $editPost = $query->fetch();

        $template = preparePostUpdateQuery($queryParams, $id);
        $query = $connection->prepare($template["query"]);
        $res = $query->execute($template["params"]);

        $query = $connection->prepare(' SELECT * FROM posts WHERE id = ? AND user_id = ?');
        $query->execute([$id, $_SESSION[FieldType::UserID]]);
        $newPost = $query->fetch();

        if ($res) {
            if ($editPost[FieldType::CoverImage] != $newPost[FieldType::CoverImage]) {
                unlink($editPost[FieldType::CoverImage]);
            }

            if ($editPost[FieldType::Audio] != $newPost[FieldType::Audio]) {
                unlink($editPost[FieldType::Audio]);
            }

            $result["post_edited"] = true;
            return $result;
        }
    } catch (Exception $e) {
        $errors["general"] = "Try again later";
        $result["errors"] = $errors;
    }
    return $result;
}

function fetchData($conn, $id)
{
    try {

        $query = $conn->prepare(' SELECT * FROM posts WHERE id = ? AND user_id = ?');
        $query->execute([$id, $_SESSION[FieldType::UserID]]);
        return $query->fetch();
    } catch (Exception $e) {
        return null;
    }
}
?>

<?php
$currentPost;
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!$_GET["id"]) {
        header('Location: ' . $BASE_URL);
        exit();
    }
    $currentPost = fetchData($connection, $_GET["id"]);
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: ' . $BASE_URL);
        exit();
    } else if ($action === 'edit_post') {
        $result = update($connection, $_POST['id']);
        if (!$result["errors"] && $result["post_edited"] ) {
            $_SESSION['errors'] = [];
            header('Location: ' . $BASE_URL);
            exit();
        } else {
            $_SESSION['errors'] = $result["errors"];
        }
    }
}
