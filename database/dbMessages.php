<?php

require_once('database/dbinfo.php');
date_default_timezone_set("America/New_York");

function get_user_messages($userID) {
    $query = "select * from dbMessages
    where recipientID='$userID'
    order by prioritylevel desc, time, grant_id";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($messages as &$message) {
        foreach ($message as $key => $value) {
            if ($value != NULL) {
                $message[$key] = htmlspecialchars($value);
            }
        }
    }
    unset($message);
    mysqli_close($connection);
    return $messages;
}

function get_user_messages_ordered_by($userID, $order) {
    $query = "select * from dbMessages m inner join dbevents e on m.grant_id = e.id
    where recipientID='$userID'
    order by $order desc, time, grant_id";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        error_log("Database query failed: " . mysqli_error($connection));
        mysqli_close($connection);
        return null;
    }
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($messages as &$message) {
        foreach ($message as $key => $value) {
            if ($value != NULL) {
                $message[$key] = htmlspecialchars($value);
            }
        }
    }
    unset($message);
    mysqli_close($connection);
    return $messages;
}

function get_user_messages_ordered_by_unread($userID) {
    $query = "select * from dbMessages
    where recipientID='$userID'
    order by wasRead asc, prioritylevel desc, time, grant_id";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        error_log("Database query failed: " . mysqli_error($connection));
        mysqli_close($connection);
        return null;
    }
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($messages as &$message) {
        foreach ($message as $key => $value) {
            if ($value != NULL) {
                $message[$key] = htmlspecialchars($value);
            }
        }
    }
    unset($message);
    mysqli_close($connection);
    return $messages;
}

function get_user_messages_ordered_by_time($userID) {
    $query = "select * from dbMessages
    where recipientID='$userID'
    order by time desc, prioritylevel, grant_id";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        error_log("Database query failed: " . mysqli_error($connection));
        mysqli_close($connection);
        return null;
    }
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($messages as &$message) {
        foreach ($message as $key => $value) {
            if ($value != NULL) {
                $message[$key] = htmlspecialchars($value);
            }
        }
    }
    unset($message);
    mysqli_close($connection);
    return $messages;
}

function get_user_messages_ordered_by_open_due_dates($userID, $date) {
    $alternateDate = ($date === 'open') ? 'due' : 'open';
    $query = "
        SELECT * 
        FROM dbMessages
        WHERE recipientID = '$userID'
        ORDER BY 
            FIELD(message_type, '$date', '$alternateDate', 'custom') ASC, 
            prioritylevel DESC, 
            time DESC, 
            grant_id
    ";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        error_log("Database query failed: " . mysqli_error($connection));
        mysqli_close($connection);
        return null;
    }
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($messages as &$message) {
        foreach ($message as $key => $value) {
            if ($value != NULL) {
                $message[$key] = htmlspecialchars($value);
            }
        }
    }
    unset($message);
    mysqli_close($connection);
    return $messages;
}

function get_user_messages_nonsys_first($userID) {
    $query = "
        SELECT * 
        FROM dbMessages
        WHERE recipientID = '$userID'
        ORDER BY 
            FIELD(message_type, 'custom') DESC, 
            prioritylevel DESC, 
            time DESC, 
            grant_id
    ";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        error_log("Database query failed: " . mysqli_error($connection));
        mysqli_close($connection);
        return null;
    }
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($messages as &$message) {
        foreach ($message as $key => $value) {
            if ($value != NULL) {
                $message[$key] = htmlspecialchars($value);
            }
        }
    }
    unset($message);
    mysqli_close($connection);
    return $messages;
}

function get_user_unread_count($userID) {
    $query = "select count(*) from dbMessages m left join dbevents e on m.grant_id = e.id and e.archived<>'yes'
        where recipientID='$userID' and wasRead=0";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }

    $row = mysqli_fetch_row($result);
    mysqli_close($connection);
    return intval($row[0]);
}

