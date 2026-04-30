<?php
/**
 * Section Roster
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$section_id = $_GET['id'] ?? null;

if (!$section_id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch section details
    $stmt = $pdo->prepare("SELECT s.*, c.course_name, c.course_code, u.first_name as f_first, u.last_name as f_last 
                           FROM sections s
                           JOIN courses c ON s.course_id = c.course_id
                           JOIN faculty f ON s.faculty_id = f.faculty_id
                           JOIN users u ON f.user_id = u.user_id
                           WHERE s.section_id = ?");
    $stmt->execute([$section_id]);
    $section = $stmt->fetch();

    if (!$section) {
        die("Section not found.");
    }

    // Security: Faculty can only see their own rosters
    if ($_SESSION['role'] === 'faculty') {
        $stmt_f = $pdo->prepare("SELECT faculty_id FROM faculty WHERE user_id = ?");
        $stmt_f->execute([$_SESSION['user_id']]);
        $f_id = $stmt_f->fetchColumn();
        if ($section['faculty_id'] != $f_id) {
            header("Location: index.php?error=unauthorized");
            exit;
        }
    }

    // Fetch roster
    $stmt = $pdo->prepare("SELECT e.*, s.student_number, u.first_name, u.last_name, u.email 
                           FROM enrollments e
                           JOIN students s ON e.student_id = s.student_id
                           JOIN users u ON s.user_id = u.user_id
                           WHERE e.section_id = ? AND e.status = 'enrolled'
                           ORDER BY u.first_name ASC");
    $stmt->execute([$section_id]);
    $roster = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Error fetching roster: " . $e->getMessage());
}

$pageTitle = "Section Roster";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="flex justify-between items-start mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="index.php" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <span class="bg-primary-container text-primary px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
                    <?php echo htmlspecialchars($section['course_code'] . ' - ' . $section['section_code']); ?>
                </span>
            </div>
            <h1 class="text-3xl font-bold text-on-surface"><?php echo htmlspecialchars($section['course_name']); ?></h1>
            <p class="text-on-surface-variant mt-1">
                Instructor: <span class="font-bold"><?php echo htmlspecialchars($section['f_first'] . ' ' . $section['f_last']); ?></span> | 
                Term: <span class="font-bold"><?php echo htmlspecialchars($section['semester'] . ' ' . $section['academic_year']); ?></span>
            </p>
        </div>
        <div class="bg-surface-container rounded-2xl p-4 shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] text-center min-w-[120px]">
            <span class="text-xs text-on-surface-variant uppercase font-bold block mb-1">Enrolled</span>
            <span class="text-2xl font-bold text-primary"><?php echo count($roster); ?></span>
            <span class="text-xs text-on-surface-variant font-medium">/ <?php echo $section['max_capacity']; ?></span>
        </div>
    </header>

    <!-- Roster Table -->
    <div class="bg-surface-container rounded-[32px] overflow-hidden shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant">
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Student ID</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Name</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Email</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                <?php if (empty($roster)): ?>
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-on-surface-variant italic">No students are currently enrolled in this section.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($roster as $student): ?>
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-8 py-6 text-on-surface font-medium"><?php echo htmlspecialchars($student['student_number']); ?></td>
                            <td class="px-8 py-6 text-on-surface font-bold"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                            <td class="px-8 py-6 text-on-surface-variant"><?php echo htmlspecialchars($student['email']); ?></td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="../students/view.php?id=<?php echo $student['student_id']; ?>" class="w-10 h-10 rounded-xl bg-surface-container shadow-[2px_2px_4px_#dbe4eb,-2px_-2px_4px_#ffffff] flex items-center justify-center text-primary hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-xl">account_circle</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
