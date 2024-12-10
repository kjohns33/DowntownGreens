
<?php
    session_cache_expire(30);
    session_start();

    if ($_SESSION['access_level'] < 2 || $_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location: index.php');
        die();
    }
    require_once('database/dbEvents.php');
    require_once('include/input-validation.php');
    $args = sanitize($_POST);
    $id = $args['id'];
    if (!$id) {
        header('Location: index.php');
        die();
    }

    if (getReportCount($id) > 0) {
        unarchive_all_reports($id);
    }
    if (unarchive_grant($id)) {
        header('Location: viewArchived.php?unarchiveSuccess');
        die();
    }
    header('Location: index.php');
?>