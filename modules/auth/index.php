<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/index.php");
    exit;
}
$pageTitle = "Welcome to EduSoft SMS";
require_once '../../includes/header.php';
?>

<main class="min-h-screen flex flex-col items-center justify-center p-6">
    <!-- Header Section -->
    <header class="text-center mb-16">
        <div class="w-20 h-20 rounded-full bg-surface-container flex items-center justify-center mb-8 mx-auto shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff]">
            <span class="material-symbols-outlined text-primary text-5xl" style="font-variation-settings: 'FILL' 1;">school</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-on-surface tracking-tight mb-4">EduSoft SMS</h1>
        <p class="text-on-surface-variant text-lg md:text-xl max-w-2xl">A comprehensive academic management ecosystem designed for simplicity and excellence.</p>
    </header>

    <!-- Cards Container -->
    <div class="flex flex-col md:flex-row gap-12 w-full max-w-4xl justify-center items-stretch">
        
        <!-- Student Portal Card -->
        <a href="login_student.php" class="group flex-1" aria-label="Access Student Portal">
            <section class="h-full bg-surface-container rounded-[40px] p-10 md:p-12 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] hover:scale-[1.02] transition-all duration-300 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-surface-container flex items-center justify-center mb-8 shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] group-hover:shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-300">
                    <span class="material-symbols-outlined text-primary text-5xl" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">person</span>
                </div>
                <h2 class="text-2xl font-bold text-on-surface mb-4">Student Portal</h2>
                <p class="text-on-surface-variant leading-relaxed">View your courses, track academic progress, and manage your enrollment status seamlessly.</p>
                <div class="mt-10 flex items-center gap-2 text-primary font-bold">
                    <span>Enter Student Portal</span>
                    <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform" aria-hidden="true">arrow_forward</span>
                </div>
            </section>
        </a>

        <!-- Admin Portal Card -->
        <a href="login_admin.php" class="group flex-1" aria-label="Access Admin Portal">
            <section class="h-full bg-surface-container rounded-[40px] p-10 md:p-12 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] hover:scale-[1.02] transition-all duration-300 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-surface-container flex items-center justify-center mb-8 shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] group-hover:shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-300">
                    <span class="material-symbols-outlined text-primary text-5xl" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">admin_panel_settings</span>
                </div>
                <h2 class="text-2xl font-bold text-on-surface mb-4">Admin Portal</h2>
                <p class="text-on-surface-variant leading-relaxed">Manage institution records, oversee faculty assignments, and maintain system configuration.</p>
                <div class="mt-10 flex items-center gap-2 text-primary font-bold">
                    <span>Enter Admin Portal</span>
                    <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform" aria-hidden="true">arrow_forward</span>
                </div>
            </section>
        </a>

    </div>

    <!-- Additional Options -->
    <footer class="mt-20 text-center">
        <p class="text-on-surface-variant mb-4">New to the system?</p>
        <p class="text-lg text-primary font-bold italic">Contact Admin for account creation</p>
    </footer>
</main>

<?php require_once '../../includes/footer.php'; ?>
