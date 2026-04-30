<?php
/**
 * Authentication Middleware
 * Ensures the user is logged in before accessing protected pages.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../modules/auth/login.php");
    exit;
}

/**
 * Check if the user has a specific role.
 * @param array $roles Allowed roles
 */
function checkRole($roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        header("Location: ../../modules/dashboard/index.php?error=unauthorized");
        exit;
    }
}
?>
