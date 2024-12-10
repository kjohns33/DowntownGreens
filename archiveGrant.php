
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
    $is_due_date = $args['is_report_date'];
    if (!$id) {
        header('Location: index.php');
        die();
    }
    if (getReportCount($id) > 0) {
        archive_all_reports($id);
    }
    if (archive_grant($id)) {
        header('Location: viewGrant.php?archiveSuccess');
        die();
    }
    header('Location: index.php');
?>