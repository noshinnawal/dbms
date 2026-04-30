<?php
/**
 * Faculty Listing
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$search = $_GET['search'] ?? '';

try {
    $query = "SELECT f.*, u.first_name, u.last_name, u.email 
              FROM faculty f 
              JOIN users u ON f.user_id = u.user_id";
    
    if ($search) {
        $query .= " WHERE u.first_name LIKE :search 
                    OR u.last_name LIKE :search 
                    OR f.employee_number LIKE :search 
                    OR f.department LIKE :search";
    }
    
    $query .= " ORDER BY f.created_at DESC";
    
    $stmt = $pdo->prepare($query);
    if ($search) {
        $stmt->bindValue(':search', "%$search%");
    }
    $stmt->execute();
    $faculty_list = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching faculty: " . $e->getMessage());
}

$pageTitle = "Manage Faculty";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Manage Faculty</h1>
            <p class="text-on-surface-variant mt-1">Oversee academic staff and department assignments.</p>
        </div>
        <a href="create.php" class="flex items-center gap-2 bg-surface-container rounded-2xl px-6 py-3 font-bold text-secondary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] transition-all">
            <span class="material-symbols-outlined">person_add</span>
            Add Faculty Member
        </a>
    </header>

    <!-- Search Bar -->
    <section class="mb-10">
        <form action="index.php" method="GET" class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">search</span>
            <input type="text" name="search" placeholder="Search by name, ID, or department..." value="<?php echo htmlspecialchars($search); ?>"
                   class="w-full bg-surface-container border-none rounded-2xl py-4 pl-12 pr-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] focus:ring-1 focus:ring-primary/30 outline-none transition-all">
        </form>
    </section>

    <!-- Faculty Table -->
    <div class="bg-surface-container rounded-[32px] overflow-hidden shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant">
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Employee ID</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Name</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Department</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="px-8 py-6 text-sm font-bold text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                <?php if (empty($faculty_list)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-on-surface-variant italic">No faculty members found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($faculty_list as $faculty): ?>
                        <tr class="hover:bg-secondary/5 transition-colors">
                            <td class="px-8 py-6 text-on-surface font-medium"><?php echo htmlspecialchars($faculty['employee_number']); ?></td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-on-surface font-bold"><?php echo htmlspecialchars($faculty['first_name'] . ' ' . $faculty['last_name']); ?></span>
                                    <span class="text-xs text-on-surface-variant"><?php echo htmlspecialchars($faculty['title']); ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-on-surface-variant font-medium"><?php echo htmlspecialchars($faculty['department']); ?></td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold capitalize 
                                    <?php echo $faculty['status'] === 'active' ? 'bg-success/10 text-success' : 'bg-outline-variant text-on-surface-variant'; ?>">
                                    <?php echo htmlspecialchars($faculty['status']); ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="view.php?id=<?php echo $faculty['faculty_id']; ?>" class="w-10 h-10 rounded-xl bg-surface-container shadow-[2px_2px_4px_#dbe4eb,-2px_-2px_4px_#ffffff] flex items-center justify-center text-secondary hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                    <a href="edit.php?id=<?php echo $faculty['faculty_id']; ?>" class="w-10 h-10 rounded-xl bg-surface-container shadow-[2px_2px_4px_#dbe4eb,-2px_-2px_4px_#ffffff] flex items-center justify-center text-on-surface-variant hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
