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
function avatar($connection)
{
    $result = [];
    //Creating validator object
    $prevalidator = new Validator([]);
    $prevalidator->validate($_POST);
    $avatarImagePath = $prevalidator->validateFile([$_FILES[FieldType::UserAvatar]], "avatar");
    $errors = $prevalidator->getMessages();

    foreach ($errors as $error) {
        if (count($error) > 0) {
            $errors["general"] = "Try different image";
            $result["errors"] = $errors;
            return $result;
        }
    }

    $query = $connection->prepare('UPDATE users SET avatar_url = ? WHERE id = ?');
    try {
        //Changing avatar
        $query->execute([$avatarImagePath, $_SESSION[FieldType::UserID]]);

        //Getting new avatar
        $query = $connection->prepare('SELECT * FROM users WHERE id = ?');
        $query->execute([$_SESSION[FieldType::UserID]]);
        $user = $query->fetch();
        if ($user) {
            $result[FieldType::UserAvatar] = $user[FieldType::UserAvatar];
        }
    } catch (Exception $e) {
        $errors["general"] = "Try again later";
        $result["errors"] = $errors;
    }
    return $result;
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: ' . $BASE_URL);
        exit();
    } else {
        $result = avatar($connection);
        if (!$result["errors"]) {
            $_SESSION[FieldType::UserAvatar] = $result[FieldType::UserAvatar];
            $_SESSION['errors'] = [];
        } else {
            $_SESSION['errors'] = $result["errors"];
        }
    }
}
