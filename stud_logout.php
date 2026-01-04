<?php
session_start();

// Unset all student session variables


// Destroy the session completely
session_destroy();

// Redirect to student login page
header("Location: studlogin.php");
exit;
?>