function get_message_by_id($id) {
    $query = "select * from dbMessages where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }

    $row = mysqli_fetch_assoc($result);
    mysqli_close($connection);
    if ($row == null) {
        return null;
    }
    foreach ($row as $key => $value) {
        if ($value != NULL) {
            $row[$key] = htmlspecialchars($value);
        }
    }
    $row['body'] = str_replace("\r\n", "<br>", $row['body']);
    return $row;
}

function send_message($from, $to, $title, $body) {
    $time = date('Y-m-d-H:i');
    $connection = connect();
    $title = mysqli_real_escape_string($connection, $title);
    $body = mysqli_real_escape_string($connection, $body);
    $query = "insert into dbMessages
        (senderID, recipientID, title, body, time)
        values ('$from', '$to', '$title', '$body', '$time')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $id = mysqli_insert_id($connection);
    mysqli_close($connection);
    return $id; // get row id
}

function send_system_message($to, $title, $body) {
    send_message('vmsroot', $to, $title, $body);
}

function mark_read($id) {
    $query = "update dbMessages set wasRead=1
              where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return false;
    }
    mysqli_close($connection);
    return true;
}

function message_all_users_of_types($from, $types, $title, $body) {
    $types = implode(', ', $types);
    $time = date('Y-m-d-H:i');
    $query = "select id from dbPersons where type in ($types)";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_NUM);
    foreach ($rows as $row) {
        $to = $row[0];
        $query = "insert into dbMessages (senderID, recipientID, title, body, time)
                  values ('$from', '$to', '$title', '$body', '$time')";
        $result = mysqli_query($connection, $query);
    }
    mysqli_close($connection);    
    return true;
}

function system_message_all_admins($title, $body) {
    return message_all_users_of_types('vmsroot', ['"admin"', '"superadmin"'], $title, $body);
}

function message_all_users_prio($personID, $from, $title, $body, $prio, $grantID, $msgtype, $interval, $sent) {
    $time = date('Y-m-d');
    $query = "select id from dbPersons";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_NUM); //get all the users in the database dbPersons
    foreach ($rows as $row) { //for every user in db person, generate a notification
        $to = json_encode($row); //converting the array of users into strings to put into the database of messages
        $to = substr($to,2,-2); //getting rid of the brackets and quotes in the string: ie - ["user"]
        $query = "insert into dbMessages (person_id, senderID, recipientID, title, body, prioritylevel, grant_id, 
            message_type, interval_type, scheduled_date, sent)
                  values ('$personID', '$from', '$to', '$title', '$body', '$prio', '$grantID', '$msgtype', 
                          '$interval', '$time', '$sent')"; //inserting the notification in that users inbox
        $result = mysqli_query($connection, $query); 
    }
    mysqli_close($connection);    
    return true;
}

function delete_message($id) {
    $query = "delete from dbMessages where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function delete_message_of_grantID($id) {
    $query = "delete from dbMessages where grant_id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function delete_auto_messages_of_grantID_and_type_except_interval($id, $msg_type, $interval) {
    $query = "delete from dbMessages where grant_id='$id' and interval_type not in ('$interval', 'custom') and message_type='$msg_type'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

//For if the grant name has been changed
function update_message_title_and_body($grantID, $msg_type, $interval, $title, $body) {
    $query = "update dbMessages set title='$title', body='$body' 
        where grant_id='$grantID' and message_type='$msg_type' and interval_type='$interval'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function get_recipientID_from_name($name) {
    $pieces = explode(" ", $name);
    $first_name = $pieces[0];
    $last_name = $pieces[1];
    $query = "select id from dbPersons where first_name='$first_name' and last_name='$last_name'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($connection);
    return $row['id'];
}

function create_notification($id, $title, $body, $send_date, $priority, $send_to) {
    //send the selected recipients from create notif dropdown the message
    $connection = connect();
    foreach ($send_to as $recipient) {
        $recipient = get_recipientID_from_name($recipient);
        var_dump($recipient);
        $query = "insert into dbMessages
            (person_id, senderID, recipientID, title, body, scheduled_date, message_type, interval_type, priorityLevel)
            values ('$id', '$id', '$recipient', '$title', '$body', '$send_date', 'custom', 'custom', '$priority')";
        $result = mysqli_query($connection, $query);
        $result = boolval($result);
        if (!$result) { return false; }
    }
    //send vmsroot the message
    $recipient = 'vmsroot';
    $query = "insert into dbMessages
        (person_id, senderID, recipientID, title, body, scheduled_date, message_type, interval_type, priorityLevel)
        values ('$id', '$id', '$recipient', '$title', '$body', '$send_date', 'custom', 'custom', '$priority')";
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    if (!$result) { return false; }
    mysqli_close($connection);
    return true;
}

function is_corresponding_grant_archived($messageID) {
    $query = "select count(*) as count from dbMessages m inner join dbevents e on m.grant_id = e.id
        where m.id = '$messageID' and e.archived<>'yes'
        order by prioritylevel desc";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] == 1) {
        return true;
    } else {
        return false;
    }
}

function get_grant_name_from_messageID($messageID) {
    $query = "select * from dbMessages m inner join dbevents e on m.grant_id = e.id
        where m.id = '$messageID'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) < 1) {
        mysqli_close($connection);
        return false;
    }
    $row = mysqli_fetch_assoc($result);
    return $row['name'];
}

