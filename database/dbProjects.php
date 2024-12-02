<?php
include_once('dbinfo.php');
require_once(dirname(__FILE__).'/../domain/Project.php');

function make_project($result_row) {
    return new Project(
        null,
        $result_row['name'],
    );
}

function add_project($project) {
    if(!$project instanceOf Project){
        die("type mismatch -- add project");
    }
    $connection = connect();
    $name = $project->getName();
    $query = "insert into dbprojects (name) values ('$name')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    return mysqli_insert_id($connection);
}

function fetch_projects() {
    $connection = connect();
    $query = "select * from dbProjects";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function add_to_junction($project_id, $grant_id){
    $connection = connect();
    $query = "insert into dbgrantprojects (project_id, grant_id) values ('$project_id', '$grant_id')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    return mysqli_insert_id($connection);
}

function fetch_projects_for_grant($grant_id){
    $connection = connect();
    $query = "select * from dbgrantprojects where grant_id = '$grant_id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function fetch_project_by_id($project_id) {
    $connection = connect();
    $query = "select name from dbprojects where id = '$project_id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}