<?php
    session_cache_expire(30);
    session_start();

    if ($_SESSION['access_level'] < 2 || $_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location: index.php');
        die();
    }
    require_once('database/dbEvents.php');
    require_once('database/dbMessages.php');
    require_once('include/input-validation.php');
    $args = sanitize($_POST);
    $id = $args['id'];
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $body = isset($_POST['body']) ? $_POST['body'] : '';
    $send_date = isset($_POST['send_date']) ? $_POST['send_date'] : '';
    $priority = isset($_POST['priority']) ? $_POST['priority'] : '';
    $send_to = isset($_POST['send_to']) ? (array)$_POST['send_to'] : [];
    if (!$id || !$title || !$body || !$send_date || !$priority || empty($send_to)) {
        header('Location: inbox.php?createNotifFailure');
        die();
    }
    if (create_notification($id, $title, $body, $send_date, $priority, $send_to)) {
        header('Location: inbox.php?createNotifSuccess');
        die();
    }
    header('Location: inbox.php');
?>