function get_grant_id_from_messageID($messageID) {
    $query = "select e.id as grantID, m.id, m.grant_id from dbMessages m inner join dbevents e on m.grant_id = e.id
        where m.id = '$messageID'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) < 1) {
        mysqli_close($connection);
        return false;
    }
    $row = mysqli_fetch_assoc($result);
    return $row['grantID'];
}

function update_sent_status($id, $sent) {
    $query = "update dbMessages set sent='$sent' where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function markAllAsRead($id) {
    $query = "
        UPDATE dbMessages m
        LEFT JOIN dbEvents e ON m.grant_id = e.id
        SET m.wasRead = 1
        WHERE m.recipientID='$id'
        AND (m.grant_id IS NULL OR e.archived <> 'yes') 
        AND m.wasRead = 0
    ";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function deleteSelected($messages) {
    $connection = connect();
    $allSuccessful = true;
    foreach ($messages as &$message) {
        $query = "
            DELETE from dbMessages where id=$message
        ";
        $result = mysqli_query($connection, $query);
        if (!boolval($result)) { $allSuccessful = false; }
    }
    mysqli_close($connection);
    return $allSuccessful;
}

function deleteAll($id) {
    $connection = connect();
    $query = "
        DELETE from dbMessages where recipientID='$id'
    ";
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function dateChecker(){
    //first, check custom notifications:
    $query = "select * from dbMessages where interval_type='custom' and message_type='custom'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $currentDate = date('Y-m-d');
    while($row = mysqli_fetch_assoc($result)){
        if ($row['scheduled_date'] <= $currentDate && $row['sent'] == 'notSent') {
            update_sent_status($row['id'], 'sent');
        }
    }
    mysqli_close($connection);    

    $query = "select * from dbEvents";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $oneDayAhead = date('Y-m-d', strtotime('+1 day'));
    $oneWeekAhead = date('Y-m-d', strtotime('+1 week'));
    $oneMonthAhead = date('Y-m-d', strtotime('+1 month'));
    $sixMonthsAhead = date('Y-m-d', strtotime('+6 months'));
    $currentDate = date('Y-m-d');
    if($result){
        //For each grant, check its open and due dates for sending automatic system messages (1 week, 1 month, and 6 months)
        while($row = mysqli_fetch_assoc($result)){
            //FROM GRANT DB (dbevents)
            $name = $row['name'];
            $open_date = $row['open_date'];
            $due_date = $row['due_date'];
            $id = $row['id'];

            //The Logic for It Being Late
            //Grant has opened
            if($open_date != "0000-00-00"){
            if($open_date <= $currentDate){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    (interval_type = '1Day' or interval_type = '1Week' or interval_type = '1Month' or interval_type = '6Months')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'open', 'late');
                }

                //If the grant does not have a late message for open date, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    interval_type = 'late' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if ($open_date == $currentDate) {
                    $title = $name . " is opening today";
                } else {
                    $title = $name . " has OPENED";
                }
                $body = $name . " opened on " . $open_date;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body ,'3', $id, 'open', 'late', 'sent');
                } else {
                    update_message_title_and_body($id, 'open', 'late', $title, $body);
                }
                
            }
            }
            //Grant is due
            if($due_date <= $currentDate){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    (interval_type = '1Day' or interval_type = '1Week' or interval_type = '1Month' or interval_type = '6Months')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'due', 'late');
                }

                //If the grant does not have a late message for due date, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    interval_type = 'late' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if ($due_date == $currentDate) {
                    $title = $name . " is due today";
                } else {
                    $title = $name . " was DUE";
                }
                $body = $name . " was due on " . $due_date;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body ,'3', $id, 'due', 'late', 'sent');
                } else {
                    update_message_title_and_body($id, 'due', 'late', $title, $body);
                }
            }
            
            
            //The Logic for One Day Out
            if($open_date > $currentDate && $open_date <= $oneDayAhead){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    (interval_type = '1Week' or interval_type = '1Month' or interval_type = '6Months' or interval_type = 'late') 
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'open', '1Day');
                }

                //If the grant does not have a 1 day interval message for open date and it's one day out, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    interval_type = '1Day' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                $title = $name . " open date is coming up tomorrow";
                $body = $name . " is opening on " . $open_date ;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body ,'2', $id, 'open', '1Day', 'sent');
                } else {
                    update_message_title_and_body($id, 'open', '1Day', $title, $body);
                }
            }
            if($due_date > $currentDate && $due_date <= $oneDayAhead){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    (interval_type = '1Week' or interval_type = '1Month' or interval_type = '6Months' or interval_type = 'late')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'due', '1Day');
                }

                //If the grant does not have a 1 day interval message for due date and it's one day out, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    interval_type = '1Day' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                $title = $name . " due date is coming up tomorrow";
                $body = $name . " is due on " . $due_date ;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body, '2', $id, 'due', '1Day', 'sent');
                } else {
                    update_message_title_and_body($id, 'due', '1Day', $title, $body);
                }
            }

            //The Logic for One Week Out
            if($open_date > $currentDate && $open_date <= $oneWeekAhead && $open_date > $oneDayAhead){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    (interval_type = '1Day' or interval_type = '1Month' or interval_type = '6Months' or interval_type = 'late')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'open', '1Week');
                }

                //If the grant does not have a 1 week interval message for open date and it's one week out, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    interval_type = '1Week' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if ($open_date == $oneWeekAhead) {
                    $title = $name . " open date is coming up in one week";
                } else {
                    $title = $name . " open date is coming up in less than one week";
                }
                $body = $name . " is opening on " . $open_date ;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body ,'2', $id, 'open', '1Week', 'sent');
                } else {
                    update_message_title_and_body($id, 'open', '1Week', $title, $body);
                }
            }
            if($due_date > $currentDate && $due_date <= $oneWeekAhead && $due_date > $oneDayAhead){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    (interval_type = '1Day' or interval_type = '1Month' or interval_type = '6Months' or interval_type = 'late')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'due', '1Week');
                }

                //If the grant does not have a 1 week interval message for due date and it's one week out, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    interval_type = '1Week' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if ($due_date == $oneWeekAhead) {
                    $title = $name . " due date is coming up in one week";
                } else {
                    $title = $name . " due date is coming up in less than one week";
                }
                $body = $name . " is due on " . $due_date ;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body, '2', $id, 'due', '1Week', 'sent');
                } else {
                    update_message_title_and_body($id, 'due', '1Week', $title, $body);
                }
            }

            //The Logic for One Month Out
            if($open_date >= $currentDate && $open_date <= $oneMonthAhead && $open_date > $oneWeekAhead){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    (interval_type = '1Day' or interval_type = '1Week' or interval_type = '6Months' or interval_type = 'late')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'open', '1Month');
                }

                //If the grant does not have a 1 month interval message for open date and it's one month out, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    interval_type = '1Month' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if ($open_date == $oneMonthAhead) {
                    $title = $name . " open date is coming up in one month";
                } else {
                    $title = $name . " open date is coming up in less than one month";
                }
                $body = $name . " is opening on " . $open_date ;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body , '1', $id, 'open', '1Month', 'sent');
                } else {
                    update_message_title_and_body($id, 'open', '1Month', $title, $body);
                }
            }
            if($due_date > $currentDate && $due_date <= $oneMonthAhead && $due_date > $oneWeekAhead){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    (interval_type = '1Day' or interval_type = '1Week' or interval_type = '6Months' or interval_type = 'late')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'due', '1Month');
                }

                //If the grant does not have a 1 month interval message for due date and it's one month out, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    interval_type = '1Month' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if ($due_date == $oneMonthAhead) {
                    $title = $name . " due date is coming up in one month";
                } else {
                    $title = $name . " due date is coming up in less than one month";
                }
                $body = $name . " is due on " . $due_date;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body ,'1', $id, 'due', '1Month', 'sent');
                } else {
                    update_message_title_and_body($id, 'due', '1Month', $title, $body);
                }
            }

            //The Logic for Six Months Out
            if($open_date >= $currentDate && $open_date <= $sixMonthsAhead && $open_date > $oneMonthAhead){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    (interval_type = '1Day' or interval_type = '1Week' or interval_type = '1Month' or interval_type = 'late')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'open', '6Months');
                }

                //If the grant does not have a 6 month interval message for open date and it's 6 months out, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'open' and 
                    interval_type = '6Months' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if ($open_date == $sixMonthsAhead) {
                    $title = $name . " open date is coming up in six months";
                } else {
                    $title = $name . " open date is coming up in less than six months";
                }
                $body = $name . " is opening on " . $open_date ;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body ,'1', $id, 'open', '6Months', 'sent');
                } else {
                    update_message_title_and_body($id, 'open', '6Months', $title, $body);
                }
            }
            if($due_date >= $currentDate && $due_date <= $sixMonthsAhead && $due_date > $oneMonthAhead){
                //First, check if there are automatic messages already there, if so delete them
                $query = "select * from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    (interval_type = '1Day' or interval_type = '1Week' or interval_type = '1Month' or interval_type = 'late')
                    and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                if ($result2) {
                    delete_auto_messages_of_grantID_and_type_except_interval($id, 'due', '6Months');
                }

                //If the grant does not have a 6 month interval message for due date and it's 6 months out, add it to dbmessages
                $query = "select count(*) as count from dbmessages where grant_id = " . $id . " and message_type = 'due' and 
                    interval_type = '6Months' and (sent = 'sent' or sent = 'dismissed')";
                $result2 = mysqli_query($connection, $query);
                $row2 = mysqli_fetch_assoc($result2);
                if ($due_date == $sixMonthsAhead) {
                    $title = $name . " due date is coming up in six months";
                } else {
                    $title = $name . " due date is coming up in less than six months";
                }
                $body = $name . " is due on " . $due_date;
                if ($row2['count'] == 0) {
                    message_all_users_prio('vmsroot', 'vmsroot', $title, $body ,'1', $id, 'due', '6Months', 'sent');
                } else {
                    update_message_title_and_body($id, 'due', '6Months', $title, $body);
                }
            }
        }
    }
    mysqli_close($connection);    
    return true;
}

//dateChecker();
//Method Type 1: For Upcoming Appointments
//message_all_users('vmsroot', 'message all users test', "does this work?");
//send_message('vmsroot', 'rwarren@mail.umw.edu', 'I am a bad test """""!!ASDF', "helloAAA'''ffdf!!$$");

//Method Type 2: For Animal Updates 
//message_all_users_prio('vmsroot', 'Baxter needs his rabies shot in 2 weeks', "does this work?",'1');
//message_all_users_prio('vmsroot', 'Snuffles needs to get neutered in the next 3 days, she is a menace to society', "does this work?",'2');
//message_all_users_prio('vmsroot', 'PABLO IS LATE ON HIS HEARTWORM SHOT', "hi",'3');