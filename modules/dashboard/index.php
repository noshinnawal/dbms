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
    'faculty' => 0,
    'courses' => 0,
    'attendance_today' => 0
];

try {
    $stats['students'] = $pdo->query("SELECT COUNT(*) FROM students WHERE status = 'active'")->fetchColumn();
    $stats['faculty'] = $pdo->query("SELECT COUNT(*) FROM faculty WHERE status = 'active'")->fetchColumn();
    $stats['courses'] = $pdo->query("SELECT COUNT(*) FROM courses WHERE is_active = 1")->fetchColumn();
    $stats['attendance_today'] = $pdo->query("SELECT COUNT(*) FROM attendance WHERE attendance_date = CURDATE() AND status = 'present'")->fetchColumn();
} catch (PDOException $e) {
    // Tables might not exist yet if the user hasn't imported the SQL
}

$pageTitle = "Dashboard";
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
        <div class="flex gap-4">
            <button class="w-12 h-12 rounded-2xl bg-surface-container shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] flex items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="w-12 h-12 rounded-2xl bg-surface-container shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] flex items-center justify-center text-on-surface-variant">
                <span class="material-symbols-outlined">settings</span>
            </button>
        </div>
    </header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        <!-- Stat Card: Students -->
        <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <div class="w-12 h-12 rounded-2xl bg-primary-container flex items-center justify-center text-primary mb-6">
                <span class="material-symbols-outlined">group</span>
            </div>
            <h3 class="text-on-surface-variant text-sm font-medium mb-1">Total Students</h3>
            <div class="text-3xl font-bold text-on-surface"><?php echo number_format($stats['students']); ?></div>
        </div>

        <!-- Stat Card: Faculty -->
        <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <div class="w-12 h-12 rounded-2xl bg-secondary-container flex items-center justify-center text-secondary mb-6">
                <span class="material-symbols-outlined">person_pin</span>
            </div>
            <h3 class="text-on-surface-variant text-sm font-medium mb-1">Total Faculty</h3>
            <div class="text-3xl font-bold text-on-surface"><?php echo number_format($stats['faculty']); ?></div>
        </div>

        <!-- Stat Card: Courses -->
        <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <div class="w-12 h-12 rounded-2xl bg-tertiary-fixed flex items-center justify-center text-tertiary mb-6">
                <span class="material-symbols-outlined">menu_book</span>
            </div>
            <h3 class="text-on-surface-variant text-sm font-medium mb-1">Active Courses</h3>
            <div class="text-3xl font-bold text-on-surface"><?php echo number_format($stats['courses']); ?></div>
        </div>

        <!-- Stat Card: Attendance -->
        <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <div class="w-12 h-12 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary mb-6">
                <span class="material-symbols-outlined">event_available</span>
            </div>
            <h3 class="text-on-surface-variant text-sm font-medium mb-1">Attendance Today</h3>
            <div class="text-3xl font-bold text-on-surface"><?php echo number_format($stats['attendance_today']); ?></div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 gap-8">
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
                <a href="../enrollments/create.php" class="w-full py-4 rounded-2xl bg-surface-container shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] text-on-surface font-medium flex items-center justify-center gap-2 hover:text-primary transition-all">
                    <span class="material-symbols-outlined">how_to_reg</span>
                    New Enrollment
                </a>
                <a href="../attendance/record.php" class="w-full py-4 rounded-2xl bg-surface-container shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] text-on-surface font-medium flex items-center justify-center gap-2 hover:text-primary transition-all">
                    <span class="material-symbols-outlined">checklist</span>
                    Record Attendance
                </a>
            </div>
        </div>
    </div>
</main>

<?php 
echo "</div>"; // Close the sidebar's content div
require_once '../../includes/footer.php'; 
?>
