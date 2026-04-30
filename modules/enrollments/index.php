<?php
/**
 * Enrollment Listing
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin', 'faculty']);

$search = $_GET['search'] ?? '';

try {
    $query = "SELECT e.*, s.student_number, u_s.first_name as s_first, u_s.last_name as s_last, 
                     sec.section_code, sec.semester, sec.academic_year, c.course_code, c.course_name
              FROM enrollments e
              JOIN students s ON e.student_id = s.student_id
              JOIN users u_s ON s.user_id = u_s.user_id
              JOIN sections sec ON e.section_id = sec.section_id
              JOIN courses c ON sec.course_id = c.course_id";
    
    if ($search) {
        $query .= " WHERE u_s.first_name LIKE :search 
                    OR u_s.last_name LIKE :search 
                    OR s.student_number LIKE :search 
                    OR c.course_code LIKE :search";
    }
    
    $query .= " ORDER BY e.enrollment_date DESC";
    
    $stmt = $pdo->prepare($query);
    if ($search) {
        $stmt->bindValue(':search', "%$search%");
    }
    $stmt->execute();
    $enrollments = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching enrollments: " . $e->getMessage());
}

$pageTitle = "Enrollments";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Student Enrollments</h1>
            <p class="text-on-surface-variant mt-1">Manage student registration and course access.</p>
        </div>
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="create.php" class="flex items-center gap-2 bg-surface-container rounded-2xl px-6 py-3 font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] transition-all">
            <span class="material-symbols-outlined">person_add_alt</span>
            New Enrollment
        </a>
        <?php endif; ?>
    </header>

    <!-- Search Bar -->
    <section class="mb-10">
        <form action="index.php" method="GET" class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">search</span>
            <input type="text" name="search" placeholder="Search by student, ID, or course..." value="<?php echo htmlspecialchars($search); ?>"
                   class="w-full bg-surface-container border-none rounded-2xl py-4 pl-12 pr-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] focus:ring-1 focus:ring-primary/30 outline-none transition-all">
        </form>
    </section>

    <!-- Enrollment Table -->
    <div class="bg-surface-container rounded-[32px] overflow-hidden shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant">
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Student</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Course & Section</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Date</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                <?php if (empty($enrollments)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-on-surface-variant italic">No enrollments found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($enrollments as $e): ?>
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-on-surface font-bold"><?php echo htmlspecialchars($e['s_first'] . ' ' . $e['s_last']); ?></span>
                                    <span class="text-xs text-on-surface-variant"><?php echo htmlspecialchars($e['student_number']); ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-on-surface font-medium"><?php echo htmlspecialchars($e['course_code'] . ' (' . $e['section_code'] . ')'); ?></span>
                                    <span class="text-[10px] text-on-surface-variant uppercase font-bold"><?php echo htmlspecialchars($e['semester'] . ' ' . $e['academic_year']); ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-on-surface-variant text-sm"><?php echo date('M d, Y', strtotime($e['enrollment_date'])); ?></td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold capitalize 
                                    <?php 
                                        echo $e['status'] === 'enrolled' ? 'bg-success/10 text-success' : 
                                            ($e['status'] === 'completed' ? 'bg-primary/10 text-primary' : 'bg-error/10 text-error'); 
                                    ?>">
                                    <?php echo htmlspecialchars($e['status']); ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <?php if ($_SESSION['role'] === 'admin' && $e['status'] === 'enrolled'): ?>
                                    <button onclick="confirmDrop(<?php echo $e['enrollment_id']; ?>)" class="w-10 h-10 rounded-xl bg-surface-container shadow-[2px_2px_4px_#dbe4eb,-2px_-2px_4px_#ffffff] flex items-center justify-center text-error hover:scale-110 transition-transform" title="Drop Course">
                                        <span class="material-symbols-outlined text-xl">person_remove</span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function confirmDrop(id) {
    if (confirm('Are you sure you want to drop this student from the section?')) {
        window.location.href = 'drop.php?id=' + id;
    }
}
</script>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
