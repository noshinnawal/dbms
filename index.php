<?php
/**
 * Main Entry Point
 * Redirects to dashboard if logged in, otherwise to login page.
 */
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: modules/dashboard/index.php");
} else {
    header("Location: modules/auth/login.php");
}
exit;
