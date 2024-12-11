<?php
    session_cache_expire(30);
    session_start();

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location: index.php');
        die();
    }
    require_once('database/dbEvents.php');
    require_once('database/dbMessages.php');
    require_once('include/input-validation.php');
    $args = sanitize($_POST);
    $id = $args['id'];
    $delete_array = [];
    if (isset($_POST['delete_array']) && !empty($_POST['delete_array'])) {
        $delete_array = explode(',', $_POST['delete_array']);
    }
    if (!$id || !isset($_POST['delete_array'])) {
        header('Location: inbox.php?deleteMessagesFailure');
        die();
    }
    if (empty($delete_array)) {
        if (deleteAll($id)) {
            header('Location: inbox.php?deleteMessagesSuccess');
            die();
        }
    } else {
        if (deleteSelected($delete_array)) {
            header('Location: inbox.php?deleteMessagesSuccess');
            die();
        }
    }
    header('Location: inbox.php');
?>