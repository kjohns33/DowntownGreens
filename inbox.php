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
        <link rel="stylesheet" href="css/event.css" type="text/css" />
        <link rel="stylesheet" href="css/messages.css"></link>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="css/inbox.css">
        <script>
            const userID = <?php echo json_encode($userID); ?>;
        </script>
        <script src="js/messages.js"></script>
        <?php if ($accessLevel >= 2) : ?>
            <script src="js/event.js"></script>
        <?php endif ?>
        <title>Downtown Greens | Inbox</title>
    </head>
    <body>
        <?php if ($accessLevel >= 2) : ?>
            <div id="create-notif-confirmation-wrapper" class="hidden">
                <div id="create-notif-confirmation">
                    <p>Please Enter Your Notification Information:</p>
                    <form method="post" action="createNotification.php">
                        <label for="title">* Notification Title </label>
                        <input type="text" style="color: white" id="title" name="title" placeholder="Enter notification title" required>
                        <label for="body">Notification Body </label>
                        <input type="text" style="color: white" id="body" name="body" placeholder="Enter notification body">
                        <label for="send_date">* Send Date </label>
                        <input type="date" id="send_date" name="send_date" style="color:white;" min="<?php echo date('Y-m-d'); ?>" required>
                        <label for="send_to">* Send To </label>

                        <!-- Multiselect Dropdown -->
                        <select id="send_to" name="send_to[]" class="selectpicker custom-style" data-selected-text-format="count > 3" multiple data-live-search="true" data-width="100%" data-actions-box="true" data-live-search-placeholder="Search..." data-select-all-text="Select All" data-deselect-all-text="Deselect All" required>
                        <?php
                                require_once('database/dbPersons.php');
                                $persons = getall_volunteers();
                                if (count($persons) > 0) {
                                    foreach ($persons as $person) {
                                        $name = $person->get_first_name() . ' ' . $person->get_last_name();
                                        echo "<option>" . $name . "</option>";
                                    }
                                }
                        ?>
                        </select>

                        <!-- Initialize the Select Picker -->
                        <script>
                            $(document).ready(function() {
                                $('.selectpicker').selectpicker();
                            });
                        </script>

                        <label for="priority" >Priority </label>
                        <select style="color:white;" id="priority" name="priority">
                            <option value="1" selected>1</option>
                            <option value="2" >2</option>
                            <option value="3" >3</option>
                        </select>
                        <p></p>

                            <input type="submit" style="color: white;" value="Create Notification">
                            <input type="hidden" name="id" value="<?= $userID ?>">
                    </form>
                    <button id="create-notif-cancel">Cancel</button>
                </div>
            </div>
        <?php endif ?>
        <?php if ($accessLevel >= 2) : ?>
            <div id="delete-confirmation-wrapper" class="hidden">
                <div id="delete-confirmation">
                    <p>Are you sure you want to mark all notifications as read?</p>
                    <p>This action cannot be undone.</p>

                    <form method="post" id="delete-confirmation-form">
                        <input type="submit" value="Mark All As Read">
                        <input type="hidden" name="id" value="<?= $userID ?>">
                    </form>
                    <button id="delete-cancel">Cancel</button>
                </div>
            </div>
        <?php endif ?>
        <?php if ($accessLevel >= 2) : ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Handle form submission for "Mark All As Read"
                    const confirmationForm = document.getElementById('delete-confirmation-form');
                    confirmationForm.onsubmit = function (event) {
                        event.preventDefault(); // Prevent form submission
                        markAllAsRead();
                    };
                });

                document.addEventListener('DOMContentLoaded', function () {
                    const sortDropdown = document.querySelector('.sortby-style select');

                    sortDropdown.addEventListener('change', function () {
                        const sortOrder = this.value; // Get the selected sort order
                        fetchMessages(sortOrder); // Call the function to fetch sorted messages
                    });
                });
            </script>
        <?php endif; ?>
        <?php require_once('header.php') ?>
        <h1>Inbox</h1>
        <main class="general">
            <?php if (isset($_GET['deleteNotifSuccess'])): ?>
                    <div class="happy-toast">Notification deleted successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['createNotifSuccess'])) : ?>
                <div class="happy-toast">Notification created successfully!</div>
            <?php endif; ?>
            <?php if (isset($_GET['markAllAsReadSuccess'])) : ?>
                <div class="happy-toast">All notifications marked as read.</div>
            <?php endif; ?>
            <h2>Your Notifications</h2>
            <div id="button-inline">
                <button class="buttoncreatenotif" onclick="showCreateNotifConfirmation()">Create Notification</button>
                <button class="buttoncreatenotif" onclick="showDeleteConfirmation()">Mark All As Read</button>
                <div class="sortby-style">
                    <select class="selectpicker" data-style="btn" data-width="100%" onchange="updatePlaceholder(this)">
                        <option value="prio" selected>Priority</option>
                        <option value="open">Open Date</option>
                        <option value="due">Due Date</option>
                        <option value="nonsys">Non-System Messages</option>
                        <option value="time">Time Received</option>
                        <option value="unread">Unread</option>
                    </select>
                </div>
            </div>
            <script>
                // Automatically update the button text with the selected option on page load
                document.addEventListener("DOMContentLoaded", function() {
                    const select = document.querySelector('.sortby-style select');
                    updatePlaceholder(select); // Call the function to update the button with default selected option
                });
                const table = document.querySelector('table');
                    if (table) {
                        table.addEventListener('click', function (e) {
                            const target = e.target;
                            if (target.tagName === 'TH') {
                                const column = target.getAttribute('data-column');
                                const sortOrder = target.classList.contains('asc') ? 'desc' : 'asc';
                                fetchMessages(column, sortOrder);
                            }
                        });
                    }
            </script>
            <?php 
                require_once('database/dbMessages.php');
                require_once('database/dbPersons.php');
                dateChecker();
                $messages = get_user_messages($userID);
                if (count($messages) > 0): ?>
                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th style="width:1px; font-size:1rem">From</th>
                                <th style="font-size:1rem">Title</th>
                                <th style="width:1px; font-size:1rem">Received</th>
                            </tr>
                        </thead>
                        <tbody class="standout">
                            <?php 
                                require_once('database/dbPersons.php');
                                require_once('include/output.php');
                                $id_to_name_hash = [];
                                foreach ($messages as $message) {
                                    $sender = $message['senderID'];
                                    if (isset($id_to_name_hash[$sender])) {
                                        $sender = $id_to_name_hash[$sender];
                                    } else {
                                        $lookup = get_name_from_id($sender);
                                        $id_to_name_hash[$sender] = $lookup;
                                        $sender = $lookup;
                                    }
                                    $messageID = $message['id'];
                                    if ($message['grant_id'] != NULL && !is_corresponding_grant_archived($messageID)) {
                                        continue;
                                    }
                                    $title = $message['title'];
                                    $time = $message['time'];
                                    $wasRead = $message['wasRead'];
                                    $scheduledDate = $message['scheduled_date'];
                                    if ($scheduledDate) {
                                        if ($scheduledDate > date("Y-m-d")) { continue; }
                                    }
                                    /*$timePacked = $message['time'];
                                    $pieces = explode('-', $timePacked);
                                    $year = $pieces[0];
                                    $month = $pieces[1];
                                    $day = $pieces[2];
                                    $time = time24hto12h($pieces[3]);*/
                                    $class = 'message';
                                    if (!$message['wasRead']) {
                                        $class .= ' unread';
                                    }
                                    if ($message['prioritylevel'] == 1) {
                                        $class .= ' prio1';
                                    }
                                    if ($message['prioritylevel'] == 2) {
                                        $class .= ' prio2';
                                    }
                                    if ($message['prioritylevel'] == 3) {
                                        $class .= ' prio3';
                                    }
                                    echo "
                                        <tr class='$class' style='color:white;' data-message-id='$messageID' 'wasRead=$wasRead'>
                                            <td>$sender</td>";
                                            if (!$wasRead) {
                                                echo "<td>(!) $title";
                                            } else {
                                                echo "<td>$title</td>";
                                            }
                                            echo "<td>$time</td>
                                        </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="no-messages standout" style="color:white;">You currently have no messages.</p>
            <?php endif ?>
            <!-- <button>Compose New Message</button> -->
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </main>
    </body>
</html>