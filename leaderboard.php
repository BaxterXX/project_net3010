<?php
// Tells the browser to return the scripts to the JSON data
header('Content-Type: application/json');
// Imports the database connection function
require_once 'db.php';
// Initiates a connection to the database
$conn = getDB();

// Get top 20 scores (best score per user)
$sql = "
    SELECT u.username,
           MAX(s.score) AS score,
           DATE(MAX(s.played_at)) AS date
    FROM scores s
    JOIN users u ON u.id = s.user_id
    GROUP BY s.user_id, u.username
    ORDER BY score DESC
    LIMIT 20
";

// Initiates the SQL query
$result = $conn->query($sql);

// Sends an error message when the SQL query fails
if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch leaderboard.'
    ]);
    exit;
}

$rows = [];

while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);

$conn->close();
?>
