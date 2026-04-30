<?php
/**
 * Registration Choice Page
 * Allows users to choose between Student and Teacher registration.
 */
$pageTitle = "Choose Your Role";
require_once '../../includes/header.php';
?>

<main class="min-h-screen flex flex-col items-center justify-center p-6">
    <!-- Header Section -->
    <header class="text-center mb-12">
        <h1 class="text-4xl font-bold text-on-surface tracking-tight mb-4">Join Our Community</h1>
        <p class="text-on-surface-variant text-lg">Select your role to get started with your registration</p>
    </header>

    <!-- Cards Container -->
    <div class="flex flex-col md:flex-row gap-12 w-full max-w-4xl justify-center items-stretch">
        
        <!-- Student Card -->
        <a href="register_student.php" class="group flex-1" aria-label="Register as a Student">
            <section class="h-full bg-surface-container rounded-[32px] p-12 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] hover:scale-105 transition-transform duration-300 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-surface-container flex items-center justify-center mb-8 shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] group-hover:shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-300">
                    <span class="material-symbols-outlined text-primary text-5xl" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">school</span>
                </div>
                <h2 class="text-2xl font-bold text-on-surface mb-4">Student</h2>
                <p class="text-on-surface-variant">Access courses, track your grades, and manage your academic schedule.</p>
                <div class="mt-8 flex items-center gap-2 text-primary font-semibold">
                    <span>Register as Student</span>
                    <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform" aria-hidden="true">arrow_forward</span>
                </div>
            </section>
        </a>

        <!-- Teacher Card -->
        <a href="register_teacher.php" class="group flex-1" aria-label="Register as a Teacher">
            <section class="h-full bg-surface-container rounded-[32px] p-12 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] hover:scale-105 transition-transform duration-300 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-surface-container flex items-center justify-center mb-8 shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] group-hover:shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-300">
                    <span class="material-symbols-outlined text-primary text-5xl" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">workspace_premium</span>
                </div>
                <h2 class="text-2xl font-bold text-on-surface mb-4">Teacher</h2>
                <p class="text-on-surface-variant">Manage classes, grade students, and share resources with your pupils.</p>
                <div class="mt-8 flex items-center gap-2 text-primary font-semibold">
                    <span>Register as Teacher</span>
                    <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform" aria-hidden="true">arrow_forward</span>
                </div>
            </section>
        </a>

    </div>

    <!-- Back to Login -->
    <footer class="mt-16">
        <p class="text-on-surface-variant">
            Already have an account? 
            <a href="login.php" class="text-primary font-bold hover:underline">Sign In</a>
        </p>
    </footer>
</main>

<?php require_once '../../includes/footer.php'; ?>
