<?php
include_once('dbinfo.php');
require_once(dirname(__FILE__).'/../domain/Link.php');

function make_link($result_row) {
return new Link(
null,
$result_row['link-name'],
$result_row['link-data']
);
}

function add_link($link, $grant_id) {
if(!$link instanceOf Link){
die("type mismatch -- add links");
}
$connection = connect();
$name = $link->getName();
$url = $link->getURL();
$query = "insert into dblinks (name, link, grant_id) values ('$name', '$url', '$grant_id')";
$result = mysqli_query($connection, $query);
if (!$result) {
return null;
}
return mysqli_insert_id($connection);
}
