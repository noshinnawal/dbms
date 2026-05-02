<?php
/**
 * Registration Backend Processor
 * Handles student registration logic. (Faculty registration is deprecated)
 */
require_once __DIR__ . '/../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register_choice.php');
    exit();
}

// 1. Receive POST data
$role = $_POST['role'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// 2. Validate inputs
$errors = [];

if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
    $errors[] = "All fields are required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
}

if ($role !== 'student') {
    $errors[] = "Invalid role selected.";
}

// Role-specific validation
if ($role === 'student') {
    $student_number = $_POST['student_number'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    if (empty($student_number)) {
        $errors[] = "Student number is required.";
    }
}

if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header('Location: register_student.php');
    exit();
}

try {
    // 3. Check if email or username already exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email or Username already exists.";
        header('Location: register_student.php');
        exit();
    }

    // 4. Start a Database Transaction
    $pdo->beginTransaction();

    // 5. Insert into users table
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, first_name, last_name) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $email, $role, $first_name, $last_name]);
    
    // 6. Get the new user_id
    $user_id = $pdo->lastInsertId();

    // 7. Insert into role-specific table
    if ($role === 'student') {
        $enrollment_date = empty($_POST['enrollment_date']) ? date('Y-m-d') : $_POST['enrollment_date'];
        $stmt = $pdo->prepare("INSERT INTO students (user_id, student_number, date_of_birth, phone, address, enrollment_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $student_number, $date_of_birth, $phone, $address, $enrollment_date]);
    }

    // 8. Commit Transaction
    $pdo->commit();

    // 9. Redirect to login.php with success message
    $_SESSION['success'] = "Registration successful! Please log in.";
    header('Location: login.php?success=registered');
    exit();

} catch (Exception $e) {
    // 10. If error, roll back and redirect back to form
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['error'] = "Registration failed: " . $e->getMessage();
    header('Location: register_student.php');
    exit();
}
