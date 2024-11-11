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
            $id2 = add_link($args);
            if(!$id && !$id2){
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

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Downtown Greens | Add Grant</title>
        <style> input::placeholder{
        	color: white;
        }
        </style>
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
                <input type="date" id="open_date" name="open_date" style="color:white;" <?php if ($date) echo 'value="' . $date . '"'; ?> required>
                <label for="name">* Due Date </label>
                <input type="date" id="due_date" name="due_date" style="color:white;"<?php if ($date) echo 'value="' . $date . '"'; ?> required>
                <label for="name">* Description </label>
                <input type="text" id="description" name="description" required placeholder="Enter description">
                <label for="name" >* Status </label>
                <select id="completed" name="completed" style="color:white;">
                    <option value="incomplete" >Incomplete</option>
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
                <div id="dynField-container"  style="margin-top:.5rem;"></div>
                <script src= "js/dynField.js"></script>
                
                <fieldset>
                    <div id="children-container"></div>
                    <button type="button" onclick="addChildForm()">Add Link</button>
                </fieldset>

                <script>
                    let childCount = 0;
                    const children = [];

                    function addChildForm() {
                        childCount++;
                        const container = document.getElementById('children-container');
                        
                        const childDiv = document.createElement('div');
                        childDiv.className = 'child-form';
                        childDiv.id = `child-form-${childCount}`;
                        
                        childDiv.innerHTML = `
                            <label>Link ${children.length + 1}</label>

                            <label for="link_name">Name</label>
                            <input type="text" id="link_name" name="link_name" required placeholder="Enter link name">

                            <label for="link_data">Link</label>
                            <input type="text" id="link_data" name="link_data" required placeholder="Enter link data">

                            <button type="button" onclick="removeChildForm(${childCount})">Remove Link</button>

                            <hr>
                        `;
                        
                        container.appendChild(childDiv);
                        children.push(childDiv);
                        renumberChildren();
                    }

                    function removeChildForm(childId) {
                        // Find the child div to remove
                        const childDiv = document.getElementById(`child-form-${childId}`);
                        if (childDiv) {
                            childDiv.remove();  // Remove the specific child form

                            // Remove the corresponding child element from the array
                            const index = children.findIndex(child => child.id === `child-form-${childId}`);
                            if (index > -1) {
                                children.splice(index, 1);
                            }
                            
                            // Renumber the children after removal
                            renumberChildren();
                        }
                    }

                    function renumberChildren() {
                        // Iterate over each child form and update the displayed child number
                        children.forEach((child, index) => {
                            const childHeader = child.querySelector('h4');
                            childHeader.textContent = `Child ${index + 1}`;
                        });
                    }
                </script>
                
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