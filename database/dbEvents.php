<?php
/*
 * Copyright 2013 by Jerrick Hoang, Ivy Xing, Sam Roberts, James Cook, 
 * Johnny Coster, Judy Yang, Jackson Moniaga, Oliver Radwan, 
 * Maxwell Palmer, Nolan McNair, Taylor Talmage, and Allen Tucker. 
 * This program is part of RMH Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/**
 * @version March 1, 2012
 * @author Oliver Radwan and Allen Tucker
 */

/* 
 * Created for Gwyneth's Gift in 2022 using original Homebase code as a guide
 */


include_once('dbinfo.php');
include_once('dbMessages.php');
include_once(dirname(__FILE__).'/../domain/Event.php');
require_once(dirname(__FILE__).'/../domain/Grant.php');
require_once(dirname(__FILE__).'/../domain/Link.php');
require_once(dirname(__FILE__).'/../domain/Field.php');


/*
 * add an event to dbEvents table: if already there, return false
 */

function add_event($event)
{
    if (!$event instanceof Event)
        die("Error: add_event type mismatch");
    $con = connect();
    $query = "SELECT * FROM dbEvents WHERE id = '" . $event->get_id() . "'";
    $result = mysqli_query($con, $query);
    //if there's no entry for this id, add it
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con, 'INSERT INTO dbEvents VALUES("' .
            $event->get_id() . '","' .
            $event->get_event_date() . '","' .
            $event->get_venue() . '","' .
            $event->get_event_name() . '","' .
            $event->get_description() . '","' .
            $event->get_event_id() .
            '");');
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}


/*
 * remove an event from dbEvents table.  If already there, return false
 */

function remove_event($id) {
    $con=connect();
    $query = 'SELECT * FROM dbEvents WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $query = 'DELETE FROM dbEvents WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return true;
}


/*
 * @return an Event from dbEvents table matching a particular id.
 * if not in table, return false
 */

function retrieve_event($id) {
    $con=connect();
    $query = "SELECT * FROM dbEvents WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    // var_dump($result_row);
    $theEvent = make_an_event($result_row);
//    mysqli_close($con);
    return $theEvent;
}

function retrieve_event2($id) {
    $con=connect();
    $query = "SELECT * FROM dbEvents WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
//    var_dump($result_row);
    return $result_row;
}

// not in use, may be useful for future iterations in changing how events are edited (i.e. change the remove and create new event process)
function update_event_date($id, $new_event_date) {
	$con=connect();
	$query = 'UPDATE dbEvents SET event_date = "' . $new_event_date . '" WHERE id = "' . $id . '"';
	$result = mysqli_query($con,$query);
	mysqli_close($con);
	return $result;
}

function fetch_events_in_date_range($start_date, $end_date) {
    $connection = connect();
    $start_date = mysqli_real_escape_string($connection, $start_date);
    $end_date = mysqli_real_escape_string($connection, $end_date);
    $query = "select * from dbEvents
              where due_date >= '$start_date' and due_date <= '$end_date'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    require_once('include/output.php');
    $events = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $key = $result_row['open_date'];
        if (isset($events[$key])) {
            $events[$key] []= hsc($result_row);
        } else {
            $events[$key] = array(hsc($result_row));
        }
        $key = $result_row['due_date'];
        if (isset($events[$key])) {
            $events[$key] []= hsc($result_row);
        } else {
            $events[$key] = array(hsc($result_row));
        }
    }
    mysqli_close($connection);
    return $events;
}

function fetch_events_on_date($date) {
    $connection = connect();
    $date = mysqli_real_escape_string($connection, $date);
    $query = "select * from dbEvents
              where open_date = '$date' or due_date = '$date'";
    $results = mysqli_query($connection, $query);
    if (!$results) {
        mysqli_close($connection);
        return null;
    }
    require_once('include/output.php');
    $events = [];
    foreach ($results as $row) {
        $events []= hsc($row);
    }
    mysqli_close($connection);
    return $events;
}

function make_field($result_row){
    $field = new Field(
        null,
        $result_row['field-name'],
        $result_row['field-data']
    );
    return $field;
}

function add_field($field, $grant_id) {
    if(!$field instanceOf Field){
    die("type mismatch -- add fields");
    }
    $connection = connect();
    $name = $field->getName();
    $fData = $field->getData();
    $query = "insert into dbfields (name, data, grant_id) values ('$name', '$fData', '$grant_id')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
    return null;
    }
    return mysqli_insert_id($connection);
    }

function fetch_event_by_id($id) {
    $connection = connect();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "select * from dbEvents where id = '$id'";
    $result = mysqli_query($connection, $query);
    $event = mysqli_fetch_assoc($result);
    if ($event) {
        require_once('include/output.php');
        $event = hsc($event);
        mysqli_close($connection);
        return $event;
    }
    mysqli_close($connection);
    return null;
}

