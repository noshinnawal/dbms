<?php
/**
 * Create Section
 */
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

checkRole(['admin']);

$error = '';
$success = '';

// Fetch courses for selection
try {
    $courses = $pdo->query("SELECT course_id, course_code, course_name FROM courses WHERE is_active = 1 ORDER BY course_code")->fetchAll();
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = (int)$_POST['course_id'];
    $section_code = trim($_POST['section_code']);
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];
    $schedule_day = $_POST['schedule_day'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $room = trim($_POST['room']);
    $capacity = (int)$_POST['max_capacity'];

    try {
        $stmt = $pdo->prepare("INSERT INTO sections (course_id, section_code, semester, academic_year, schedule_day, schedule_time_start, schedule_time_end, room, max_capacity) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$course_id, $section_code, $semester, $academic_year, $schedule_day, $time_start, $time_end, $room, $capacity]);
        $success = "Section scheduled successfully!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Error: This course already has a section with code '$section_code' for the selected semester/year.";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

$pageTitle = "Create Section";
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
?>

<main class="p-8 flex-1">
    <header class="mb-10">
        <h1 class="text-3xl font-bold text-on-surface">Create New Section</h1>
        <p class="text-on-surface-variant mt-1">Schedule a course offering.</p>
    </header>

    <?php if ($error): ?>
        <div class="mb-8 p-4 bg-error-container text-on-error-container rounded-2xl text-sm font-medium">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="mb-8 p-4 bg-primary-container text-primary rounded-2xl text-sm font-medium">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form action="create.php" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Core Scheduling -->
        <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">calendar_month</span>
                Course & Time
            </h2>
            <div class="flex flex-col gap-6">
                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="course_id">Course</label>
                    <select id="course_id" name="course_id" required
                            class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 appearance-none">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo $c['course_id']; ?>"><?php echo htmlspecialchars($c['course_code'] . ' - ' . $c['course_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="semester">Semester</label>
                        <select id="semester" name="semester" required
                                class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 appearance-none">
                            <option value="Fall">Fall</option>
                            <option value="Spring">Spring</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="academic_year">Academic Year</label>
                        <input type="number" id="academic_year" name="academic_year" required value="<?php echo date('Y'); ?>"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-1">
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="schedule_day">Day</label>
                        <select id="schedule_day" name="schedule_day" required
                                class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 appearance-none">
                            <option value="Mon/Wed">Mon/Wed</option>
                            <option value="Tue/Thu">Tue/Thu</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="time_start">Start Time</label>
                        <input type="time" id="time_start" name="time_start" required
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="time_end">End Time</label>
                        <input type="time" id="time_end" name="time_end" required
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>
            </div>
        </section>

        <!-- Assignments & Capacity -->
        <section class="bg-surface-container rounded-[32px] p-8 shadow-[12px_12px_24px_#dbe4eb,-12px_-12px_24px_#ffffff]">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person_pin</span>
                Assignment & Details
            </h2>
            <div class="flex flex-col gap-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="section_code">Section Code</label>
                        <input type="text" id="section_code" name="section_code" required placeholder="A01"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30 uppercase">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="max_capacity">Max Capacity</label>
                        <input type="number" id="max_capacity" name="max_capacity" required value="30" min="1" max="500"
                               class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-on-surface-variant block mb-2 ml-2" for="room">Room / Location</label>
                    <input type="text" id="room" name="room" required placeholder="Lab 301, Engineering Bldg"
                           class="w-full bg-surface-container border-none rounded-2xl py-3 px-4 text-on-surface shadow-[inset_4px_4px_8px_#dbe4eb,inset_-4px_-4px_8px_#ffffff] outline-none focus:ring-1 focus:ring-primary/30">
                </div>
            </div>
        </section>

        <!-- Submit -->
        <div class="lg:col-span-2 flex justify-end gap-4 mt-4">
            <a href="index.php" class="px-8 py-4 rounded-2xl text-on-surface-variant font-bold hover:text-on-surface transition-all">Cancel</a>
            <button type="submit" class="px-10 py-4 bg-surface-container rounded-2xl font-bold text-primary shadow-[8px_8px_16px_#dbe4eb,-8px_-8px_16px_#ffffff] hover:shadow-[4px_4px_8px_#dbe4eb,-4px_-4px_8px_#ffffff] active:scale-95 transition-all">
                Schedule Section
            </button>
        </div>
    </form>
</main>

<?php 
echo "</div>";
require_once '../../includes/footer.php'; 
?>
