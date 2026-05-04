<?php
/**
 * Course Listing
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$search = $_GET['search'] ?? '';

try {
    $query = "SELECT * FROM courses";
    if ($search) {
        $query .= " WHERE course_name LIKE :search 
                    OR course_code LIKE :search 
                    OR department LIKE :search";
    }
    $query .= " ORDER BY course_code ASC";
    
    $stmt = $pdo->prepare($query);
    if ($search) {
        $stmt->bindValue(':search', "%$search%");
    }
    $stmt->execute();
    $courses = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching courses: " . $e->getMessage());
}

$pageTitle = "Course Catalog";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Course Catalog</h1>
            <p class="text-on-surface-variant mt-1">Manage institutional course offerings and credits.</p>
        </div>
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="create.php" class="flex items-center gap-2 bg-surface-container rounded-2xl px-6 py-3 font-bold text-tertiary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] transition-all">
            <span class="material-symbols-outlined">library_add</span>
            Add New Course
        </a>
        <?php endif; ?>
    </header>

    <!-- Search Bar -->
    <section class="mb-10">
        <form action="index.php" method="GET" class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">search</span>
            <input type="text" name="search" placeholder="Search courses..." value="<?php echo htmlspecialchars($search); ?>"
                   class="w-full bg-surface-container border-none rounded-2xl py-4 pl-12 pr-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] focus:ring-1 focus:ring-primary/30 outline-none transition-all">
        </form>
    </section>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        <?php if (empty($courses)): ?>
            <div class="col-span-full py-20 text-center bg-surface-container rounded-[32px] shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff]">
                <p class="text-on-surface-variant italic">No courses found in the catalog.</p>
            </div>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff] flex flex-col hover:scale-[1.02] transition-transform">
                    <div class="flex justify-between items-start mb-6">
                        <div class="bg-tertiary-fixed text-tertiary px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
                            <?php echo htmlspecialchars($course['course_code']); ?>
                        </div>
                        <div class="flex gap-2">
                            <a href="view.php?id=<?php echo $course['course_id']; ?>" class="text-on-surface-variant hover:text-primary transition-colors" title="View Details">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </a>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="edit.php?id=<?php echo $course['course_id']; ?>" class="text-on-surface-variant hover:text-primary transition-colors" title="Edit Course">
                                <span class="material-symbols-outlined text-xl">edit_note</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-on-surface mb-2 line-clamp-1">
                        <a href="view.php?id=<?php echo $course['course_id']; ?>" class="hover:text-primary transition-colors">
                            <?php echo htmlspecialchars($course['course_name']); ?>
                        </a>
                    </h3>
                    <p class="text-on-surface-variant text-sm mb-6 line-clamp-2"><?php echo htmlspecialchars($course['description'] ?: 'No description available.'); ?></p>
                    
                    <div class="mt-auto flex items-center justify-between pt-6 border-t border-outline-variant/30">
                        <div class="flex flex-col">
                            <span class="text-xs text-on-surface-variant uppercase font-bold tracking-tighter">Department</span>
                            <span class="text-sm font-medium text-on-surface"><?php echo htmlspecialchars($course['department']); ?></span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-xs text-on-surface-variant uppercase font-bold tracking-tighter">Credits</span>
                            <span class="text-sm font-bold text-tertiary"><?php echo htmlspecialchars($course['credits']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
