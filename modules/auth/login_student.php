<?php
/**
 * Student Login Page
 * Handles student authentication via Email or Student ID.
 */
require_once '../../config/database.php';
session_start();

// Redirect if already logged in as student
if (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    header("Location: ../dashboard/index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($identifier && $password) {
        // Expanded query to support Email, Student ID, Username, or Student Number
        $query = "SELECT u.*, s.login_id, s.student_number 
                  FROM users u 
                  LEFT JOIN students s ON u.user_id = s.user_id 
                  WHERE (u.email = :id1 OR s.login_id = :id2 OR u.username = :id3 OR s.student_number = :id4) 
                  AND u.role = 'student' 
                  AND u.is_active = 1 
                  LIMIT 1";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id1' => $identifier,
            'id2' => $identifier,
            'id3' => $identifier,
            'id4' => $identifier
        ]);
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
            $error = 'Invalid credentials. Please check your email/ID and password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

$pageTitle = "Student Login";
require_once '../../includes/header.php';
?>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    /* Refined Neomorphism */
    .neo-input:focus-within {
        box-shadow: inset 8px 8px 16px #dbe4eb, inset -8px -8px 16px #ffffff, 0 0 15px rgba(57, 101, 104, 0.1);
    }
    
    .loading-spinner {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 2px solid white;
        width: 18px;
        height: 18px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<main class="min-h-screen flex items-center justify-center p-6 bg-surface-container overflow-hidden">
    <!-- Neomorphic Login Card -->
    <section class="animate-fade-in w-full max-w-[420px] bg-surface-container rounded-[40px] p-10 md:p-12 shadow-[20px_20px_40px_#dbe4eb,-20px_-20px_40px_#ffffff] flex flex-col items-center border border-white/40">
        <!-- Branding Header -->
        <header class="w-full flex flex-col items-center mb-10">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-6 shadow-[6px_6px_12px_#dbe4eb,-6px_-6px_12px_#ffffff]">
                <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">school</span>
            </div>
            <h1 class="text-3xl font-bold text-on-surface text-center tracking-tight">Student Login</h1>
            <p class="text-on-surface-variant text-center mt-2">Access your academic portal</p>
        </header>

        <?php if ($error): ?>
            <div class="w-full mb-6 p-4 bg-error-container text-on-error-container rounded-2xl text-sm text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login_student.php" class="w-full flex flex-col gap-6" method="POST">
            <!-- Email/ID Input Group -->
            <div class="w-full">
                <label class="text-sm font-medium text-on-surface-variant block mb-3 ml-2" for="identifier">Email or Student ID</label>
                <div class="relative flex items-center group neo-input rounded-2xl transition-all duration-300">
                    <span class="material-symbols-outlined absolute left-4 text-outline-variant group-focus-within:text-primary transition-colors select-none pointer-events-none">person</span>
                    <input class="w-full bg-surface-container border-none rounded-2xl py-4 pl-12 pr-4 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-0 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-all duration-200" 
                           id="identifier" name="identifier" placeholder="Email, ID or Username" required type="text" value="<?php echo isset($_POST['identifier']) ? htmlspecialchars($_POST['identifier']) : ''; ?>"/>
                </div>
            </div>

            <!-- Password Input Group -->
            <div class="w-full">
                <label class="text-sm font-medium text-on-surface-variant block mb-3 ml-2" for="password">Password</label>
                <div class="relative flex items-center group neo-input rounded-2xl transition-all duration-300">
                    <span class="material-symbols-outlined absolute left-4 text-outline-variant group-focus-within:text-primary transition-colors select-none pointer-events-none">lock</span>
                    <input class="w-full bg-surface-container border-none rounded-2xl py-4 pl-12 pr-12 text-on-surface placeholder:text-outline-variant focus:outline-none focus:ring-0 shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff] transition-all duration-200" 
                           id="password" name="password" placeholder="••••••••" required type="password"/>
                    <button type="button" onclick="togglePassword()" class="absolute right-4 text-outline-variant hover:text-primary transition-colors focus:outline-none">
                        <span class="material-symbols-outlined select-none" id="password-icon">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Options Row -->
            <div class="w-full flex items-center justify-between mt-2 mb-4 px-1">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/30">
                    <span class="text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Remember me</span>
                </label>
                <a class="text-sm text-primary hover:text-surface-tint transition-colors font-medium" href="#">Forgot Password?</a>
            </div>

            <!-- Submit Button -->
            <button id="login-btn" class="w-full flex items-center justify-center gap-2 bg-primary text-on-primary rounded-2xl py-4 text-sm font-bold shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:brightness-110 active:scale-[0.98] transition-all duration-300 ease-out" type="submit">
                <span id="btn-text">Sign In</span>
                <span id="btn-icon" class="material-symbols-outlined text-lg">login</span>
            </button>

            <!-- Contact Admin Message -->
            <div class="w-full flex flex-col items-center gap-2 mt-6">
                <p class="text-sm text-on-surface-variant">Don't have an account?</p>
                <p class="text-sm text-primary font-bold italic">Contact Admin for account creation</p>
            </div>

            <!-- Back Link -->
            <div class="w-full flex items-center justify-center gap-2 mt-4">
                <a href="index.php" class="text-sm text-on-surface-variant hover:text-primary transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Back to Portal Selection
                </a>
            </div>
        </form>
    </section>
</main>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            passwordIcon.textContent = 'visibility';
        }
    }

    // Handle form submission loading state
    document.querySelector('form').addEventListener('submit', function(e) {
        const btn = document.getElementById('login-btn');
        const btnText = document.getElementById('btn-text');
        const btnIcon = document.getElementById('btn-icon');
        
        btn.disabled = true;
        btn.style.opacity = '0.8';
        btnText.textContent = 'Authenticating...';
        btnIcon.innerHTML = '<div class="loading-spinner"></div>';
    });
</script>

<?php require_once '../../includes/footer.php'; ?>
