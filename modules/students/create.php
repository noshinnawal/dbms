<?php
/**
 * Create Student
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    
    $student_number = trim($_POST['student_number']);
    $login_id = trim($_POST['login_id'] ?? '');
    $dob = $_POST['dob'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $enrollment_date = $_POST['enrollment_date'] ?: date('Y-m-d');

    try {
        $pdo->beginTransaction();

        // 1. Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, first_name, last_name) VALUES (?, ?, ?, 'student', ?, ?)");
        $stmt->execute([$username, $password, $email, $first_name, $last_name]);
        $user_id = $pdo->lastInsertId();

        // 2. Insert into students table
        $stmt = $pdo->prepare("INSERT INTO students (user_id, student_number, login_id, date_of_birth, phone, address, enrollment_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $student_number, $login_id, $dob, $phone, $address, $enrollment_date]);

        $pdo->commit();
        $success = "Student created successfully!";
        // Redirect to list after short delay? Or just show message.
    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == 23000) { // Unique constraint violation
            $error = "Error: Username, Email, or Student Number already exists.";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

$pageTitle = "Add Student";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="mb-10">
        <h1 class="text-3xl font-bold text-on-surface">Add New Student</h1>
        <p class="text-on-surface-variant mt-1">Create a new user account and student profile.</p>
    </header>

    <?php if ($error): ?>
        <div class="mb-8 p-4 bg-error-container text-on-error-container rounded-2xl text-sm font-medium">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="mb-8 p-4 bg-primary-container text-primary rounded-2xl text-sm font-medium">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form action="create.php" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Account Information -->
        <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">account_circle</span>
                Account Information
            </h2>
            <div class="flex flex-col gap-6">
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="username">Username</label>
                    <input type="text" id="username" name="username" required
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="email">Email Address</label>
                    <input type="email" id="email" name="email" required
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="password">Initial Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>
            </div>
        </section>

        <!-- Profile Information -->
        <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">badge</span>
                Student Profile
            </h2>
            <div class="flex flex-col gap-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="student_number">Student ID</label>
                        <input type="text" id="student_number" name="student_number" required placeholder="STU-2026-001"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="login_id">Student Login ID</label>
                        <input type="text" id="login_id" name="login_id" placeholder="e.g. jdoe2026"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="enrollment_date">Enrollment Date</label>
                        <input type="date" id="enrollment_date" name="enrollment_date"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone"
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="address">Mailing Address</label>
                    <textarea id="address" name="address" rows="3"
                              class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 resize-none"></textarea>
                </div>
            </div>
        </section>

        <!-- Submit -->
        <div class="lg:col-span-2 flex justify-end gap-4 mt-4">
            <a href="index.php" class="px-8 py-4 rounded-2xl text-on-surface-variant font-bold hover:text-on-surface transition-all">Cancel</a>
            <button type="submit" class="px-10 py-4 bg-surface-container rounded-2xl font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] active:scale-95 transition-all">
                Save Student Record
            </button>
        </div>
    </form>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
