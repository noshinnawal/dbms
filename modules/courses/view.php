<?php
/**
 * Course Detail View
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$course_id = $_GET['id'] ?? null;
if (!$course_id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch course details
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch();

    if (!$course) {
        header("Location: index.php");
        exit;
    }

    // Fetch associated sections
    $stmt = $pdo->prepare("SELECT * FROM sections WHERE course_id = ? ORDER BY academic_year DESC, semester DESC, section_code ASC");
    $stmt->execute([$course_id]);
    $sections = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$pageTitle = "Course Details: " . $course['course_code'];
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <!-- Header -->
    <header class="flex justify-between items-start mb-10">
        <div class="flex items-center gap-6">
            <div class="bg-tertiary-fixed text-tertiary px-4 py-2 rounded-2xl text-lg font-bold shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff]">
                <?php echo htmlspecialchars($course['course_code']); ?>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-on-surface"><?php echo htmlspecialchars($course['course_name']); ?></h1>
                <p class="text-on-surface-variant mt-1"><?php echo htmlspecialchars($course['department']); ?> Department</p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="edit.php?id=<?php echo $course_id; ?>" class="flex items-center gap-2 bg-surface-container rounded-2xl px-6 py-3 font-bold text-on-surface-variant shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] transition-all">
                <span class="material-symbols-outlined">edit</span>
                Edit Info
            </a>
            <a href="index.php" class="flex items-center gap-2 bg-surface-container rounded-2xl px-6 py-3 font-bold text-on-surface-variant shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
                Catalog
            </a>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Description Card -->
        <div class="lg:col-span-2 bg-surface-container rounded-[40px] p-10 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">description</span>
                Course Description
            </h2>
            <p class="text-on-surface-variant leading-relaxed text-lg">
                <?php echo nl2br(htmlspecialchars($course['description'] ?: 'No description provided for this course.')); ?>
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="bg-surface-container rounded-[40px] p-10 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] flex flex-col gap-8">
            <div>
                <span class="text-xs text-on-surface-variant uppercase font-black tracking-widest block mb-2">Credit Hours</span>
                <span class="text-4xl font-bold text-tertiary"><?php echo htmlspecialchars($course['credits']); ?> Credits</span>
            </div>
            <div>
                <span class="text-xs text-on-surface-variant uppercase font-black tracking-widest block mb-2">Academic Status</span>
                <span class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full <?php echo $course['is_active'] ? 'bg-success' : 'bg-outline'; ?>"></span>
                    <span class="font-bold text-on-surface"><?php echo $course['is_active'] ? 'Currently Offered' : 'Inactive'; ?></span>
                </span>
            </div>
            <div class="mt-auto">
                <span class="text-xs text-on-surface-variant uppercase font-black tracking-widest block mb-2">Last Updated</span>
                <span class="text-sm font-medium text-on-surface"><?php echo date('F d, Y', strtotime($course['created_at'])); ?></span>
            </div>
        </div>
    </div>

    <!-- Sections List -->
    <section>
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3">
                <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
                Class Schedule & Sections
            </h2>
            <a href="../sections/create.php?course_id=<?php echo $course_id; ?>" class="flex items-center gap-2 bg-surface-container rounded-2xl px-6 py-3 font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] transition-all">
                <span class="material-symbols-outlined">add</span>
                Add Section
            </a>
        </div>

        <div class="bg-surface-container rounded-[40px] overflow-hidden shadow-[20px_20px_40px_#dbe4eb,-20px_-20px_40px_#ffffff]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/30">
                        <th class="px-10 py-6 text-xs font-black text-on-surface-variant uppercase tracking-widest">Section Code</th>
                        <th class="px-10 py-6 text-xs font-black text-on-surface-variant uppercase tracking-widest">Term</th>
                        <th class="px-10 py-6 text-xs font-black text-on-surface-variant uppercase tracking-widest">Schedule & Timing</th>
                        <th class="px-10 py-6 text-xs font-black text-on-surface-variant uppercase tracking-widest">Room</th>
                        <th class="px-10 py-6 text-xs font-black text-on-surface-variant uppercase tracking-widest">Capacity</th>
                        <th class="px-10 py-6 text-xs font-black text-on-surface-variant uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    <?php if (empty($sections)): ?>
                        <tr>
                            <td colspan="6" class="px-10 py-20 text-center">
                                <div class="flex flex-col items-center gap-4 text-on-surface-variant italic">
                                    <span class="material-symbols-outlined text-5xl opacity-20">event_busy</span>
                                    No sections have been scheduled for this course yet.
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sections as $section): ?>
                            <tr class="hover:bg-primary/5 transition-colors group">
                                <td class="px-10 py-6">
                                    <span class="font-bold text-on-surface text-lg"><?php echo htmlspecialchars($section['section_code']); ?></span>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-on-surface font-medium"><?php echo htmlspecialchars($section['semester']); ?></span>
                                        <span class="text-xs text-on-surface-variant font-bold"><?php echo htmlspecialchars($section['academic_year']); ?></span>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                                            <span class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($section['schedule_day']); ?></span>
                                        </div>
                                        <span class="text-xs text-on-surface-variant">
                                            <?php echo date('h:i A', strtotime($section['schedule_time_start'])); ?> - 
                                            <?php echo date('h:i A', strtotime($section['schedule_time_end'])); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-on-surface font-medium"><?php echo htmlspecialchars($section['room']); ?></td>
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm text-on-surface-variant">groups</span>
                                        <span class="text-on-surface"><?php echo htmlspecialchars($section['max_capacity']); ?></span>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="../sections/edit.php?id=<?php echo $section['section_id']; ?>" 
                                           class="w-10 h-10 rounded-xl bg-surface-container shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] flex items-center justify-center text-on-surface-variant hover:text-primary hover:scale-110 transition-all" 
                                           title="Edit Section Settings">
                                            <span class="material-symbols-outlined text-xl">settings</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
