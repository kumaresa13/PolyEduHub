
<?php
// Including the database connection file
require_once 'includes/db-connection.php';

// Attempt to connect to the database
try {
    $pdo = getDbConnection();
    echo "Database connection successful!";
} catch (Exception $e) {
    echo "Failed to connect to the database: " . $e->getMessage();
}
?>
