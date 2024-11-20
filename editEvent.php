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
    require_once('include/input-validation.php');
    require_once('database/dbEvents.php');
    $errors = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $args = sanitize($_POST, null);
        $required = array(
            "id", "funder", "name", "completed", "open_date", "due_date", "description");
        
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            var_dump($args);
            echo 'bad form data';
            die();
        } else {
            require_once('database/dbPersons.php');
            $id = $args['id'];
            /*$validated = validate12hTimeRangeAndConvertTo24h($args["start-time"], "11:59 PM");
            if (!$validated) {
                $errors .= '<p>The provided time range was invalid.</p>';
            }
            $startTime = $args['start-time'] = $validated[0];
            $endTime = $validated[1];
            $date = $args['date'] = validateDate($args["date"]);*/
            $name = $args['name'];
            $funder = $args['funder'];
            $completed = $args['completed'];
            $open_date = $args['open_date'];
            $due_date = $args['due_date'];
            $description = $args['description'];
           // $capacity = intval($args["capacity"]);
           // $assignedVolunteerCount = count(getvolunteers_byevent($id));
           // $difference = $assignedVolunteerCount - $capacity;
           // if ($capacity < $assignedVolunteerCount) {
            //    $errors .= "<p>There are currently $assignedVolunteerCount volunteers assigned to this event. The new capacity must not exceed this number. You must remove $difference volunteer(s) from the event to reduce the capacity to $capacity.</p>";
           // }
            //$abbrevLength = strlen($args['abbrev-name']);
            if (!$name || !$completed || !$open_date || !$due_date || !$description){
                $errors .= '<p>Your request was missing arguments.</p>';
            }
            if (!$errors) {
                $success = update_event($id, $args);
                if (!$success){
                    echo "Oopsy!";
                    die();
                }
                header('Location: event.php?id=' . $id . '&editSuccess');
            }
        }
    }
    if (!isset($_GET['id'])) {
        // uhoh
        die();
    }
    $args = sanitize($_GET);
    $id = $args['id'];
    $event = fetch_event_by_id($id);
    if (!$event) {
        echo "Event does not exist";
        die();
    }
    require_once('include/output.php');

    // get animal data from database for form
    // Connect to database
    include_once('database/dbinfo.php'); 
    $con=connect();  
    $sql = "SELECT * FROM `dbEvents`";
    $all_locations = mysqli_query($con,$sql);
    $sql = "SELECT * FROM `dbPersons`";
    $all_services = mysqli_query($con,$sql);

    // get current selected services for event
    //$current_services = get_services($id);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Downtown Greens | Edit Appointment</title>
        <style> input::placeholder{
        	color: white;
        }
        </style>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Modify Grant</h1>
        <main class="date">
        <?php if ($errors): ?>
            <div class="error-toast"><?php echo $errors ?></div>
        <?php endif ?>
            <h2>Grant Details</h2>
            <form id="new-event-form" method="post">
                <label for="name">Grant Name </label>
                <input type="hidden" name="id" value="<?php echo $id ?>"/> 
                <input type="text" style="color:white;" id="name" name="name" value="<?php echo $event['name'] ?>" required placeholder="Enter name">
                <label for="funder">Funder</label>
                <input type="hidden" name="funder" value="<?php echo $event['funder'] ?>"/>
                <input type="text" style="color:white;" id="funder" name="funder" value="<?php echo $event['funder'] ?>" required placeholder="Enter funder">
                <?php //Get $completed variable (the current status) to set the "selected" option in the drop down select form
                    if (!isset($_GET['id'])) {
                        // uhoh
                        die();
                    }
                    $args = sanitize($_GET);
                    $id = $args['id'];
                    $event = fetch_event_by_id($id);
                    if (!$event) {
                        echo "Event does not exist";
                        die();
                    }
                    $completed = $event['completed'];

                    if($event['type'] == null){
                        $event_type = "";
                    }else{
                        $event_type = $event['type'];
                    }
        
                    if($event['partners'] == null){
                        $event_partners = "";
                    }else{
                        $event_partners = $event['partners'];
                    }
        
                    if($event['amount'] == null){
                        $event_amount = "";
                    }else{
                        $event_amount = $event['amount'];
                    }



                ?>
                <label for="name" >Status </label>
                <select style="color:white;" id="completed" name="completed">
                    <option value="not_started" >Proposal Not Started</option>
                    <option value="incomplete">Proposal Incomplete</option>
                    <option value="submitted">Proposal Submitted!</option>
                    <option value="declined">Proposal Declined</option>
                    <option value="accepted">Proposal Accepted!</option>
                    <option value="awarded">Gift Awarded!</option>
                </select>
                <label for="name">Open Date </label>
                <input type="date" style="color:white;" id="open_date" name="open_date" value="<?php echo $event['open_date'] ?>" required>
                <label for="name">Due Date </label>
                <input type="date" style="color:white;" id="due_date" name="due_date" value="<?php echo $event['due_date'] ?>" required>
                <label for="name">Description </label>
                <input type="text" style="color:white;" id="description" name="description" value="<?php echo $event['description'] ?>" required placeholder="Enter description">
                <label for="name"> Grant Type </label>
                <input type="text" id="type" name="type" value="<?php echo $event_type ?>" placeholder="Enter grant type">
                <label for="name"> Partners </label>
                <input type="text" id="partners" name="partners" value="<?php echo $event_partners ?>" placeholder="Enter partners">
                <label for="name"> Grant Amount </label>
                <input type="text" id="amount" name="amount" value="<?php echo $event_amount ?>" placeholder="Enter amount">
                </select><p></p>
                <input type="submit" value="Update Grant">
                <a class="button cancel" href="event.php?id=<?php echo htmlspecialchars($_GET['id']) ?>" style="margin-top: .5rem">Cancel</a>
            </form>

            <script type="text/javascript">
                    $(document).ready(function(){
                        var checkboxes = $('.checkboxes');
                        checkboxes.change(function(){
                            if($('.checkboxes:checked').length>0) {
                                checkboxes.removeAttr('required');
                            } else {
                                checkboxes.attr('required', 'required');
                            }
                        });
                    });
            </script>
        </main>
    </body>
</html>