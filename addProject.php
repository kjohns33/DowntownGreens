<?php
// Make session information accessible, allowing us to associate
// data with the logged-in user.
session_cache_expire(30);
session_start();

ini_set("display_errors", 1);
error_reporting(E_ALL);

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}
// Require admin privileges
if ($accessLevel < 2) {
    header('Location: login.php');
    echo 'bad access level';
    die();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('include/input-validation.php');
    require_once('database/dbProjects.php');

    $args = sanitize($_POST, null);

        $project = make_project($args);
        $success = add_project($project);
        if($success) {
            header("Location: index.php");
        }
        exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once('universal.inc') ?>
    <title>Downtown Greens | Add Project</title>
    <style>
        input::placeholder {
            color: white;
        }
    </style>
</head>
<body>
<?php require_once('header.php') ?>
<h1>Add Project</h1>
<main class="date">
    <h2>Add Project Form</h2>
    <form id="new-project-form" method="post">
        <label for="name">* Project Name </label>
        <input type="text" id="name" name="name" required placeholder="Enter name">
        <p></p>
        <input type="submit" value="Add Project">
    </form>
    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
</main>
</body>

</html>