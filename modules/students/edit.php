<?php
/**
 * Edit Student
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$student_id = $_GET['id'] ?? null;
if (!$student_id) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Fetch current data
try {
    $stmt = $pdo->prepare("SELECT s.*, u.email, u.first_name, u.last_name, u.username 
                          FROM students s 
                          JOIN users u ON s.user_id = u.user_id 
                          WHERE s.student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    if (!$student) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching student: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    
    $student_number = trim($_POST['student_number']);
    $dob = $_POST['dob'];
    $phone = trim($_POST['phone']);
    $present_address = trim($_POST['present_address']);
    $permanent_address = trim($_POST['permanent_address']);
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);
    $father_occupation = trim($_POST['father_occupation']);
    $mother_occupation = trim($_POST['mother_occupation']);
    $status = $_POST['status'];
    $enrollment_date = $_POST['enrollment_date'];
    $new_password = trim($_POST['new_password'] ?? '');

    try {
        $pdo->beginTransaction();

        // 1. Update users table (basic info)
        $stmt = $pdo->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ? WHERE user_id = ?");
        $stmt->execute([$email, $first_name, $last_name, $student['user_id']]);

        // 1b. Update password if provided
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->execute([$hashed_password, $student['user_id']]);
        }

        // 2. Update students table
        $stmt = $pdo->prepare("UPDATE students SET student_number = ?, father_name = ?, mother_name = ?, father_occupation = ?, mother_occupation = ?, date_of_birth = ?, phone = ?, present_address = ?, permanent_address = ?, status = ?, enrollment_date = ? WHERE student_id = ?");
        $stmt->execute([$student_number, $father_name, $mother_name, $father_occupation, $mother_occupation, $dob, $phone, $present_address, $permanent_address, $status, $enrollment_date, $student_id]);

        $pdo->commit();
        $success = "Student updated successfully!";
        
        // Refresh data
        $student['email'] = $email;
        $student['first_name'] = $first_name;
        $student['last_name'] = $last_name;
        $student['student_number'] = $student_number;
        $student['father_name'] = $father_name;
        $student['mother_name'] = $mother_name;
        $student['father_occupation'] = $father_occupation;
        $student['mother_occupation'] = $mother_occupation;
        $student['date_of_birth'] = $dob;
        $student['phone'] = $phone;
        $student['present_address'] = $present_address;
        $student['permanent_address'] = $permanent_address;
        $student['status'] = $status;
        $student['enrollment_date'] = $enrollment_date;

    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == 23000) {
            $error = "Error: Email or Student Number already exists.";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

$pageTitle = "Edit Student: " . $student['first_name'];
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="mb-10">
        <div class="flex items-center gap-3 mb-1">
            <a href="view.php?id=<?php echo $student_id; ?>" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-3xl font-bold text-on-surface">Edit Student</h1>
        </div>
        <p class="text-on-surface-variant ml-9">Modify profile information for <?php echo htmlspecialchars($student['first_name']); ?>.</p>
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

    <form action="edit.php?id=<?php echo $student_id; ?>" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Account Information -->
        <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">account_circle</span>
                Account Information
            </h2>
            <div class="flex flex-col gap-6">
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2">Username (System)</label>
                    <input type="text" value="<?php echo htmlspecialchars($student['username']); ?>" disabled
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface-variant shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] cursor-not-allowed">
                    <p class="text-[10px] text-on-surface-variant mt-2 ml-2 italic">Username cannot be changed after creation.</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="new_password">Reset Password (Optional)</label>
                    <div class="relative flex items-center">
                        <input type="text" id="new_password" name="new_password" placeholder="Enter new password to reset"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                        <button type="button" onclick="generatePassword()" class="absolute right-2 p-2 text-primary hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-xl">autorenew</span>
                        </button>
                    </div>
                    <p class="text-[10px] text-on-surface-variant mt-2 ml-2 italic">Leave blank to keep current password.</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="email">Email Address</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($student['email']); ?>"
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required value="<?php echo htmlspecialchars($student['first_name']); ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required value="<?php echo htmlspecialchars($student['last_name']); ?>"
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
                        <input type="text" id="student_number" name="student_number" required value="<?php echo htmlspecialchars($student['student_number']); ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="status">Status</label>
                        <select id="status" name="status" class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 appearance-none">
                            <option value="active" <?php echo $student['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $student['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="graduated" <?php echo $student['status'] === 'graduated' ? 'selected' : ''; ?>>Graduated</option>
                            <option value="dropped" <?php echo $student['status'] === 'dropped' ? 'selected' : ''; ?>>Dropped</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="enrollment_date">Enrollment Date</label>
                        <input type="date" id="enrollment_date" name="enrollment_date" value="<?php echo $student['enrollment_date']; ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" value="<?php echo $student['date_of_birth']; ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>"
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                </div>
            </div>
        </section>

        <!-- Parental Information -->
        <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">family_restroom</span>
                Parental Information
            </h2>
            <div class="flex flex-col gap-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="father_name">Father's Name</label>
                        <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($student['father_name'] ?? ''); ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="father_occupation">Father's Occupation</label>
                        <input type="text" id="father_occupation" name="father_occupation" value="<?php echo htmlspecialchars($student['father_occupation'] ?? ''); ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="mother_name">Mother's Name</label>
                        <input type="text" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($student['mother_name'] ?? ''); ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="mother_occupation">Mother's Occupation</label>
                        <input type="text" id="mother_occupation" name="mother_occupation" value="<?php echo htmlspecialchars($student['mother_occupation'] ?? ''); ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>
            </div>
        </section>

        <!-- Residential Details -->
        <section class="lg:col-span-2 bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">home_pin</span>
                Residential Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="present_address">Present Address</label>
                    <textarea id="present_address" name="present_address" rows="3"
                              class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 resize-none"><?php echo htmlspecialchars($student['present_address'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="permanent_address">Permanent Address</label>
                    <textarea id="permanent_address" name="permanent_address" rows="3"
                              class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 resize-none"><?php echo htmlspecialchars($student['permanent_address'] ?? ''); ?></textarea>
                </div>
            </div>
        </section>

        <!-- Submit -->
        <div class="lg:col-span-2 flex justify-end gap-4 mt-4">
            <a href="view.php?id=<?php echo $student_id; ?>" class="px-8 py-4 rounded-2xl text-on-surface-variant font-bold hover:text-on-surface transition-all">Cancel</a>
            <button type="submit" class="px-10 py-4 bg-surface-container rounded-2xl font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] active:scale-95 transition-all">
                Update Student Record
            </button>
        </div>
    </form>
</main>

<script>
function generatePassword() {
    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let pass = "";
    for (let i = 0; i < 12; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('new_password').value = pass;
}
</script>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
