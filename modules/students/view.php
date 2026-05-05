<?php
/**
 * View Student Profile
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$student_id = $_GET['id'] ?? null;

if (!$student_id) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT s.*, u.username, u.email, u.first_name, u.last_name 
                          FROM students s 
                          JOIN users u ON s.user_id = u.user_id 
                          WHERE s.student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    if (!$student) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching student details: " . $e->getMessage());
}

$pageTitle = "View Student: " . $student['first_name'];
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="flex justify-between items-center mb-10">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="index.php" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold text-on-surface">Student Profile</h1>
            </div>
            <p class="text-on-surface-variant ml-9">Full details for <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>.</p>
        </div>
        <div class="flex gap-4">
            <a href="edit.php?id=<?php echo $student['student_id']; ?>" class="flex items-center gap-2 bg-surface-container rounded-2xl px-6 py-3 font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] transition-all">
                <span class="material-symbols-outlined">edit</span>
                Edit Profile
            </a>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Quick Info & Status -->
        <div class="lg:col-span-1 space-y-8">
            <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff] text-center">
                <div class="w-32 h-32 rounded-full bg-surface-container shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] mx-auto mb-6 flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-primary/40">person</span>
                </div>
                <h2 class="text-2xl font-bold text-on-surface mb-1"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h2>
                <p class="text-on-surface-variant mb-6"><?php echo htmlspecialchars($student['student_number']); ?></p>
                
                <div class="inline-block px-4 py-2 rounded-full text-sm font-bold capitalize mb-8
                    <?php 
                        echo $student['status'] === 'active' ? 'bg-success/10 text-success' : 
                            ($student['status'] === 'graduated' ? 'bg-primary/10 text-primary' : 'bg-outline-variant text-on-surface-variant'); 
                    ?>">
                    <?php echo htmlspecialchars($student['status']); ?>
                </div>

                <div class="space-y-4 text-left border-t border-outline-variant/30 pt-6">
                    <div class="flex items-center gap-3 text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary/60">mail</span>
                        <span class="text-sm"><?php echo htmlspecialchars($student['email']); ?></span>
                    </div>
                    <div class="flex items-center gap-3 text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary/60">call</span>
                        <span class="text-sm"><?php echo htmlspecialchars($student['phone'] ?: 'No phone provided'); ?></span>
                    </div>
                </div>
            </section>
        </div>

        <!-- Main Content: Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Core Profile Information -->
            <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
                <h3 class="text-xl font-bold text-on-surface mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">badge</span>
                    Academic & Personal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">

                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Enrollment Date</p>
                        <p class="text-on-surface font-medium"><?php echo date('F d, Y', strtotime($student['enrollment_date'])); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Date of Birth</p>
                        <p class="text-on-surface font-medium"><?php echo $student['date_of_birth'] ? date('F d, Y', strtotime($student['date_of_birth'])) : 'Not provided'; ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">System Username</p>
                        <p class="text-on-surface font-medium"><?php echo htmlspecialchars($student['username']); ?></p>
                    </div>
                </div>
            </section>

            <!-- Parental Information -->
            <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
                <h3 class="text-xl font-bold text-on-surface mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">family_restroom</span>
                    Parental Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Father's Name</p>
                        <p class="text-on-surface font-medium"><?php echo htmlspecialchars($student['father_name'] ?: 'Not provided'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Father's Occupation</p>
                        <p class="text-on-surface font-medium"><?php echo htmlspecialchars($student['father_occupation'] ?: 'Not provided'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Mother's Name</p>
                        <p class="text-on-surface font-medium"><?php echo htmlspecialchars($student['mother_name'] ?: 'Not provided'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Mother's Occupation</p>
                        <p class="text-on-surface font-medium"><?php echo htmlspecialchars($student['mother_occupation'] ?: 'Not provided'); ?></p>
                    </div>
                </div>
            </section>

            <!-- Residential Details -->
            <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
                <h3 class="text-xl font-bold text-on-surface mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">home_pin</span>
                    Residential Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Present Address</p>
                        <p class="text-on-surface font-medium"><?php echo nl2br(htmlspecialchars($student['present_address'] ?: 'No address provided')); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Permanent Address</p>
                        <p class="text-on-surface font-medium"><?php echo nl2br(htmlspecialchars($student['permanent_address'] ?: 'No address provided')); ?></p>
                    </div>
                </div>
            </section>

            <!-- Actions -->
            <div class="flex justify-end gap-4">
                <button onclick="confirmDelete(<?php echo $student['student_id']; ?>)" class="px-8 py-4 rounded-2xl font-bold text-error hover:bg-error/5 transition-all">
                    Delete Record
                </button>
            </div>
        </div>
    </div>
</main>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
        window.location.href = 'delete.php?id=' + id;
    }
}
</script>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
