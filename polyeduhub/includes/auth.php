
<?php

function checkStudentLogin() {
    session_start();
    if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
        $_SESSION['login_error'] = "Please log in to access this page.";
        header("Location: ../../login.php");
        exit();
    }
}
?>