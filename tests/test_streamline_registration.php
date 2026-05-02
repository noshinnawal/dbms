<?php
/**
 * Test Streamline Registration
 * This script verifies that student registration still works and faculty registration is rejected.
 */

// Set up the environment
$_SERVER['REQUEST_METHOD'] = 'POST';
$base_dir = dirname(__DIR__);
$php_bin = 'C:\\xampp\\php\\php.exe';

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
    
    // 1. Test Student Registration (Should still work)
    echo "Testing Student Registration...\n";
    $test_student = [
        'role' => 'student',
        'first_name' => 'Test',
        'last_name' => 'Student',
        'email' => 'teststudent@example.com',
        'username' => 'teststudent',
        'password' => 'password123',
        'confirm_password' => 'password123',
        'student_number' => 'STU-TEST-002',
        'date_of_birth' => '2000-01-01',
        'phone' => '1234567890',
        'address' => 'Test Address'
    ];

    cleanup($pdo, $test_student['username'], $test_student['email']);

    $wrapper_content = "<?php
\$_POST = " . var_export($test_student, true) . ";
\$_SERVER['REQUEST_METHOD'] = 'POST';
include '" . $base_dir . "/modules/auth/register_process.php';
";
    $wrapper_path = $base_dir . '/tests/temp_wrapper_student.php';
    file_put_contents($wrapper_path, $wrapper_content);
    
    shell_exec(escapeshellarg($php_bin) . " " . escapeshellarg($wrapper_path));
    unlink($wrapper_path);

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
    echo "✔ Student record created and verified.\n";

    // 2. Test Faculty Registration (Should fail or be rejected)
    echo "\nTesting Faculty Registration (should be rejected)...\n";
    $test_faculty = [
        'role' => 'faculty',
        'first_name' => 'Test',
        'last_name' => 'Faculty',
        'email' => 'testfaculty@example.com',
        'username' => 'testfaculty',
        'password' => 'password123',
        'confirm_password' => 'password123',
        'employee_number' => 'EMP-TEST-002',
        'department' => 'Computer Science',
        'title' => 'Professor',
        'hire_date' => '2024-01-01',
        'office_location' => 'Room 101'
    ];

    cleanup($pdo, $test_faculty['username'], $test_faculty['email']);

    $wrapper_content = "<?php
session_start();
\$_POST = " . var_export($test_faculty, true) . ";
\$_SERVER['REQUEST_METHOD'] = 'POST';
include '" . $base_dir . "/modules/auth/register_process.php';
echo 'SESSION_ERROR=' . (\$_SESSION['error'] ?? '') . \"\\n\";
";
    $wrapper_path = $base_dir . '/tests/temp_wrapper_faculty.php';
    file_put_contents($wrapper_path, $wrapper_content);
    $output = shell_exec(escapeshellarg($php_bin) . " " . escapeshellarg($wrapper_path));
    unlink($wrapper_path);

    if (strpos($output, 'Invalid role selected') !== false) {
        echo "✔ Faculty registration correctly rejected with 'Invalid role selected'.\n";
    } else {
        // Check if user was created anyway (it shouldn't be)
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$test_faculty['username']]);
        if ($stmt->fetch()) {
            throw new Exception("Faculty user was created despite being deprecated!");
        }
        echo "✔ Faculty registration was not successful (as expected).\n";
    }

    echo "\nAll streamlined registration tests passed!\n";

    // Final cleanup
    cleanup($pdo, $test_student['username'], $test_student['email']);
    cleanup($pdo, $test_faculty['username'], $test_faculty['email']);

} catch (Exception $e) {
    echo "Test Failed: " . $e->getMessage() . "\n";
    exit(1);
}
