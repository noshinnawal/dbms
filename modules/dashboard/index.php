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

// Fetch Student Profile Info if role is student
$studentInfo = null;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    try {
        $stmt = $pdo->prepare("
            SELECT u.username, u.email, u.first_name, u.last_name, 
                   s.student_number, s.login_id, s.date_of_birth, s.phone, s.address, s.enrollment_date, s.status
            FROM users u 
            JOIN students s ON u.user_id = s.user_id 
            WHERE u.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $studentInfo = $stmt->fetch();
    } catch (PDOException $e) {
        // Handle gracefully
    }
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
        <?php elseif ($studentInfo): ?>
        <!-- Student Personal Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="lg:col-span-1 bg-surface-container rounded-[40px] p-8 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] flex flex-col items-center text-center">
                <div class="w-32 h-32 rounded-full bg-surface-container shadow-[inset_8px_8px_16px_#dbe4eb,inset_-8px_-8px_16px_#ffffff] flex items-center justify-center mb-6 border-4 border-white/50">
                    <span class="material-symbols-outlined text-primary text-6xl" style="font-variation-settings: 'FILL' 1;">account_circle</span>
                </div>
                <h2 class="text-2xl font-bold text-on-surface"><?php echo htmlspecialchars($studentInfo['first_name'] . ' ' . $studentInfo['last_name']); ?></h2>
                <p class="text-primary font-medium mt-1"><?php echo htmlspecialchars($studentInfo['student_number']); ?></p>
                
                <div class="w-full mt-8 pt-8 border-t border-outline-variant/30 flex flex-col gap-4">
                    <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff]">
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</span>
                        <span class="px-3 py-1 rounded-full bg-primary-container text-primary text-xs font-bold"><?php echo strtoupper(htmlspecialchars($studentInfo['status'])); ?></span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff]">
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Joined</span>
                        <span class="text-sm font-medium text-on-surface"><?php echo date('M d, Y', strtotime($studentInfo['enrollment_date'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Account Info -->
                <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
                    <h3 class="text-lg font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">account_circle</span>
                        Account Details
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1 ml-1">Username</p>
                            <p class="p-3 rounded-xl bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] text-on-surface font-medium"><?php echo htmlspecialchars($studentInfo['username']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1 ml-1">Email</p>
                            <p class="p-3 rounded-xl bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] text-on-surface font-medium"><?php echo htmlspecialchars($studentInfo['email']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1 ml-1">Login ID</p>
                            <p class="p-3 rounded-xl bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] text-on-surface font-medium"><?php echo htmlspecialchars($studentInfo['login_id'] ?: 'N/A'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
                    <h3 class="text-lg font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">contact_page</span>
                        Personal Information
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold text-on-surface-variant uppercase mb-1 ml-1">Date of Birth</p>
                                <p class="p-3 rounded-xl bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] text-on-surface font-medium"><?php echo $studentInfo['date_of_birth'] ? date('M d, Y', strtotime($studentInfo['date_of_birth'])) : 'N/A'; ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-on-surface-variant uppercase mb-1 ml-1">Phone</p>
                                <p class="p-3 rounded-xl bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] text-on-surface font-medium"><?php echo htmlspecialchars($studentInfo['phone'] ?: 'N/A'); ?></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1 ml-1">Address</p>
                            <div class="p-3 rounded-xl bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] text-on-surface font-medium min-h-[80px]">
                                <?php echo nl2br(htmlspecialchars($studentInfo['address'] ?: 'No address provided.')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Student Dashboard Placeholder -->
        <div class="bg-surface-container rounded-[32px] p-10 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff] flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 rounded-full bg-surface-container shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary text-4xl">account_balance</span>
            </div>
            <h2 class="text-2xl font-bold text-on-surface">Student Portal Active</h2>
            <p class="text-on-surface-variant mt-2 max-w-md">Your profile information is being synchronized. Please contact your administrator if this takes too long.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php 
echo "</div>"; // Close the sidebar's content div
require_once '../../includes/footer.php'; 
?>
