<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/grant.js"></script>
        <title>ODHS Medicine Tracker | Inbox</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Grants</h1>
        <main class="general">
            <h2>Your Grants</h2>
            <?php 
                require_once('database/dbEvents.php');
                $grants = fetch_events_as_array();
                if (count($grants) > 0): ?>
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th style="width:1px">From</th>
                                <th>Title</th>
                                <th style="width:1px">Received</th>
                            </tr>
                        </thead>
                        <tbody class="standout">
                            <?php 
                                require_once('database/dbPersons.php');
                                require_once('include/output.php');
                            
                                foreach ($grants as $grant) {
                                    $grantID = $grant['id'];
                                    $title = $grant['name'];
                                    $timePacked = $grant['date'];
                                    $pieces = explode('-', $timePacked);
                                    $year = $pieces[0];
                                    $month = $pieces[1];
                                    $day = $pieces[2];
                                    

                                    //possible spot to check if archived 
                                    $class = 'message';
                                    /*
                                    if (!$message['wasRead']) {
                                        $class .= ' unread';
                                    }
                                    */
                                    echo "
                                        <tr class='$class' data-message-id='$grantID'>
                                            <td>$grantID</td>
                                            <td>$title</td>
                                            <td>$month/$day/$year</td>
                                        </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-messages standout">You currently have no grants.</p>
            <?php endif ?>
            <!-- <button>Compose New Message</button> -->
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </main>
    </body>
</html>