<?php 
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
  require_once('include/input-validation.php');
  require_once('database/dbPersons.php');

  if ($accessLevel < 2) {
    header('Location: index.php');
    die();
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
/*
    // get animal data from database for form
    // Connect to database
    include_once('database/dbinfo.php');
    $con=connect();
    // Get all the animals from animal table
    $sql = "SELECT * FROM `dbAnimals`";
    $all_animals = mysqli_query($con,$sql);
    */ //COMMENT OUT TIL WE DO BACK END//
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Downtown Greens | Reports</title>
        <style>
            .report_select{
                display: flex;
                flex-direction: column;
                gap: .5rem;
                padding: 0 0 4rem 0;
            }
            @media only screen and (min-width: 1024px) {
                .report_select {
                    /* width: 40%; */
                    width: 35rem;
            }
            main.report {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
	    .column {
		padding: 0 4rem 0 0;
		width: 50%;
	    }
	    .row{
          	display: flex;
            }
	    }
	    .hide {
  		display: none;
	    }

	    .myDIV:hover + .hide {
		display: block;
  		color: red;
	    }
        </style>
    </head>
    <body>
        <?php require_once('header.php');?>
	<h1>Business and Operational Reports</h1>

    <main class="report">
	<?php
	    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_click"])) {
            require_once('include/input-validation.php');
            require_once('database/dbEvents.php');
            $args = sanitize($_POST, null);
            $required = array(
                "start_date", "stop_date",
            );
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                echo 'bad form data';
                die();
            } else {
                $startdate = $args['start_date'] = validateDate($args["start_date"]);
                $stopdate = $args['stop_date'] = validateDate($args["stop_date"]);
                if (!$startdate || !$stopdate > 11){
                    echo 'bad args';
                    die();
                }
            }
            }
	    ?>

	<h2>Generate Report</h2>
	<br>

    <form class="report_select" method="get" action="reportsPage.php">
        <label for="name">* Start Date </label>
        <input type="date" id="start_date" name="start_date" style="color:white;" <?php if ($date) echo 'value="' . $date . '"'; ?> required>
        <label for="name">* Stop Date </label>
        <input type="date" id="stop_date" name="stop_date" style="color:white;"<?php if ($date) echo 'value="' . $date . '"'; ?> required>
        <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>
    <input type="submit" name="submit_click">
    </form>
    </main>

    </body>

</html>
