<?php
/**
 * Student Registration Page
 * Renders a neomorphic form for student self-registration.
 */
require_once '../../config/database.php';
session_start();

$pageTitle = "Student Registration";
require_once '../../includes/header.php';
?>

<main class="min-h-screen flex items-center justify-center p-6 py-12">
    <!-- Neomorphic Registration Card -->
    <section class="w-full max-w-2xl bg-surface-container rounded-[32px] p-8 md:p-12 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] flex flex-col">
        <!-- Branding Header -->
        <header class="w-full flex flex-col items-center mb-10">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-6 shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff]">
                <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">person_add</span>
            </div>
            <h1 class="text-3xl font-bold text-on-surface text-center tracking-tight">Student Registration</h1>
            <p class="text-on-surface-variant text-center mt-2">Join our academic community</p>
        </header>

        <!-- Registration Form -->
        <form action="register_process.php" class="w-full flex flex-col gap-8" method="POST" id="registerForm">
            <input type="hidden" name="role" value="student">

            <!-- Personal Information Section -->
            <div class="space-y-6">
                <h2 class="text-lg font-semibold text-primary flex items-center gap-2 px-2">
                    <span class="material-symbols-outlined text-xl">person</span>
                    Personal Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="first_name">First Name</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="first_name" name="first_name" placeholder="John" required type="text"/>
                    </div>
                    <!-- Last Name -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="last_name">Last Name</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="last_name" name="last_name" placeholder="Doe" required type="text"/>
                    </div>
                    <!-- Email -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="email">Email Address</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="email" name="email" placeholder="john.doe@example.com" required type="email"/>
                    </div>
                    <!-- Username -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="username">Username</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="username" name="username" placeholder="johndoe24" required type="text"/>
                    </div>
                    <!-- Password -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="password">Password</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="password" name="password" placeholder="••••••••" required type="password"/>
                    </div>
                    <!-- Confirm Password -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="confirm_password">Confirm Password</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="confirm_password" name="confirm_password" placeholder="••••••••" required type="password"/>
                    </div>
                </div>
            </div>

            <!-- Student Specific Section -->
            <div class="space-y-6">
                <h2 class="text-lg font-semibold text-primary flex items-center gap-2 px-2">
                    <span class="material-symbols-outlined text-xl">school</span>
                    Student Details
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Student Number -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="student_number">Student ID / Number</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="student_number" name="student_number" placeholder="STU-2024-001" required type="text"/>
                    </div>
                    <!-- Date of Birth -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="date_of_birth">Date of Birth</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="date_of_birth" name="date_of_birth" required type="date"/>
                    </div>
                    <!-- Phone -->
                    <div class="w-full">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="phone">Phone Number</label>
                        <input class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                               id="phone" name="phone" placeholder="+1 (555) 000-0000" required type="tel"/>
                    </div>
                    <!-- Address -->
                    <div class="w-full md:col-span-2">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="address">Residential Address</label>
                        <textarea class="w-full bg-surface-container border-none rounded-2xl py-4 px-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200 min-h-[100px]" 
                               id="address" name="address" placeholder="123 Academic Way, Education City" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button class="w-full flex items-center justify-center gap-2 bg-surface-container rounded-2xl py-4 text-sm font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] active:shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] active:scale-[0.98] transition-all duration-300 ease-out" type="submit">
                    <span>Complete Registration</span>
                    <span class="material-symbols-outlined text-lg">how_to_reg</span>
                </button>
                <p class="text-center text-sm text-on-surface-variant mt-6">
                    Already have an account? 
                    <a href="login.php" class="text-primary font-medium hover:underline">Sign In</a>
                </p>
            </div>
        </form>
    </section>
</main>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match. Please ensure both password fields are identical.');
        document.getElementById('confirm_password').focus();
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>