function make_grant($result_row){
    return new Grant(
        null,
        $result_row['name'],
        $result_row['funder'],
        $result_row['open_date'],
        $result_row['due_date'],
        $result_row['description'],
        $result_row['completed'],
        $result_row['type'],
        $result_row['partners'],
        $result_row['amount'],
        null
    );
}

function add_grant($grant) {
    if(!$grant instanceOf Grant){
        die("type mismatch -- add grant");
    }
    $connection = connect();
    $name = $grant->getName();
    $funder = $grant->getFunder();
    $opendate = $grant->getOpenDate();
    $duedate = $grant->getDueDate();
    $description = $grant->getDescription();
    $completed = $grant->getCompleted();
    $type = $grant->getType();
    $partners = $grant->getPartners();
    $amount = $grant->getAmount();
    $archived = "no";
    $query = "insert into dbevents (name, funder, open_date, due_date, description, completed, type, partners, amount, archived)
    values ('$name', '$funder', '$opendate', '$duedate', '$description', '$completed', '$type', '$partners', '$amount', '$archived')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    return mysqli_insert_id($connection);
}

function get_grant_id($event){
    $connection = connect();
    $name = $event->getName();
    $query = "select id from dbevents where name = '$name'";
    $result = $connection->query($query);
    $row = mysqli_fetch_array($result);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $row[0];
}


function add_services_to_event($eventID, $serviceIDs) {
    $connection = connect();
    foreach($serviceIDs as $serviceID) {
        $query = "insert into dbEventsServices (eventID, serviceID) values ('$eventID', '$serviceID')";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            return null;
        }
        $id = mysqli_insert_id($connection);
    }
    mysqli_commit($connection);
    return $id;
}

function update_event($eventID, $eventDetails) {
    $connection = connect();
    var_dump($eventDetails);
    $name = $eventDetails["name"];
    $open_date = $eventDetails["open_date"];
    $due_date = $eventDetails["due_date"];
    $completed = $eventDetails["completed"];
    $description = $eventDetails["description"];
    $type = $eventDetails["type"];
    $partners = $eventDetails["partners"];
    $amount = $eventDetails["amount"];
    
    $query = "
        update dbEvents set name='$name', completed='$completed', open_date='$open_date', due_date='$due_date', 
        description='$description', type='$type', partners='$partners', amount='$amount' where id='$eventID'";
    $result = mysqli_query($connection, $query);
    //update_services_for_event($eventID, $services);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $result;
}

function update_event2($eventID, $eventDetails) {
    $connection = connect();
    $name = $eventDetails["name"];
    $abbrevName = $eventDetails["abbrevName"];
    $date = $eventDetails["date"];
    $startTime = $eventDetails["startTime"];
    $endTime = $eventDetails["endTime"];
    $description = $eventDetails["description"];
    $location = $eventDetails["locationID"];
    $capacity = $eventDetails["capacity"];
    $animalID = $eventDetails["animalID"];
    $completed = $eventDetails["completed"];
    $query = "
        update dbEvents set name='$name', abbrevName='$abbrevName', date='$date', startTime='$startTime', endTime='$endTime', description='$description', locationID='$location', capacity='$capacity', animalId='$animalID', completed='$completed'
        where id='$eventID'
    ";
    $result = mysqli_query($connection, $query);
    //update_services_for_event($eventID, $services);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $result;
}

function find_event($nameLike) {
    $connection = connect();
    $query = "
        select * from dbEvents
        where name like '%$nameLike%'
    ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $all = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $all;
}

function fetch_event_open($open_date) {
    $connection = connect();
    $date = mysqli_real_escape_string($connection, $open_date);
    $query = "SELECT * from dbEvents WHERE open_date = '$date'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function fetch_event_due($due_date) {
    $connection = connect();
    $date = mysqli_real_escape_string($connection, $due_date);
    $query = $query = "SELECT * from dbEvents WHERE due_date = '$date'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function fetch_events_as_array() {
    $connection = connect();
    $query = "select * from dbEvents
              order by open_date asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

//returs false if there are no archived grants
function check_archived_grants($grants) {
    foreach ($grants as $grant) {
        if ($grant['archived'] == 'yes') : return true; endif;
    }
    return false;
}

function get_animal($id) {
    $connection = connect();
    $query = "select * from dbAnimals
              where id='$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $animal = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $animal;
}

function get_location($id) {
    $connection = connect();
    $query = "select * from dbLocations
              where id='$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $location = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $location;
}

function get_media($id, $type) {
    $connection = connect();
    $query = "select * from dbEventMedia
              where eventID='$id' and type='$type'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $media = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $media;
}

function get_event_training_media($id) {
    return get_media($id, 'training');
}

function get_post_event_media($id) {
    return get_media($id, 'post');
}

function attach_media($eventID, $type, $url, $format, $description) {
    $query = "insert into dbEventMedia
              (eventID, type, url, format, description)
              values ('$eventID', '$type', '$url', '$format', '$description')";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    mysqli_close($connection);
    if (!$result) {
        return false;
    }
    return true;
}

function attach_event_training_media($eventID, $url, $format, $description) {
    return attach_media($eventID, 'training', $url, $format, $description);
}

function attach_post_event_media($eventID, $url, $format, $description) {
    return attach_media($eventID, 'post', $url, $format, $description);
}

function detach_media($mediaID) {
    $query = "delete from dbEventMedia where id='$mediaID'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    mysqli_close($connection);
    if ($result) {
        return true;
    }
    return false;
}

function delete_event($id) {
    delete_message_of_grantID($id);
    $query = "delete from dbEvents where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function archive_grant($id) {
    $query = "UPDATE dbEvents set archived='yes' where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function unarchive_grant($id) {
    $query = "UPDATE dbEvents set archived='no' where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function find_archived() {
    $query = "select * from dbEvents where archived='yes' order by name";

    $connection = connect();

    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return [];
    }
    $raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $events = [];
    mysqli_close($connection);
    return $events;

}

