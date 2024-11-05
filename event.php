<?php 

    session_cache_expire(30);
    session_start();

    // Ensure user is logged in
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        header('Location: login.php');
        die();
    }
    require_once('include/input-validation.php');
    $args = sanitize($_GET);
    if (isset($args["id"])) {
        $id = $args["id"];
    } else {
        header('Location: calendar.php');
        die();
  	}
  	
  	include_once('database/dbEvents.php');
  	
    // We need to check for a bad ID here before we query the db
    // otherwise we may be vulnerable to SQL injection(!)
  	$event_info = fetch_event_by_id($id);
    if ($event_info == NULL) {
        // TODO: Need to create error page for no event found
        // header('Location: calendar.php');

        // Lauren: changing this to a more specific error message for testing
        echo 'bad event ID';
        die();
    }

    $event_archived = $event_info['archived'];

    include_once('database/dbPersons.php');
    $access_level = $_SESSION['access_level'];
    $user = retrieve_person($_SESSION['_id']);
    $active = $user->get_status() == 'Active';

    ini_set("display_errors",1);
    error_reporting(E_ALL);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $args = sanitize($_POST);
        $get = sanitize($_GET);
        if (isset($_POST['attach-post-media-submit'])) {
            if ($access_level < 2) {
                echo 'forbidden';
                die();
            }
            $required = [
                'url', 'description', 'format', 'id'
            ];
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                echo "dude, args missing";
                die();
            }
            $type = 'post';
            $format = $args['format'];
            $url = $args['url'];
            if ($format == 'video') {
                $url = convertYouTubeURLToEmbedLink($url);
                if (!$url) {
                    echo "bad video link";
                    die();
                }
            } else if (!validateURL($url)) {
                echo "bad url";
                die();
            }
            $eid = $args['id'];
            $description = $args['description'];
            if (!valueConstrainedTo($format, ['link', 'video', 'picture'])) {
                echo "dude, bad format";
                die();
            }
            attach_post_event_media($eid, $url, $format, $description);
            header('Location: event.php?id=' . $id . '&attachSuccess');
            die();
        }
        if (isset($_POST['attach-training-media-submit'])) {
            if ($access_level < 2) {
                echo 'forbidden';
                die();
            }
            $required = [
                'url', 'description', 'format', 'id'
            ];
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                echo "dude, args missing";
                die();
            }
            $type = 'post';
            $format = $args['format'];
            $url = $args['url'];
            if ($format == 'video') {
                $url = convertYouTubeURLToEmbedLink($url);
                if (!$url) {
                    echo "bad video link";
                    die();
                }
            } else if (!validateURL($url)) {
                echo "bad url";
                die();
            }
            $eid = $args['id'];
            $description = $args['description'];
            if (!valueConstrainedTo($format, ['link', 'video', 'picture'])) {
                echo "dude, bad format";
                die();
            }
            attach_event_training_media($eid, $url, $format, $description);
            header('Location: event.php?id=' . $id . '&attachSuccess');
            die();
        }
    } else {
        if (isset($args["request_type"])) {
            //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_type = $args['request_type'];
            if (!valueConstrainedTo($request_type, 
                    array('add self', 'add another', 'remove'))) {
                echo "Bad request";
                die();
            }
            $eventID = $args["id"];
    
            // Check if Get request from user is from an organization member
            // (volunteer, admin/super admin)
            if ($request_type == 'add self' && $access_level >= 1) {
                if (!$active) {
                    echo 'forbidden';
                    die();
                }
                $volunteerID = $args['selected_id'];
                $person = retrieve_person($volunteerID);
                $name = $person->get_first_name() . ' ' . $person->get_last_name();
                $name = htmlspecialchars_decode($name);
                require_once('database/dbMessages.php');
                require_once('include/output.php');
                $event = fetch_event_by_id($eventID);
                
                $eventName = htmlspecialchars_decode($event['name']);
                $eventDate = date('l, F j, Y', strtotime($event['date']));
                $eventStart = time24hto12h($event['startTime']);
                $eventEnd = time24hto12h($event['endTime']);
                system_message_all_admins("$name signed up for an event!", "Exciting news!\r\n\r\n$name signed up for the [$eventName](event: $eventID) event from $eventStart to $eventEnd on $eventDate.");
                // Check if GET request from user is from an admin/super admin
            // (Only admins and super admins can add another user)
            } else if ($request_type == 'add another' && $access_level > 1) {
                $volunteerID = strtolower($args['selected_id']);
                if ($volunteerID == 'vmsroot') {
                    echo 'invalid user id';
                    die();
                }
                require_once('database/dbMessages.php');
                require_once('include/output.php');
                $event = fetch_event_by_id($eventID);
                $eventName = htmlspecialchars_decode($event['name']);
                $eventDate = date('l, F j, Y', strtotime($event['date']));
                $eventStart = time24hto12h($event['startTime']);
                $eventEnd = time24hto12h($event['endTime']);
                send_system_message($volunteerID, 'You were assigned to an event!', "Hello,\r\n\r\nYou were assigned to the [$eventName](event: $eventID) event from $eventStart to $eventEnd on $eventDate.");
            } else {
                header('Location: event.php?id='.$eventID);
                die();
            }
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <?php
        require_once('universal.inc');
    ?>
    <title>Downtown Greens | View Grant: <?php echo $event_info['name'] ?></title>
    <link rel="stylesheet" href="css/event.css" type="text/css" />
    <?php if ($access_level >= 2) : ?>
        <script src="js/event.js"></script>
    <?php endif ?>
</head>

<body>
    <?php if ($access_level >= 2) : ?>
        <div id="delete-confirmation-wrapper" class="hidden">
            <div id="delete-confirmation">
                <p>Are you sure you want to delete this appointment?</p>
                <p>This action cannot be undone.</p>

                <form method="post" action="deleteEvent.php">
                    <input type="submit" value="Delete Appointment">
                    <input type="hidden" name="id" value="<?= $id ?>">
                </form>
                <button id="delete-cancel">Cancel</button>
            </div>
        </div>
    <?php endif ?>
    <?php if ($access_level >= 2) : ?>
        <div id="complete-confirmation-wrapper" class="hidden">
            <div id="complete-confirmation">
                <p>Are you sure you want to complete this appointment?</p>
                <p>This action cannot be undone.</p>
                <button id="complete-cancel">Cancel</button>
            </div>
        </div>
    <?php endif ?>
    <?php if ($access_level >= 2) :?>
        <div id="archive-confirmation-wrapper" class="hidden">
            <div id="archive-confirmation">
                <p>Are you sure you want to archive this grant?</p>
                <form method="post" action="archiveGrant.php">
                    <input type="submit" value="Archive Grant">
                    <input type="hidden" name="id" value="<?= $id ?>">
                </form>
                <button id="archive-cancel">Cancel</button>
            </div>
        </div>
    <?php endif ?>
    <?php if ($access_level >= 2) :?>
        <div id="unarchive-confirmation-wrapper" class="hidden">
            <div id="unarchive-confirmation">
                <p>Are you sure you want to unarchive this grant?</p>
                <form method="post" action="unarchiveGrant.php">
                    <input type="submit" value="Unarchive Grant">
                    <input type="hidden" name="id" value="<?= $id ?>">
                </form>
                <button id="unarchive-cancel">Cancel</button>
            </div>
        </div>
    <?php endif ?>
    <?php require_once('header.php') ?>
    <h1>View Grant</h1>
    <main class="event-info">
        <?php if (isset($_GET['createSuccess'])): ?>
            <div class="happy-toast">Grant created successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['attachSuccess'])): ?>
            <div class="happy-toast">Media attached successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['removeSuccess'])): ?>
            <div class="happy-toast">Media removed successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['editSuccess'])): ?>
            <div class="happy-toast">Grant details updated successfully!</div>
        <?php endif ?>
        <?php    
            require_once('include/output.php');
            $event_name = $event_info['name'];
            $event_open_date = date('l, F j, Y', strtotime($event_info['open_date']));
            $event_due_date= date('l, F j, Y', strtotime($event_info['due_date']));
            $event_description = $event_info['description'];
            $event_completed = $event_info['completed'];
            if($event_info['type'] == null){
                $event_type = "N/A";
            }else{
                $event_type = $event_info['type'];
            }

            if($event_info['partners'] == null){
                $event_partners = "N/A";
            }else{
                $event_partners = $event_info['partners'];
            }

            if($event_info['amount'] == null){
                $event_amount = "N/A";
            }else{
                $event_amount = $event_info['amount'];
            }
            

            $event_in_past = strcmp(date('Y-m-d'), $event_info['due_date']) > 0;
            echo '<h2 class="centered">'.$event_name.'</h2>';
        ?>
        <div id="table-wrapper">
            <table class="general">
                <tbody>
        <!-- band aid fix for white font need to do inline css to override-->
                    <tr style="color:white;">	
                        <td class="label"> Grant </td>
                        <td><?php echo $event_name ?></td>     		
                    </tr>
                    <tr style="color:white;">	
                        <td class="label"> Status </td>
                        <td><?php echo $event_completed ?></td>     		
                    </tr>
                    <tr style="color:white;">	
                        <td class="label"> Open Date </td>
                        <td><?php echo $event_open_date ?></td>     		
                    </tr>
                    <tr style="color:white;">	
                        <td class="label"> Due Date </td>
                        <td><?php echo $event_due_date ?></td>     		
                    </tr>
                    <tr style="color:white;">	
                        <td class="label"> Description </td>
                        <td><?php echo $event_description ?></td>     		
                    </tr>
                    <tr style="color:white;">	
                        <td class="label"> Grant Type </td>
                        <td><?php echo $event_type ?></td>     		
                    </tr>
                    <tr style="color:white;">	
                        <td class="label"> Partners </td>
                        <td><?php echo $event_partners ?></td>     		
                    </tr>
                    <tr style="color:white;">	
                        <td class="label"> Grant Amount </td>
                        <td><?php echo $event_amount ?></td>     		
                    </tr>
                    <!-- <tr>	
                        <td class="label">Service(s) </td>
                        <td>
                            <?php /*
                                $services = get_services($id);
                                $length = count($services);
                                for ($i = 0; $i < $length; $i++) { 
                                    echo $services[$i]['name'];
                                    if ($i < $length - 1) {
                                        echo ', ';
                                    }
                                }
                            ?>
                        </td>     		
                    </tr> -->
                    <!--<tr>	
                        <td class="label">Location </td>
                        <td>
                            <?php 
                                $locations = get_location($event_location);
                                foreach($locations as $location) {
                                    echo $location['name'];
                                }
                            ?>
                        </td>     		
                    </tr>
                    <tr>	
                        <td class="label">Location Address </td>
                        <td>
                            <?php 
                                foreach($locations as $location) {
                                    echo $location['address'];
                                }
                            ?>
                        </td>     		
                    </tr>
                    <tr>	
                        <td class="label">Description </td><td></td>
                    </tr>
                    <tr>
                        <td id="description-cell" colspan="2"><?php echo $event_description */?></td>     		
                    </tr> -->
                    
                        
        <!-- TODO: will figure out another way to center
                 later -->
        <?php
		if ($access_level >= 2) {
                	echo '
                        <tr>
                        	<td colspan="2">
                                	<a href="editEvent.php?id=' . $id . '" class="button">Edit Grant Details</a>
                                </td>
                        </tr>
                        ';

                        if ($event_archived != 'yes') {
                            echo '
                            <tr>
                                <td colspan="2">
                                        <button onclick="showArchiveConfirmation()">Archive Grant</button>
                                    </td>
                            </tr>
                            ';
                        } else {
                            echo '
                            <tr>
                                <td colspan="2">
                                        <button onclick="showUnarchiveConfirmation()">Unarchive Grant</button>
                                    </td>
                            </tr>
                            ';
                        }

                        echo '
                        <tr>
                        	<td colspan="2">
                                	<button onclick="showDeleteConfirmation()">Delete Grant</button>
                                </td>
                        </tr>
                        ';

                        echo '
                        <tr>
                        	<td colspan="2">
                                	<a class="button cancel" href="viewGrant.php">Return to Grants</a>
                                </td>
                        </tr>
                        ';
                 }
	?> 

        <?php /* if ($access_level >= 2) : ?>
            <!-- <form method="post" action="deleteEvent.php">
                <input type="submit" value="Delete Event">
                <input type="hidden" name="id" value="<?= $id ?>">
            </form> -->
            <?php if ($event_info["completed"] == "incomplete") : ?>
                <button onclick="showCompleteConfirmation()">Complete Appointment</button>
            <?php endif ?>
            <!--<button onclick="showDeleteConfirmation()">Delete Grant</button>-->
        <?php endif */?>
        
        <!--<a href="calendar.php?month=<?/*php echo substr($event_info['date'], 0, 7) */?>" class="button cancel" style="margin-top: -.5rem">Return to Calendar</a>-->
    </main>
</body>

</html>
