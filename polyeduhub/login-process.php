<?php
// Start session
session_start();

// Include configuration and database connection
require_once 'includes/config.php';
require_once 'includes/db-connection.php';
require_once 'includes/functions.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize email input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Invalid email format
        $_SESSION['login_error'] = "Invalid email format";
        header("Location: login.php");
        exit();
    }
    
    // Get password
    $password = $_POST['password'];
    
    // Get database connection
    $pdo = getDbConnection();
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password, role, student_id, department, year_of_study FROM users WHERE email = ? AND role = 'student'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // Check if user exists and password is correct
    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a new session
            session_regenerate_id();
            
            // Store data in session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['department'] = $user['department'];
            $_SESSION['year_of_study'] = $user['year_of_study'];
            
            // Check if remember me is checked
            if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                // Set cookies for 30 days
                setcookie('remember_email', $email, time() + (86400 * 30), "/");
                $token = bin2hex(random_bytes(16));
                setcookie('remember_token', $token, time() + (86400 * 30), "/");
                
                // Store token in database
                $user_id = $user['id'];
                $expiry = date('Y-m-d H:i:s', time() + (86400 * 30));
                $hash_token = password_hash($token, PASSWORD_DEFAULT);
                
                // Delete any existing tokens for this user
                $pdo->prepare("DELETE FROM remember_me_tokens WHERE user_id = ?")->execute([$user_id]);
                
                // Insert new token
                $pdo->prepare("INSERT INTO remember_me_tokens (user_id, token, expiry) VALUES (?, ?, ?)")
                    ->execute([$user_id, $hash_token, $expiry]);
            }
            
            // Record login action in the activity log
            $user_id = $user['id'];
            $action = "Student login";
            $details = "Student user logged in";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)")
                ->execute([$user_id, $action, $details, $ip_address]);
            
            // Update last login time
            $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")
                ->execute([$user_id]);
            
            // Redirect to student dashboard
            header("Location: student/dashboard.php");
            exit();
        } else {
            // Incorrect password
            $_SESSION['login_error'] = "Incorrect email or password";
            header("Location: login.php");
            exit();
        }
    } else {
        // User not found
        $_SESSION['login_error'] = "Incorrect email or password";
        header("Location: login.php");
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: login.php");
    exit();
}