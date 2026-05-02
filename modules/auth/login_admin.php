<?php
/**
 * Admin Login Page
 * Handles administrative authentication and renders the neomorphic login form.
 */
require_once '../../config/database.php';
session_start();

// Redirect if already logged in as admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        // Hardcoded Admin Login Fallback
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id'] = 0;
            $_SESSION['username'] = 'admin';
            $_SESSION['role'] = 'admin';
            $_SESSION['full_name'] = 'System Administrator';
            
            header("Location: ../dashboard/index.php");
            exit;
        }

        // Query users table for admin role
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin' AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
            
            header("Location: ../dashboard/index.php");
            exit;
        } else {
            $error = 'Invalid admin credentials.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

$pageTitle = "Admin Login";
require_once '../../includes/header.php';
?>

<main class="min-h-screen flex items-center justify-center p-6 bg-surface-container">
    <!-- Neomorphic Login Card -->
    <section class="w-full max-w-[420px] bg-surface-container rounded-[32px] p-10 md:p-12 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] flex flex-col items-center border border-white/20">
        <!-- Branding Header -->
        <header class="w-full flex flex-col items-center mb-10">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-6 shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff]">
                <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">admin_panel_settings</span>
            </div>
            <h1 class="text-3xl font-bold text-on-surface text-center tracking-tight">Admin Portal</h1>
            <p class="text-on-surface-variant text-center mt-2">Secure access for staff only</p>
        </header>

        <?php if ($error): ?>
            <div class="w-full mb-6 p-4 bg-error-container text-on-error-container rounded-2xl text-sm text-center animate-pulse">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login_admin.php" class="w-full flex flex-col gap-6" method="POST">
            <!-- Username Input Group -->
            <div class="w-full">
                <label class="text-sm font-medium text-on-surface-variant block mb-3 ml-2" for="username">Admin Username</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-4 text-outline-variant select-none pointer-events-none">shield_person</span>
                    <input class="w-full bg-surface-container border-none rounded-2xl py-4 pl-12 pr-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                           id="username" name="username" placeholder="Enter admin username" required type="text" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"/>
                </div>
            </div>

            <!-- Password Input Group -->
            <div class="w-full">
                <label class="text-sm font-medium text-on-surface-variant block mb-3 ml-2" for="password">Password</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-4 text-outline-variant select-none pointer-events-none">lock</span>
                    <input class="w-full bg-surface-container border-none rounded-2xl py-4 pl-12 pr-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                           id="password" name="password" placeholder="••••••••" required type="password"/>
                </div>
            </div>

            <!-- Options Row (Simplified for Admin) -->
            <div class="w-full flex items-center justify-end mt-2 mb-4 px-1">
                <a class="text-sm text-primary hover:text-surface-tint transition-colors" href="#">Reset Secure Key?</a>
            </div>

            <!-- Submit Button -->
            <button class="w-full flex items-center justify-center gap-2 bg-primary text-on-primary rounded-2xl py-4 text-sm font-bold shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:brightness-110 active:scale-[0.98] transition-all duration-300 ease-out" type="submit">
                <span>Authorize Access</span>
                <span class="material-symbols-outlined text-lg">verified_user</span>
            </button>

            <!-- Back Link -->
            <div class="w-full flex items-center justify-center gap-2 mt-4">
                <a href="login.php" class="text-sm text-on-surface-variant hover:text-primary transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Standard Login
                </a>
            </div>
        </form>
    </section>
</main>

<?php require_once '../../includes/footer.php'; ?>
