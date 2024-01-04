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