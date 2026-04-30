<?php
/**
 * Test Registration Simulation
 * This script simulates a registration request and verifies the database.
 */

// Set up the environment
$_SERVER['REQUEST_METHOD'] = 'POST';
$base_dir = dirname(__DIR__);

// Helper to clean up test data
function cleanup($pdo, $username, $email) {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch();
    if ($user) {
        $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$user['user_id']]);
        echo "Cleaned up existing test user: $username\n";
    }
}

try {
    require_once $base_dir . '/config/database.php';
    
    // 1. Test Student Registration
    echo "Testing Student Registration...\n";
    $test_student = [
        'role' => 'student',
        'first_name' => 'Test',
        'last_name' => 'Student',
        'email' => 'teststudent@example.com',
        'username' => 'teststudent',
        'password' => 'password123',
        'confirm_password' => 'password123',
        'student_number' => 'STU-TEST-001',
        'date_of_birth' => '2000-01-01',
        'phone' => '1234567890',
        'address' => 'Test Address'
    ];

    cleanup($pdo, $test_student['username'], $test_student['email']);

    $_POST = $test_student;
    
    // We can't include register_process.php directly because it calls exit()
    // Instead, we will simulate its logic here for verification, 
    // OR we can run it as a separate process and check the DB.
    
    // Let's run it as a separate process to truly test the file
    $php_bin = '/opt/lampp/bin/php';
    $cmd = "$php_bin " . escapeshellarg($base_dir . '/modules/auth/register_process.php');
    
    // We need to pass $_POST data. Since it's a CLI call, we'd need a wrapper.
    // Let's create a temporary wrapper.
    $wrapper_content = "<?php
\$_POST = " . var_export($_POST, true) . ";
\$_SERVER['REQUEST_METHOD'] = 'POST';
include '" . $base_dir . "/modules/auth/register_process.php';
";
    $wrapper_path = $base_dir . '/tests/temp_wrapper.php';
    file_put_contents($wrapper_path, $wrapper_content);
    
    $output = shell_exec("$php_bin " . escapeshellarg($wrapper_path));
    unlink($wrapper_path);
    
    // echo "Output: $output\n";

    // Verify Student in Database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$test_student['username']]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception("Student user not created in 'users' table.");
    }
    echo "✔ User record created.\n";

    $stmt = $pdo->prepare("SELECT * FROM students WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);
    $student = $stmt->fetch();

    if (!$student) {
        throw new Exception("Student record not created in 'students' table.");
    }
    if ($student['student_number'] !== $test_student['student_number']) {
        throw new Exception("Student number mismatch.");
    }
    echo "✔ Student record created and verified.\n";

    // 2. Test Faculty Registration
    echo "\nTesting Faculty Registration...\n";
    $test_faculty = [
        'role' => 'faculty',
        'first_name' => 'Test',
        'last_name' => 'Faculty',
        'email' => 'testfaculty@example.com',
        'username' => 'testfaculty',
        'password' => 'password123',
        'confirm_password' => 'password123',
        'employee_number' => 'EMP-TEST-001',
        'department' => 'Computer Science',
        'title' => 'Professor',
        'hire_date' => '2024-01-01',
        'office_location' => 'Room 101'
    ];

    cleanup($pdo, $test_faculty['username'], $test_faculty['email']);

    $_POST = $test_faculty;
    $wrapper_content = "<?php
\$_POST = " . var_export($_POST, true) . ";
\$_SERVER['REQUEST_METHOD'] = 'POST';
include '" . $base_dir . "/modules/auth/register_process.php';
";
    $php_bin = '/opt/lampp/bin/php';
    file_put_contents($wrapper_path, $wrapper_content);
    shell_exec("$php_bin " . escapeshellarg($wrapper_path));
    unlink($wrapper_path);

    // Verify Faculty in Database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$test_faculty['username']]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception("Faculty user not created in 'users' table.");
    }
    echo "✔ User record created.\n";

    $stmt = $pdo->prepare("SELECT * FROM faculty WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);
    $faculty = $stmt->fetch();

    if (!$faculty) {
        throw new Exception("Faculty record not created in 'faculty' table.");
    }
    if ($faculty['employee_number'] !== $test_faculty['employee_number']) {
        throw new Exception("Employee number mismatch.");
    }
    echo "✔ Faculty record created and verified.\n";

    echo "\nAll registration tests passed!\n";

    // Final cleanup
    cleanup($pdo, $test_student['username'], $test_student['email']);
    cleanup($pdo, $test_faculty['username'], $test_faculty['email']);

} catch (Exception $e) {
    echo "Test Failed: " . $e->getMessage() . "\n";
    exit(1);
}
