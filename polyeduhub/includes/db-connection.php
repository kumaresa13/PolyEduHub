<?php
// includes/db-connection.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "polyeduhub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Get database connection
 * @return PDO The database connection
 */
function getDbConnection() {
    static $pdo;
    
    if (!$pdo) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // For development, show the error
            die("Database connection failed: " . $e->getMessage());
            
            // For production, show a user-friendly error
            // die("We're experiencing technical difficulties. Please try again later.");
        }
    }
    
    return $pdo;
}

// Connect to the database (will be established when needed)
// $conn = getDbConnection();

/**
 * Execute a query and return all results
 * 
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters for the prepared statement
 * @return array Results of the query
 */
function dbSelect($sql, $params = []) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Execute a query and return a single row
 * 
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters for the prepared statement
 * @return array|false Single row result or false if no results
 */
function dbSelectOne($sql, $params = []) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * Execute an insert, update, or delete query
 * 
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters for the prepared statement
 * @return int Number of affected rows
 */
function dbExecute($sql, $params = []) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/**
 * Insert data into a table and return the last insert ID
 * 
 * @param string $table Table name
 * @param array $data Associative array of column names and values
 * @return int The last insert ID
 */
function dbInsert($table, $data) {
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($data));
    
    return $pdo->lastInsertId();
}

/**
 * Update data in a table
 * 
 * @param string $table Table name
 * @param array $data Associative array of column names and values to update
 * @param string $where WHERE clause with placeholders
 * @param array $whereParams Parameters for the WHERE clause
 * @return int Number of affected rows
 */
function dbUpdate($table, $data, $where, $whereParams = []) {
    $set = [];
    foreach (array_keys($data) as $column) {
        $set[] = "$column = ?";
    }
    
    $sql = "UPDATE $table SET " . implode(', ', $set) . " WHERE $where";
    
    $params = array_merge(array_values($data), $whereParams);
    
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->rowCount();
}

/**
 * Delete data from a table
 * 
 * @param string $table Table name
 * @param string $where WHERE clause with placeholders
 * @param array $params Parameters for the WHERE clause
 * @return int Number of affected rows
 */
function dbDelete($table, $where, $params = []) {
    $sql = "DELETE FROM $table WHERE $where";
    
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->rowCount();
}
?>