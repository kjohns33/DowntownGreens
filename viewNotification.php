<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    ini_set("display_errors",1);
    error_reporting(E_ALL);
    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
    if (!$loggedIn) {
        header('Location: login.php');
        die();
    }
    if (!isset($_GET['id'])) {
        header('Location: inbox.php');
        die();
    }
    $id = intval($_GET['id']);
    if ($id < 1) {
        header('Location: inbox.php');
        die();
    }
    require_once('database/dbMessages.php');
    $message = get_message_by_id($id);
    if (!$message || $message['recipientID'] != $_SESSION['_id']) {
        header('Location: inbox.php');
        die();
    }
    mark_read($id);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Downtown Greens | View Notification</title>
        <link rel="stylesheet" href="css/messages.css"></link>
        <link rel="stylesheet" href="css/event.css" type="text/css" />
        <?php if ($accessLevel >= 2) : ?>
            <script src="js/event.js"></script>
        <?php endif ?>
        <script src="js/messages.js"></script>
    </head>
    <body>
        <?php if ($accessLevel >= 2) : ?>
            <div id="delete-confirmation-wrapper" class="hidden">
                <div id="delete-confirmation">
                    <p>Are you sure you want to delete this notification?</p>
                    <p>This action cannot be undone.</p>

                    <?php echo "<form method=\"post\" action=\"deleteNotification.php?id=" . $id . "\">" ?>
                        <input type="submit" value="Delete Notification">
                        <input type="hidden" name="id" value="<?= $id ?>">
                    </form>
                    <button id="delete-cancel">Cancel</button>
                </div>
            </div>
        <?php endif ?>
        <?php require_once('header.php') ?>
        <h1>View Notification</h1>
        <main class="message">
            <?php 
                require_once('database/dbPersons.php');
                require_once('include/output.php');
                require_once('database/dbEvents.php');
            ?>
            <p class="sender-time-line"><span><label>From </label><?php echo get_name_from_id($message['senderID']) ?></span>
            <span><label>Received </label><?php 
                    $time = $message['time'];
                    echo $time;
                ?></span>
            </p>
            <div class="message-body">
                <?php 
                $grant_name = get_grant_name_from_messageID($id);
                $grant_id = get_grant_id_from_messageID($id);

                if ($grant_name) {
                    ?>
                    <h2><?php echo $message['title']; ?></h2>
                    <div class="inline-elements">
                    <?php $body = "<a href=\"event.php?id=" . $grant_id . "\">" . $grant_name . "</a>" . substr($message['body'], strlen($grant_name));
                    echo $body;
                    ?></div>
                    <?php
                } else { 
                    ?>
                    <h2><?php echo $message['title']; ?></h2>
                    <p><?php echo prepareMessageBody($message['body']) ?></p>
                    <?php 
                } 
                ?>
            </div>
            <!--<button id="delete-button" data-message-id="<?php //echo $id ?>">Delete Notification</button>-->
            <button onclick="showDeleteConfirmation()">Delete Notification</button>
            <a class="button cancel" href="inbox.php">Return to Inbox</a>
        </main>
    </body>
</html>