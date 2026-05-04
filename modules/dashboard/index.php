<?php
/**
 * Dashboard Index
 * Displays statistics and overview for the logged-in user.
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

// Fetch Statistics
$stats = [
    'students' => 0,
    'courses' => 0
];

try {
    $stats['students'] = $pdo->query("SELECT COUNT(*) FROM students WHERE status = 'active'")->fetchColumn();
    $stats['courses'] = $pdo->query("SELECT COUNT(*) FROM courses WHERE is_active = 1")->fetchColumn();
} catch (PDOException $e) {
    // Tables might not exist yet if the user hasn't imported the SQL
}

$pageTitle = "Dashboard";
$role = $_SESSION['role'] ?? 'student';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <!-- Header -->
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Welcome Back, <?php echo explode(' ', $_SESSION['full_name'])[0]; ?>!</h1>
            <p class="text-on-surface-variant mt-1">Here's what's happening in the system today.</p>
        </div>
    </header>

    <?php if ($role === 'admin'): ?>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
        <!-- Stat Card: Students -->
        <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <div class="w-12 h-12 rounded-2xl bg-primary-container flex items-center justify-center text-primary mb-6">
                <span class="material-symbols-outlined">group</span>
            </div>
            <h3 class="text-on-surface-variant text-sm font-medium mb-1">Total Students</h3>
            <div class="text-3xl font-bold text-on-surface"><?php echo number_format($stats['students']); ?></div>
        </div>
        <!-- Stat Card: Courses -->
        <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <div class="w-12 h-12 rounded-2xl bg-tertiary-fixed flex items-center justify-center text-tertiary mb-6">
                <span class="material-symbols-outlined">menu_book</span>
            </div>
            <h3 class="text-on-surface-variant text-sm font-medium mb-1">Active Courses</h3>
            <div class="text-3xl font-bold text-on-surface"><?php echo number_format($stats['courses']); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 gap-8">
        <?php if ($role === 'admin'): ?>
        <!-- Quick Links -->
        <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="../students/create.php" class="w-full py-4 rounded-2xl bg-surface-container shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] text-on-surface font-medium flex items-center justify-center gap-2 hover:text-primary transition-all">
                    <span class="material-symbols-outlined">person_add</span>
                    Add Student
                </a>
                <a href="../courses/create.php" class="w-full py-4 rounded-2xl bg-surface-container shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] text-on-surface font-medium flex items-center justify-center gap-2 hover:text-primary transition-all">
                    <span class="material-symbols-outlined">library_add</span>
                    New Course
                </a>
                <a href="../sections/create.php" class="w-full py-4 rounded-2xl bg-surface-container shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] text-on-surface font-medium flex items-center justify-center gap-2 hover:text-primary transition-all">
                    <span class="material-symbols-outlined">calendar_add_on</span>
                    Schedule Section
                </a>
            </div>
        </div>
        <?php else: ?>
        <!-- Student Dashboard Placeholder -->
        <div class="bg-surface-container rounded-[32px] p-10 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff] flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 rounded-full bg-surface-container shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary text-4xl">account_balance</span>
            </div>
            <h2 class="text-2xl font-bold text-on-surface">Student Portal Active</h2>
            <p class="text-on-surface-variant mt-2 max-w-md">Your academic records and courses will appear here shortly. Please use the sidebar to navigate your portal.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php 
echo "</div>"; // Close the sidebar's content div
require_once '../../includes/footer.php'; 
?>
