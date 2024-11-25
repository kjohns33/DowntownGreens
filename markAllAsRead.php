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
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'User ID is missing.']);
        exit;
    }
    if (markAllAsRead($id)) {
        echo json_encode(['success' => true, 'message' => 'All notifications marked as read.']);
        exit;
    }
    echo json_encode(['success' => false, 'message' => 'Failed to mark notifications as read.']);
?>