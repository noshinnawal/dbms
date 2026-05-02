<?php
/**
 * Login Page
 * Handles user authentication and renders the neomorphic login form.
 */
require_once '../../config/database.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['login_input'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login_input && $password) {
        // Hardcoded Admin Login
        if ($login_input === 'admin' && $password === 'admin123') {
            $_SESSION['user_id'] = 0;
            $_SESSION['username'] = 'admin';
            $_SESSION['role'] = 'admin';
            $_SESSION['full_name'] = 'System Administrator';
            
            header("Location: ../dashboard/index.php");
            exit;
        }

        // Check if input is email or username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE (email = ? OR username = ?) AND is_active = 1");
        $stmt->execute([$login_input, $login_input]);
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
            $error = 'Invalid credentials.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

$pageTitle = "Login";
require_once '../../includes/header.php';
?>

<main class="min-h-screen flex items-center justify-center p-6">
    <!-- Neomorphic Login Card -->
    <section class="w-full max-w-[420px] bg-surface-container rounded-[32px] p-10 md:p-12 shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff] flex flex-col items-center">
        <!-- Branding Header -->
        <header class="w-full flex flex-col items-center mb-10">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-6 shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff]">
                <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">school</span>
            </div>
            <h1 class="text-3xl font-bold text-on-surface text-center tracking-tight">EduSoft SMS</h1>
            <p class="text-on-surface-variant text-center mt-2">Welcome back to the portal</p>
        </header>

        <?php if ($error): ?>
            <div class="w-full mb-6 p-4 bg-error-container text-on-error-container rounded-2xl text-sm text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" class="w-full flex flex-col gap-6" method="POST">
            <!-- Email/Username Input Group -->
            <div class="w-full">
                <label class="text-sm font-medium text-on-surface-variant block mb-3 ml-2" for="login_input">Username or Email</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-4 text-outline-variant select-none pointer-events-none">person</span>
                    <input class="w-full bg-surface-container border-none rounded-2xl py-4 pl-12 pr-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-1 focus:ring-primary/30 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-shadow duration-200" 
                           id="login_input" name="login_input" placeholder="admin or admin@school.edu" required type="text" value="<?php echo isset($_POST['login_input']) ? htmlspecialchars($_POST['login_input']) : ''; ?>"/>
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

            <!-- Options Row -->
            <div class="w-full flex items-center justify-between mt-2 mb-4 px-1">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center justify-center w-6 h-6">
                        <input class="peer sr-only" type="checkbox" name="remember"/>
                        <div class="w-6 h-6 rounded-md bg-surface-container shadow-[inset_3px_3px_6px_#dbe4eb,inset_-3px_-3px_6px_#ffffff] transition-all"></div>
                        <div class="absolute w-3 h-3 rounded-sm bg-primary opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                    </div>
                    <span class="text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Remember me</span>
                </label>
                <a class="text-sm text-primary hover:text-surface-tint transition-colors" href="#">Forgot Password?</a>
            </div>

            <!-- Submit Button -->
            <button class="w-full flex items-center justify-center gap-2 bg-surface-container rounded-2xl py-4 text-sm font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff] active:shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] active:scale-[0.98] transition-all duration-300 ease-out" type="submit">
                <span>Sign In</span>
                <span class="material-symbols-outlined text-lg">login</span>
            </button>

            <!-- Registration Link -->
            <div class="w-full flex items-center justify-center gap-2 mt-2">
                <span class="text-sm text-on-surface-variant">New here?</span>
                <a href="register_choice.php" class="text-sm font-bold text-primary hover:text-surface-tint transition-all flex items-center gap-1">
                    Create Account
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </form>
    </section>
</main>

<?php require_once '../../includes/footer.php'; ?>
