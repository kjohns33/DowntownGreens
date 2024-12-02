<?php
session_start();
require_once('database/dbMessages.php');

if (!isset($_SESSION['_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userID = $_SESSION['_id'];
$sortOrder = $_POST['sortOrder'] ?? 'priority'; // Default to 'priority'

// Fetch messages based on sortOrder
switch ($sortOrder) {
    case 'open':
        $messages = get_user_messages_ordered_by_open_due_dates($userID, 'open');
        break;
    case 'due':
        $messages = get_user_messages_ordered_by_open_due_dates($userID, 'due');
        break;
    case 'nonsys':
        $messages = get_user_messages_nonsys_first($userID);
        break;
    case 'time':
        $messages = get_user_messages_ordered_by_time($userID);
        break;
    case 'unread':
        $messages = get_user_messages_ordered_by_unread($userID, 'wasRead');
        break;
    default:
        $messages = get_user_messages($userID); // Default to priority
}

echo json_encode(['success' => true, 'messages' => $messages]);
?>
