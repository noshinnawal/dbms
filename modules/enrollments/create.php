<?php
/**
 * New Enrollment
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$error = '';
$success = '';

// Fetch active students and available sections
try {
    $students = $pdo->query("SELECT s.student_id, s.student_number, u.first_name, u.last_name 
                             FROM students s 
                             JOIN users u ON s.user_id = u.user_id 
                             WHERE s.status = 'active' 
                             ORDER BY u.first_name")->fetchAll();
                             
    $sections = $pdo->query("SELECT s.section_id, s.section_code, s.semester, s.academic_year, c.course_code, c.course_name,
                                    (SELECT COUNT(*) FROM enrollments e WHERE e.section_id = s.section_id AND e.status = 'enrolled') as current_enrollment,
                                    s.max_capacity
                             FROM sections s
                             JOIN courses c ON s.course_id = c.course_id
                             ORDER BY s.academic_year DESC, s.semester DESC, c.course_code ASC")->fetchAll();
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)$_POST['student_id'];
    $section_id = (int)$_POST['section_id'];

    try {
        // Check capacity
        $stmt = $pdo->prepare("SELECT max_capacity, (SELECT COUNT(*) FROM enrollments WHERE section_id = ? AND status = 'enrolled') as current FROM sections WHERE section_id = ?");
        $stmt->execute([$section_id, $section_id]);
        $cap = $stmt->fetch();
        
        if ($cap['current'] >= $cap['max_capacity']) {
            $error = "Error: This section is already at maximum capacity (" . $cap['max_capacity'] . ").";
        } else {
            $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, section_id) VALUES (?, ?)");
            $stmt->execute([$student_id, $section_id]);
            $success = "Student enrolled successfully!";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Error: Student is already enrolled in this section.";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

$pageTitle = "New Enrollment";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="mb-10">
        <h1 class="text-3xl font-bold text-on-surface">New Enrollment</h1>
        <p class="text-on-surface-variant mt-1">Register a student for a specific course section.</p>
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

    <form action="create.php" method="POST" class="max-w-3xl bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
        <div class="flex flex-col gap-8">
            <!-- Student Selection -->
            <div>
                <label class="text-sm font-bold text-on-surface-variant block mb-3 ml-2" for="student_id">Select Student</label>
                <div class="relative">
                    <select id="student_id" name="student_id" required
                            class="w-full bg-surface-container border-none rounded-2xl py-4 px-6 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 appearance-none">
                        <option value="">Choose a student...</option>
                        <?php foreach ($students as $s): ?>
                            <option value="<?php echo $s['student_id']; ?>">
                                <?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['student_number'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant pointer-events-none">expand_more</span>
                </div>
            </div>

            <!-- Section Selection -->
            <div>
                <label class="text-sm font-bold text-on-surface-variant block mb-3 ml-2" for="section_id">Select Section</label>
                <div class="relative">
                    <select id="section_id" name="section_id" required
                            class="w-full bg-surface-container border-none rounded-2xl py-4 px-6 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 appearance-none">
                        <option value="">Choose a section...</option>
                        <?php foreach ($sections as $sec): ?>
                            <?php $full = $sec['current_enrollment'] >= $sec['max_capacity']; ?>
                            <option value="<?php echo $sec['section_id']; ?>" <?php echo $full ? 'disabled' : ''; ?>>
                                <?php echo htmlspecialchars($sec['course_code'] . ' - ' . $sec['section_code'] . ' (' . $sec['semester'] . ' ' . $sec['academic_year'] . ')'); ?>
                                [<?php echo $sec['current_enrollment']; ?>/<?php echo $sec['max_capacity']; ?>] <?php echo $full ? '(FULL)' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant pointer-events-none">expand_more</span>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4 mt-4">
                <a href="index.php" class="px-8 py-4 rounded-2xl text-on-surface-variant font-bold hover:text-on-surface transition-all">Cancel</a>
                <button type="submit" class="px-10 py-4 bg-surface-container rounded-2xl font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] active:scale-95 transition-all">
                    Complete Enrollment
                </button>
            </div>
        </div>
    </form>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
