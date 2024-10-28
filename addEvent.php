<?php
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
    // Require admin privileges
    if ($accessLevel < 2) {
        header('Location: login.php');
        echo 'bad access level';
        die();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        require_once('database/dbEvents.php');
        $args = sanitize($_POST, null);
        $required = array(
            "name", "open_date", "due_date", "description", "completed",
        );
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $opendate = $args['open_date'] = validateDate($args["open_date"]);
            $duedate = $args['due_date'] = validateDate($args["due_date"]);
            //$capacity = intval($args["capacity"]);
            if (!$opendate || !$duedate > 11){
                echo 'bad args';
                die();
            }
            $id = create_event($args);
            if(!$id){
                echo "Oopsie!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            $startTime = time24hto12h($startTime);
            $date = date('l, F j, Y', strtotime($date));
            require_once('database/dbMessages.php');
            system_message_all_users_except($userID, "A new event was created!", "Exciting news!\r\n\r\nThe [$name](event: $id) event at $startTime on $date was added!\r\nSign up today!");
            header("Location: event.php?id=$id&createSuccess");
            die();
        }
    }
    $date = null;
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $datePattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
        $timeStamp = strtotime($date);
        if (!preg_match($datePattern, $date) || !$timeStamp) {
            header('Location: calendar.php');
            die();
        }
    }

    // get animal data from database for form
    // Connect to database
    include_once('database/dbinfo.php'); 
    $con=connect();  
    // Get all the animals from animal table
    $sql = "SELECT * FROM `dbAnimals`";
    //$all_animals = mysqli_query($con,$sql);
    $sql = "SELECT * FROM `dbLocations`";
    //$all_locations = mysqli_query($con,$sql);
    $sql = "SELECT * FROM `dbServices`";
    //$all_services = mysqli_query($con,$sql);

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Downtown Greens | Add Grant</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Grant</h1>
        <main class="date">
            <h2>Add Grant Form</h2>
            <form id="new-event-form" method="post">
                <label for="name">* Grant Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter name"> 
                <label for="name">* Open Date </label>
                <input type="date" id="open_date" name="open_date" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>
                <label for="name">* Due Date </label>
                <input type="date" id="due_date" name="due_date" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>
                <label for="name">* Description </label>
                <input type="text" id="description" name="description" required placeholder="Enter description">
                <label for="name">* Status </label>
                <select id="completed" name="completed">
                    <option value="incomplete">Incomplete</option>
                    <option value="complete">Complete!</option>
                    <option value="funded">Funding Awarded</option>
                    <option value="not_funded">Funding Failed</option>
                </select>
                <label for="name"> Grant Type </label>
                <input type="text" id="type" name="type" placeholder="Enter grant type">
                <label for="name"> Partners </label>
                <input type="text" id="partners" name="partners" placeholder="Enter partners">
                <label for="name"> Grant Amount </label>
                <input type="text" id="amount" name="amount" placeholder="Enter amount">
                <p></p>
                <input type="submit" value="Add Grant">
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>
        </main>
    </body>
</html>