//    var_dump($event);

function update_animal2($animal) {
    $connection = connect();
    $id = $animal['id'];
	$odhsid = $animal["odhs_id"];
    $name = $animal["name"];
	$breed = $animal["breed"];
    $age = $animal["age"];
    $gender = $animal["gender"];
    $notes = $animal["notes"];
    $spay_neuter_done = $animal["spay_neuter_done"];
	$spay_neuter_date = $animal["spay_neuter_date"];
    if (empty($animal["spay_neuter_date"])) {
        $spay_neuter_date = '0000-00-00';
    }
    $rabies_given_date = $animal["rabies_given_date"];
    if (empty($animal["rabies_given_date"])) {
        $rabies_given_date = '0000-00-00';
    }
	$rabies_due_date = $animal["rabies_due_date"];
    if (empty($animal["rabies_due_date"])) {
        $rabies_due_date = '0000-00-00';
    }
    $heartworm_given_date = $animal["heartworm_given_date"];
    if (empty($animal["heartworm_given_date"])) {
        $heartworm_given_date = '0000-00-00';
    }
	$heartworm_due_date = $animal["heartworm_due_date"];
    if (empty($animal["heartworm_due_date"])) {
        $heartworm_due_date = '0000-00-00';
    }
	$distemper1_given_date = $animal["distemper1_given_date"];
    if (empty($animal["distemper1_given_date"])) {
        $distemper1_given_date = '0000-00-00';
    }
	$distemper1_due_date = $animal["distemper1_due_date"];
    if (empty($animal["distemper1_due_date"])) {
        $distemper1_due_date = '0000-00-00';
    }
	$distemper2_given_date = $animal["distemper2_given_date"];
    if (empty($animal["distemper2_given_date"])) {
        $distemper2_given_date = '0000-00-00';
    }
	$distemper2_due_date = $animal["distemper2_due_date"];
    if (empty($animal["distemper2_due_date"])) {
        $distemper2_due_date = '0000-00-00';
    }
	$distemper3_given_date = $animal["distemper3_given_date"];
    if (empty($animal["distemper3_given_date"])) {
        $distemper3_given_date = '0000-00-00';
    }
	$distemper3_due_date = $animal["distemper3_due_date"];
    if (empty($animal["distemper3_due_date"])) {
        $distemper3_due_date = '0000-00-00';
    }
	$microchip_done = $animal["microchip_done"];
    $query = "
        UPDATE dbAnimals set odhs_id='$odhsid', name='$name', breed='$breed', age='$age', gender='$gender', notes='$notes', spay_neuter_done='$spay_neuter_done', spay_neuter_date='$spay_neuter_date', rabies_given_date='$rabies_given_date', rabies_due_date='$rabies_due_date', heartworm_given_date='$heartworm_given_date', heartworm_due_date='$heartworm_due_date', distemper1_given_date='$distemper1_given_date', distemper1_due_date='$distemper1_due_date', distemper2_given_date='$distemper2_given_date', distemper2_due_date='$distemper2_due_date', distemper3_given_date='$distemper3_given_date', distemper3_due_date='$distemper3_due_date', microchip_done='$microchip_done'
        where id='$id'
        ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}

/* generate report things*/
function fetch_event_open_range($start_date, $stop_date) {
   $connection = connect();
   $beg_date = mysqli_real_escape_string($connection, $start_date);
   $end_date = mysqli_real_escape_string($connection, $stop_date);
   $query = "SELECT * from dbEvents WHERE open_date >= '$beg_date' and open_date <= '$end_date'";
   $result = mysqli_query($connection, $query);

   if (!$result) {
       mysqli_close($connection);
       return null;
   }

   $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
   mysqli_close($connection);
   return $events;

}


?>