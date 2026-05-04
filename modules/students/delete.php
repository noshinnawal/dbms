<?php
/**
 * Delete Student
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$student_id = $_GET['id'] ?? null;

if ($student_id) {
    try {
        // 1. Get user_id first to delete from users table (cascades to students)
        $stmt = $pdo->prepare("SELECT user_id FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch();

        if ($student) {
            // Delete from users table - this will cascade delete the student record
            $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->execute([$student['user_id']]);
        }
        
        // Redirect back to list
        header('Location: index.php?msg=Student deleted successfully');
        exit;

    } catch (PDOException $e) {
        die("Error deleting student: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
