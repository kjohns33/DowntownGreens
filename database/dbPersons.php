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
include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Person.php');

/*
 * add a person to dbPersons table: if already there, return false
 */

function add_person($person) {
    if (!$person instanceof Person)
        die("Error: add_person type mismatch");
    $con=connect();
    $query = "SELECT * FROM dbPersons WHERE id = '" . $person->get_id() . "'";
    $result = mysqli_query($con,$query);
    //if there's no entry for this id, add it
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con,'INSERT INTO dbPersons VALUES("' .
            $person->get_id() . '","' .
            $person->get_first_name() . '","' .
            $person->get_last_name() . '","' .
            $person->get_password() . '","' .
            $person->get_profile_pic() . '","' .
            $person->is_password_change_required() . '","' .
            '");'
        );							
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}

/*
 * remove a person from dbPersons table.  If already there, return false
 */

function remove_person($id) {
    $con=connect();
    $query = 'SELECT * FROM dbPersons WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $query = 'DELETE FROM dbPersons WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return true;
}

/*
 * @return a Person from dbPersons table matching a particular id.
 * if not in table, return false
 */

function retrieve_person($id) {
    $con=connect();
    $query = "SELECT * FROM dbPersons WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    // var_dump($result_row);
    $thePerson = make_a_person($result_row);
//    mysqli_close($con);
    return $thePerson;
}
// Name is first concat with last name. Example 'James Jones'
// return array of Persons.

function change_password($id, $newPass) {
    $con=connect();
    $query = 'UPDATE dbPersons SET password = "' . $newPass . '", force_password_change="0" WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return $result;
}

function reset_password($id, $newPass) {
    $con=connect();
    $query = 'UPDATE dbPersons SET password = "' . $newPass . '", force_password_change="1" WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return $result;
}

/*
  @return all rows from dbPersons

*/
function getall_volunteers() {
    $con=connect();
    $query = 'SELECT * FROM dbPersons WHERE id != "vmsroot"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $result = mysqli_query($con,$query);
    $thePersons = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $thePerson = make_a_person($result_row);
        $thePersons[] = $thePerson;
    }

    return $thePersons;
}

function make_a_person($result_row) {
    $thePerson = new Person(
                    $result_row['id'],
                    $result_row['first_name'],
                    $result_row['last_name'],
                    $result_row['profile_pic'],
                    $result_row['type'],
                    $result_row['status'],
                    $result_row['password'],
                    $result_row['force_password_change'],
                );   
    return $thePerson;
}

    date_default_timezone_set("America/New_York");

    function remove_profile_picture($id) {
        $con=connect();
        $query = 'UPDATE dbPersons SET profile_pic="" WHERE id="'.$id.'"';
        $result = mysqli_query($con,$query);
        mysqli_close($con);
        return True;
    }

    function get_name_from_id($id) {
        if ($id == 'vmsroot') {
            return 'System';
        }
        $query = "select first_name, last_name from dbPersons
            where id='$id'";
        $connection = connect();
        $result = mysqli_query($connection, $query);
        if (!$result) {
            return null;
        }

        $row = mysqli_fetch_assoc($result);
        mysqli_close($connection);
        return $row['first_name'] . ' ' . $row['last_name'];
    }
