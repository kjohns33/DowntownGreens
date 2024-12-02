<?php
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHC-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/*
 * Created on Mar 28, 2008
 * @author Oliver Radwan <oradwan@bowdoin.edu>, Sam Roberts, Allen Tucker
 * @version 3/28/2008, revised 7/1/2015
 */

$accessLevelsByRole = [
	'admin' => 2,
	'superadmin' => 3
];

class Person {
	private $id;         // id (unique key) = first_name . phone1
	private $first_name; // first name as a string
	private $last_name;  // last name as a string
  	private $profile_pic; // image link
	private $type;       // array of "volunteer", "weekendmgr", "sub", "guestchef", "events", "projects", "manager"
	private $access_level;
	private $status;     // a person may be "active" or "inactive"
	private $password;     // password for calendar and database access: default = $id
	private $mustChangePassword;

	function __construct($f, $l, $pp, $e, $t, $st, $pass, $mcp) {
		$this->id = $e;
		$this->first_name = $f;
		$this->last_name = $l;
        $this->profile_pic = $pp;
		$this->mustChangePassword = $mcp;
		$this->type = $t !== "" ? explode(',', $t) : array();
		$this->access_level = 2;
		$this->status = $st;
		if ($pass == "")
			$this->password = password_hash($this->id, PASSWORD_BCRYPT); // default password
		else
			$this->password = $pass;
	}

	function get_id() {
		return $this->id;
	}

	function get_first_name() {
		return $this->first_name;
	}

	function get_last_name() {
		return $this->last_name;
	}

  function get_profile_pic() {
    return $this->profile_pic;
  }

	function get_type() {
		return $this->type;
	}

	function get_status() {
		return $this->status;
	}

	function get_password() {
		return $this->password;
	}

	function get_access_level() {
		return $this->access_level;
	}

	function is_password_change_required() {
		return $this->mustChangePassword;
	}
}
