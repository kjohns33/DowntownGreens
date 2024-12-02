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
require_once(dirname(__FILE__).'/../domain/Grant.php');
require_once(dirname(__FILE__).'/../domain/Link.php');
require_once(dirname(__FILE__).'/../domain/Field.php');

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
function check_archived_grants($grants)
{
    foreach ($grants as $grant) {
        if ($grant['archived'] == 'yes') : return true; endif;
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