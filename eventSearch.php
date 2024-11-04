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
    if (!$loggedIn) {
        header('Location: login.php');
        die();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        require_once('database/dbEvents.php');
        $args = sanitize($_POST);
        if (isset($args['submitName'])) {
            if (!wereRequiredFieldsSubmitted($args, array('name'))) {
                echo 'missing form data';
                die();
            }
            $events = find_event($args['name']);
            $search = 'Results for Search by Name: "' . htmlspecialchars($_POST['name']) . '"';
        } else if (isset($args['submitOpenDate'])) {
            if (!wereRequiredFieldsSubmitted($args, array('open-date'))) {
                echo 'missing form data';
                die();
            }
            $opendate = validateDate($args['open-date']);
            $events = fetch_event_open($args['open-date']);
            $search = 'Results for Search by Open Date: ' . htmlspecialchars($_POST['open-date']);
        } else if (isset($args['submitDueDate'])) {
            if (!wereRequiredFieldsSubmitted($args, array('due-date'))) {
                echo 'missing form data';
                die();
            }
            $duedate = validateDate($args['due-date']);
            $events = fetch_event_due($args['due-date']);
            $search = 'Results for Search by Due Date: ' . htmlspecialchars($_POST['due-date']);
        }
    } else {
        $events = null;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Downtown Greens | Find Grant</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Find Grant</h1>
        <main class="search-form">
            <?php
                if (isset($events)) {
                    echo '<h2>' . $search . '</h2>';
                    require_once('include/output.php');
                    if (count($events) > 0) {
                        foreach ($events as $event) {
                            $date = $event['open_date'];
                            $date = strtotime($date);
                            $date = date('l, F j, Y', $date);
                            $duedate = $event['due_date'];
                            $duedate = strtotime($duedate);
                            $duedate = date('l, F j, Y', $duedate);
                            echo "
                                <table class='event'>
                                    <thead>
                                        <tr>
                                            <th colspan='2' data-event-id='" . $event['id'] . "'>" . $event['name'] . "</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Open Date</td><td>" . $date . "</td></tr>
                                        <tr><td>Due Date</td><td>" . $duedate . "</td></tr>
                                        <tr><td>Description</td><td>" . $event['description'] . "</td></tr>
                                    </tbody>
                                </table>
                            ";
                        }
                    } else {
                        echo '<div class="error-toast">Your search returned no results.</div>';
                    }
                }
            ?>
            <h2>Search for a Grant</h2>
            <form method="post">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter event name" required>
                <input type="submit" name="submitName" id="submitName" value="Search by Name">
            </form>
            <form method="post">
                <label for="open-date">Open Date</label>
                <input type="date" name="open-date" id="open-date" required>
                <input type="submit" name="submitOpenDate" id="submitOpenDate" value="Search by Open Date">
            </form>
            <form method="post">
                <label for="due-date">Due Date</label>
                <input type="date" name="due-date" id="due-date" required>
                <input type="submit" name="submitDueDate" id="submitDueDate" value="Search by Due Date">
            </form>
            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
        </main>
    </body>
</html>