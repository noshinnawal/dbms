<?php
/**
 * Create Faculty Member
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
    
    $employee_number = trim($_POST['employee_number']);
    $department = trim($_POST['department']);
    $title = trim($_POST['title']);
    $hire_date = $_POST['hire_date'] ?: date('Y-m-d');
    $office_location = trim($_POST['office_location']);

    try {
        $pdo->beginTransaction();

        // 1. Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, first_name, last_name) VALUES (?, ?, ?, 'faculty', ?, ?)");
        $stmt->execute([$username, $password, $email, $first_name, $last_name]);
        $user_id = $pdo->lastInsertId();

        // 2. Insert into faculty table
        $stmt = $pdo->prepare("INSERT INTO faculty (user_id, employee_number, department, title, hire_date, office_location) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $employee_number, $department, $title, $hire_date, $office_location]);

        $pdo->commit();
        $success = "Faculty member added successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == 23000) {
            $error = "Error: Username, Email, or Employee Number already exists.";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

$pageTitle = "Add Faculty";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="mb-10">
        <h1 class="text-3xl font-bold text-on-surface">Add Faculty Member</h1>
        <p class="text-on-surface-variant mt-1">Register a new academic staff member.</p>
    </header>

    <?php if ($error): ?>
        <div class="mb-8 p-4 bg-error-container text-on-error-container rounded-2xl text-sm font-medium">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="mb-8 p-4 bg-secondary-container text-on-secondary-container rounded-2xl text-sm font-medium">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form action="create.php" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Account Info -->
        <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">admin_panel_settings</span>
                Account Access
            </h2>
            <div class="flex flex-col gap-6">
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="username">Username</label>
                    <input type="text" id="username" name="username" required
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="email">Work Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="password">Temporary Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                    </div>
                </div>
            </div>
        </section>

        <!-- Professional Info -->
        <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">work</span>
                Professional Profile
            </h2>
            <div class="flex flex-col gap-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="employee_number">Employee ID</label>
                        <input type="text" id="employee_number" name="employee_number" required placeholder="FAC-2026-001"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="hire_date">Hire Date</label>
                        <input type="date" id="hire_date" name="hire_date"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="department">Department</label>
                    <select id="department" name="department" required
                            class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30 appearance-none">
                        <option value="">Select Department</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Physics">Physics</option>
                        <option value="English">English</option>
                        <option value="Business Administration">Business Administration</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="title">Job Title</label>
                    <input type="text" id="title" name="title" placeholder="Professor, Assistant Professor, etc."
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="office_location">Office Location</label>
                    <input type="text" id="office_location" name="office_location" placeholder="Building B, Room 402"
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-secondary/30">
                </div>
            </div>
        </section>

        <!-- Submit -->
        <div class="lg:col-span-2 flex justify-end gap-4 mt-4">
            <a href="index.php" class="px-8 py-4 rounded-2xl text-on-surface-variant font-bold hover:text-on-surface transition-all">Cancel</a>
            <button type="submit" class="px-10 py-4 bg-surface-container rounded-2xl font-bold text-secondary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] active:scale-95 transition-all">
                Add Faculty Member
            </button>
        </div>
    </form>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
