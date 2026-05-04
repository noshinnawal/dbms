<?php
/**
 * Section Listing
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$semester_filter = $_GET['semester'] ?? '';
$year_filter = $_GET['year'] ?? '';

try {
    $query = "SELECT s.*, c.course_name, c.course_code 
              FROM sections s
              JOIN courses c ON s.course_id = c.course_id";
    
    $where_clauses = [];
    $params = [];

    if ($semester_filter) {
        $where_clauses[] = "s.semester = :semester";
        $params[':semester'] = $semester_filter;
    }
    if ($year_filter) {
        $where_clauses[] = "s.academic_year = :year";
        $params[':year'] = $year_filter;
    }

    if (!empty($where_clauses)) {
        $query .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $query .= " ORDER BY s.academic_year DESC, s.semester DESC, c.course_code ASC";
    
    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->execute();
    $sections = $stmt->fetchAll();

    // Get unique semesters and years for filters
    $semesters = $pdo->query("SELECT DISTINCT semester FROM sections ORDER BY semester")->fetchAll(PDO::FETCH_COLUMN);
    $years = $pdo->query("SELECT DISTINCT academic_year FROM sections ORDER BY academic_year DESC")->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    die("Error fetching sections: " . $e->getMessage());
}

$pageTitle = "Manage Sections";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Manage Sections</h1>
            <p class="text-on-surface-variant mt-1">Schedule courses and manage rosters.</p>
        </div>
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="create.php" class="flex items-center gap-2 bg-surface-container rounded-2xl px-6 py-3 font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] transition-all">
            <span class="material-symbols-outlined">add_task</span>
            Create New Section
        </a>
        <?php endif; ?>
    </header>

    <!-- Filters -->
    <section class="mb-10 flex gap-4">
        <form action="index.php" method="GET" class="flex gap-4">
            <select name="semester" onchange="this.form.submit()" class="bg-surface-container border-none rounded-2xl py-3 px-6 text-on-surface shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 appearance-none">
                <option value="">All Semesters</option>
                <?php foreach ($semesters as $sem): ?>
                    <option value="<?php echo htmlspecialchars($sem); ?>" <?php echo $semester_filter == $sem ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($sem); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="year" onchange="this.form.submit()" class="bg-surface-container border-none rounded-2xl py-3 px-6 text-on-surface shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 appearance-none">
                <option value="">All Years</option>
                <?php foreach ($years as $yr): ?>
                    <option value="<?php echo htmlspecialchars($yr); ?>" <?php echo $year_filter == $yr ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($yr); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <?php if ($semester_filter || $year_filter): ?>
                <a href="index.php" class="py-3 px-6 text-on-surface-variant hover:text-primary transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">close</span>
                    Clear Filters
                </a>
            <?php endif; ?>
        </form>
    </section>

    <!-- Sections Table -->
    <div class="bg-surface-container rounded-[32px] overflow-hidden shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant">
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Course</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Section</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Schedule</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Room</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                <?php if (empty($sections)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-on-surface-variant italic">No sections scheduled for the selected filters.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($sections as $section): ?>
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-on-surface font-bold"><?php echo htmlspecialchars($section['course_code']); ?></span>
                                    <span class="text-xs text-on-surface-variant"><?php echo htmlspecialchars($section['course_name']); ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-on-surface font-medium"><?php echo htmlspecialchars($section['section_code']); ?></span>
                                    <span class="text-[10px] text-on-surface-variant uppercase font-bold"><?php echo htmlspecialchars($section['semester'] . ' ' . $section['academic_year']); ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-primary"><?php echo htmlspecialchars($section['schedule_day']); ?></span>
                                    <span class="text-xs text-on-surface-variant">
                                        <?php echo date('h:i A', strtotime($section['schedule_time_start'])); ?> - 
                                        <?php echo date('h:i A', strtotime($section['schedule_time_end'])); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-on-surface font-medium"><?php echo htmlspecialchars($section['room']); ?></td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="edit.php?id=<?php echo $section['section_id']; ?>" class="w-10 h-10 rounded-xl bg-surface-container shadow-[2px_2px_4px_#dbe4eb,-2px_-2px_4px_#ffffff] flex items-center justify-center text-on-surface-variant hover:scale-110 transition-transform" title="Edit Section">
                                        <span class="material-symbols-outlined text-xl">settings</span>
                                    </a>
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

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
