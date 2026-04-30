<?php
/**
 * Drop Enrollment
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$enrollment_id = $_GET['id'] ?? null;

if ($enrollment_id) {
    try {
        $stmt = $pdo->prepare("UPDATE enrollments SET status = 'dropped' WHERE enrollment_id = ?");
        $stmt->execute([$enrollment_id]);
    } catch (PDOException $e) {
        // Silently fail or log
    }
}

header("Location: index.php");
exit;
