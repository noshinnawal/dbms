<?php
/**
 * Navigation Sidebar
 * Role-based neomorphic sidebar.
 */
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? 'student';
?>

<aside class="fixed left-0 top-0 h-screen w-72 bg-surface-container shadow-[8px_0_16px_#dbe4eb] flex flex-col p-6 z-50">
    <!-- Brand -->
    <div class="flex items-center gap-4 mb-12 px-2">
        <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff]">
            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">school</span>
        </div>
        <h2 class="text-xl font-bold text-on-surface tracking-tight">EduSoft SMS</h2>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 flex flex-col gap-4">
        <a href="../dashboard/index.php" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all <?php echo $current_page == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] text-primary' : 'text-on-surface-variant hover:text-primary'; ?>">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="font-medium">Dashboard</span>
        </a>

        <?php if ($role === 'admin'): ?>
        <a href="../students/index.php" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all text-on-surface-variant hover:text-primary">
            <span class="material-symbols-outlined">group</span>
            <span class="font-medium">Students</span>
        </a>
        <a href="../courses/index.php" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all text-on-surface-variant hover:text-primary">
            <span class="material-symbols-outlined">menu_book</span>
            <span class="font-medium">Courses</span>
        </a>
        <?php endif; ?>


    </nav>

    <!-- User Section -->
    <div class="mt-auto border-t border-outline-variant pt-6">
        <div class="flex items-center gap-4 px-2 mb-6">
            <div class="w-10 h-10 rounded-full bg-surface-container shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">account_circle</span>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold text-on-surface truncate"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                <span class="text-xs text-on-surface-variant capitalize"><?php echo htmlspecialchars($role); ?></span>
            </div>
        </div>
        <a href="../auth/logout.php" class="flex items-center gap-4 px-4 py-3 rounded-2xl text-error hover:bg-error/5 transition-all">
            <span class="material-symbols-outlined">logout</span>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</aside>

<!-- Space for sidebar on the right -->
<div class="pl-72 w-full min-h-screen flex flex-col">
