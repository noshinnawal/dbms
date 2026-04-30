<?php
/**
 * Create Course
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['course_code']);
    $name = trim($_POST['course_name']);
    $desc = trim($_POST['description']);
    $credits = (int)$_POST['credits'];
    $dept = trim($_POST['department']);

    try {
        $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name, description, credits, department) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$code, $name, $desc, $credits, $dept]);
        $success = "Course created successfully!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Error: Course code '$code' already exists.";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

$pageTitle = "Add Course";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="mb-10">
        <h1 class="text-3xl font-bold text-on-surface">Add New Course</h1>
        <p class="text-on-surface-variant mt-1">Define a new course offering for the academic catalog.</p>
    </header>

    <?php if ($error): ?>
        <div class="mb-8 p-4 bg-error-container text-on-error-container rounded-2xl text-sm font-medium">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="mb-8 p-4 bg-tertiary-container text-tertiary rounded-2xl text-sm font-medium">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form action="create.php" method="POST" class="max-w-3xl bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
        <div class="flex flex-col gap-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="course_code">Course Code</label>
                    <input type="text" id="course_code" name="course_code" required placeholder="CS101"
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-tertiary/30 uppercase">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="course_name">Course Name</label>
                    <input type="text" id="course_name" name="course_name" required placeholder="Introduction to Computer Science"
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-tertiary/30">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="department">Department</label>
                    <select id="department" name="department" required
                            class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-tertiary/30 appearance-none">
                        <option value="">Select Department</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Physics">Physics</option>
                        <option value="English">English</option>
                        <option value="Business Administration">Business Administration</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="credits">Credit Hours</label>
                    <input type="number" id="credits" name="credits" required min="1" max="10" value="3"
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-tertiary/30">
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="description">Course Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-tertiary/30 resize-none"></textarea>
            </div>

            <div class="flex justify-end gap-4 mt-4">
                <a href="index.php" class="px-8 py-4 rounded-2xl text-on-surface-variant font-bold hover:text-on-surface transition-all">Cancel</a>
                <button type="submit" class="px-10 py-4 bg-surface-container rounded-2xl font-bold text-tertiary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] active:scale-95 transition-all">
                    Create Course
                </button>
            </div>
        </div>
    </form>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
