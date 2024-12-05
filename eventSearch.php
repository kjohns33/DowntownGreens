<?php
session_start();
require_once('include/input-validation.php');
require_once('database/dbEvents.php');

// Initialize variables
$searchType = $_POST['searchType'] ?? '';
$searchInput = $_POST[$searchType] ?? ''; // Dynamically fetch the input field
$search = '';
$events = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($searchType) && !empty($searchInput)) {
    switch ($searchType) {
        case 'name':
            $events = find_event($searchInput);
            $search = 'Results for Name: ' . htmlspecialchars($searchInput);
            break;
        case 'open_date':
            $events = fetch_event_open($searchInput);
            $search = 'Results for Open Date: ' . htmlspecialchars($searchInput);
            break;
        case 'due_date':
            $events = fetch_event_due($searchInput);
            $search = 'Results for Due Date: ' . htmlspecialchars($searchInput);
            break;
        case 'funder':
            $events = fetch_event_by_funder($searchInput);
            $search = 'Results for Funder: ' . htmlspecialchars($searchInput);
            break;
        case 'category':
            $events = fetch_event_by_category($searchInput);
            $search = 'Results for Category: ' . htmlspecialchars($searchInput);
            break;
        default:
            $search = 'Invalid search type.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('universal.inc') ?>
    <title>Search Grant</title>
    <link rel="stylesheet" href="eventSearch.css">
    <script src="js/search.js"></script>
</head>
<body>
    <?php require_once('header.php') ?>
    <h1>Search Grant</h1>
    <main class="search-form">
        <form method="POST" action="eventSearch.php" class="form-style">
            <label for="searchType">Search by:</label>
            <select id="searchType" name="searchType" class="dropdown-style" onchange="updateInputField()">
                <option value="">Select...</option>
                <option value="name" <?= ($searchType === 'name') ? 'selected' : ''; ?>>Name</option>
                <option value="open_date" <?= ($searchType === 'open_date') ? 'selected' : ''; ?>>Open Date</option>
                <option value="due_date" <?= ($searchType === 'due_date') ? 'selected' : ''; ?>>Close Date</option>
                <option value="funder" <?= ($searchType === 'funder') ? 'selected' : ''; ?>>Funder</option>
                <option value="category" <?= ($searchType === 'category') ? 'selected' : ''; ?>>Category</option>
            </select>

            <div id="inputContainer">
                <?php if (!empty($searchType)): ?>
                    <input type="<?= ($searchType === 'open_date' || $searchType === 'due_date') ? 'date' : 'text'; ?>"
                           name="<?= htmlspecialchars($searchType); ?>"
                           value="<?= htmlspecialchars($searchInput); ?>"
                           placeholder="Enter <?= ucfirst(str_replace('_', ' ', $searchType)); ?>" required>
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-btn">Search</button>
        </form>

        <?php if (isset($events) && count($events) > 0): ?>
            <!-- Sorting Dropdown -->
            <div class="sorting-dropdown">
                <form method="POST" action="eventSearch.php" id="sortForm">
                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sort" class="form-control">
                        <option value="">Select...</option>
                        <option value="name">Name (A-Z)</option>
                        <option value="open_date">Open Date</option>
                        <option value="due_date">Close Date</option>
                        <option value="funder">Funder (A-Z)</option>
                    </select>
                </form>
            </div>

            <!-- Results Table -->
            <div class="table-wrapper">
                <table class="general" id="eventSearchTable">
                    <thead>
                        <tr>
                            <th>Grant Name</th>
                            <th>Open Date</th>
                            <th>Close Date</th>
                            <th>Funder</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                            <tr data-href="event.php?id=<?= htmlspecialchars($event['id']) ?>">
                                <td><a href="event.php?id=<?= htmlspecialchars($event['id']) ?>"><?= htmlspecialchars($event['name']) ?></a></td>
                                <td><?= date('m/d/Y', strtotime($event['open_date'])) ?></td>
                                <td><?= date('m/d/Y', strtotime($event['due_date'])) ?></td>
                                <td><?= htmlspecialchars($event['funder'] ?? 'Unknown') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif (isset($events)): ?>
            <p>No results found.</p>
        <?php endif; ?>
    </main>
</body>
</html>
