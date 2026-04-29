# University School Management System (SMS)
## Project Plan - Database-Focused Implementation

**Course**: CSE 425 - Database Management  
**Technology Stack**: Vanilla JS, HTML5, CSS3, PHP, MySQL (XAMPP)  
**Date**: April 27, 2026  
**Focus**: Robust database implementation with clean, simple code

---

## 1. PROJECT OVERVIEW

A full-stack School Management System built with vanilla technologies (no frameworks) focusing on database design, normalization, and robust CRUD operations. The system implements a neomorphic UI design matching the existing prototype mockups.

### Core Principles
- **Simple & Readable**: Clean code that's easy to understand and maintain
- **Database-First**: Proper normalization, constraints, and relationships
- **Secure**: Prepared statements, password hashing, input validation
- **Consistent**: Uniform neomorphic design across all pages

---

## 2. DATABASE SCHEMA DESIGN

### 2.1 Entity-Relationship Overview

The system manages the following core entities:
- **Users** - Authentication and authorization (admins, faculty, students)
- **Students** - Student profiles and enrollment information
- **Faculty** - Faculty members and their departments
- **Courses** - Course catalog with credits and descriptions
- **Sections** - Course offerings per semester with assigned faculty
- **Enrollments** - Many-to-many relationship between students and sections
- **Attendance** - Daily attendance tracking for enrolled students
- **Grades** - Assessment records with weighted scoring
- **Notifications** - System notifications for users

### 2.2 Complete SQL Schema

```sql
-- --------------------------------------------------------
-- DATABASE: university_sms
-- --------------------------------------------------------

-- --------------------------------------------------------
-- TABLE: users
-- --------------------------------------------------------
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'faculty', 'student') NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLE: students
-- --------------------------------------------------------
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    student_number VARCHAR(20) UNIQUE NOT NULL,
    date_of_birth DATE,
    phone VARCHAR(20),
    address TEXT,
    enrollment_date DATE NOT NULL,
    status ENUM('active', 'inactive', 'graduated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_student_number (student_number),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLE: faculty
-- --------------------------------------------------------
CREATE TABLE faculty (
    faculty_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    employee_number VARCHAR(20) UNIQUE NOT NULL,
    department VARCHAR(100) NOT NULL,
    title VARCHAR(50),
    hire_date DATE NOT NULL,
    office_location VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_employee_number (employee_number),
    INDEX idx_department (department),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLE: courses
-- --------------------------------------------------------
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    description TEXT,
    credits INT NOT NULL DEFAULT 3,
    department VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_course_code (course_code),
    INDEX idx_department (department),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLE: sections
-- --------------------------------------------------------
CREATE TABLE sections (
    section_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    faculty_id INT NOT NULL,
    section_code VARCHAR(20) NOT NULL,
    semester VARCHAR(20) NOT NULL,
    academic_year YEAR NOT NULL,
    schedule_day VARCHAR(20),
    schedule_time_start TIME,
    schedule_time_end TIME,
    room VARCHAR(20),
    max_capacity INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (faculty_id) REFERENCES faculty(faculty_id) ON DELETE CASCADE,
    UNIQUE KEY unique_section (course_id, section_code, semester, academic_year),
    INDEX idx_semester (semester),
    INDEX idx_faculty (faculty_id),
    INDEX idx_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLE: enrollments
-- --------------------------------------------------------
CREATE TABLE enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    section_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('enrolled', 'dropped', 'completed') DEFAULT 'enrolled',
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(section_id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, section_id),
    INDEX idx_student (student_id),
    INDEX idx_section (section_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLE: attendance
-- --------------------------------------------------------
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,
    notes TEXT,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(enrollment_id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (enrollment_id, attendance_date),
    INDEX idx_enrollment (enrollment_id),
    INDEX idx_date (attendance_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLE: grades
-- --------------------------------------------------------
CREATE TABLE grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL,
    grade_type ENUM('quiz', 'midterm', 'final', 'project', 'homework') NOT NULL,
    score DECIMAL(5,2),
    max_score DECIMAL(5,2) DEFAULT 100,
    weight DECIMAL(5,2) DEFAULT 10,
    comment TEXT,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(enrollment_id) ON DELETE CASCADE,
    INDEX idx_enrollment (enrollment_id),
    INDEX idx_grade_type (grade_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- TABLE: notifications
-- --------------------------------------------------------
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2.3 Database Design Decisions

**Normalization Level**: 3NF (Third Normal Form)
- All tables have primary keys
- No repeating groups
- Foreign keys enforce referential integrity
- Composite unique keys prevent duplicate relationships

**Key Features**:
1. **CASCADE Deletes**: Child records automatically deleted when parent is removed
2. **ENUM Types**: Controlled values for status fields
3. **Timestamps**: All tables track creation time
4. **Indexes**: Strategic indexes on frequently queried columns
5. **InnoDB Engine**: Supports transactions and foreign keys

**Sample Data Insertion**:
```sql
-- Create admin user (password: admin123)
INSERT INTO users (username, password, email, role, first_name, last_name) 
VALUES ('admin', '$2y$10$YourHashedPasswordHere', 'admin@school.edu', 'admin', 'System', 'Admin');

-- Insert sample courses
INSERT INTO courses (course_code, course_name, credits, department) VALUES
('CS101', 'Introduction to Programming', 3, 'Computer Science'),
('MATH201', 'Calculus I', 4, 'Mathematics'),
('ENG101', 'English Composition', 3, 'English');

-- Insert sample faculty
INSERT INTO faculty (user_id, employee_number, department, title, hire_date, office_location)
VALUES (1, 'FAC001', 'Computer Science', 'Professor', '2020-01-15', 'Room 201');
```

---

## 3. PROJECT STRUCTURE

```
cse425/
├── config/
│   └── database.php          # Database connection (PDO)
├── includes/
│   ├── header.php            # HTML header with CSS/JS
│   ├── footer.php            # HTML footer
│   ├── navbar.php            # Navigation menu (role-based)
│   ├── auth_check.php        # Authentication middleware
│   └── flash.php             # Flash message system
├── css/
│   ├── style.css             # Main neomorphic styles
│   └── components.css        # Component-specific styles
├── js/
│   ├── main.js               # Global JavaScript utilities
│   ├── auth.js               # Authentication functions
│   └── components/           # Reusable components
│       ├── modal.js
│       └── form-validation.js
├── modules/
│   ├── auth/
│   │   ├── login.php         # Login page
│   │   ├── logout.php        # Logout handler
│   │   └── register.php      # User registration (optional)
│   ├── dashboard/
│   │   └── index.php         # Main dashboard with statistics
│   ├── students/
│   │   ├── index.php         # List all students
│   │   ├── create.php        # Add new student
│   │   ├── edit.php          # Edit student details
│   │   ├── view.php          # View student profile
│   │   └── delete.php        # Delete student (AJAX)
│   ├── faculty/
│   │   ├── index.php         # List faculty members
│   │   ├── create.php        # Add faculty member
│   │   ├── edit.php          # Edit faculty details
│   │   ├── view.php          # View faculty profile
│   │   └── delete.php        # Delete faculty (AJAX)
│   ├── courses/
│   │   ├── index.php         # List courses
│   │   ├── create.php        # Add course
│   │   ├── edit.php          # Edit course
│   │   └── sections.php      # Manage course sections
│   ├── sections/
│   │   ├── index.php         # List all sections
│   │   ├── create.php        # Create section offering
│   │   ├── edit.php          # Edit section
│   │   └── roster.php        # View section roster
│   ├── enrollments/
│   │   ├── index.php         # List enrollments
│   │   ├── create.php        # Enroll student
│   │   ├── edit.php          # Update enrollment
│   │   └── drop.php          # Drop student (AJAX)
│   ├── attendance/
│   │   ├── index.php         # Attendance records
│   │   ├── record.php        # Record attendance (bulk)
│   │   └── report.php        # Attendance reports
│   ├── grades/
│   │   ├── index.php         # Grade records
│   │   ├── record.php        # Record grades
│   │   ├── edit.php          # Edit grades
│   │   └── report.php        # Grade reports (transcript)
│   └── notifications/
│       ├── index.php         # View notifications
│       └── mark-read.php     # Mark as read (AJAX)
├── index.php                 # Home/redirect page
└── README.md                 # Project documentation
```

---

## 4. IMPLEMENTATION PHASES

### Phase 1: Database & Core Infrastructure (Days 1-2)
**Objective**: Set up database and core PHP infrastructure

**Tasks**:
- [ ] Install and configure XAMPP (Apache + MySQL)
- [ ] Create `university_sms` database
- [ ] Execute SQL schema with sample data
- [ ] Create `config/database.php` with PDO connection
- [ ] Build `includes/header.php` and `includes/footer.php`
- [ ] Implement `includes/auth_check.php` for session management
- [ ] Create reusable neomorphic CSS in `css/style.css`
- [ ] Set up `includes/navbar.php` with role-based menu

**Deliverables**:
- Working database with sample data
- Functional login/logout system
- Consistent neomorphic UI across pages

### Phase 2: Authentication Module (Days 2-3)
**Objective**: Secure login system with role-based access

**Tasks**:
- [ ] Create login form (matching neomorphic design)
- [ ] Implement password hashing (password_hash)
- [ ] Build session management
- [ ] Add logout functionality
- [ ] Implement role-based redirects
- [ ] Add password reset (optional)
- [ ] Create user registration (for testing)

**Security Features**:
- Prepared statements for all queries
- CSRF token generation
- Session regeneration on login
- Brute force protection (login attempt limits)

**Deliverables**:
- Secure login/logout system
- Role-based access control (admin, faculty, student)

### Phase 3: Dashboard & Statistics (Days 3-4)
**Objective**: Main dashboard with key metrics

**Tasks**:
- [ ] Create dashboard layout with sidebar
- [ ] Display total students, faculty, courses
- [ ] Show today's attendance summary
- [ ] Display recent enrollments
- [ ] Add quick action buttons
- [ ] Implement role-specific dashboards

**SQL Queries**:
```php
// Total students
SELECT COUNT(*) FROM students WHERE status = 'active';

// Today's attendance
SELECT COUNT(*) FROM attendance 
WHERE attendance_date = CURDATE() 
AND status = 'present';

// Recent enrollments (last 7 days)
SELECT s.first_name, s.last_name, c.course_name 
FROM enrollments e
JOIN students s ON e.student_id = s.student_id
JOIN sections sec ON e.section_id = sec.section_id
JOIN courses c ON sec.course_id = c.course_id
WHERE e.enrollment_date >= DATE_SUB(NOW(), INTERVAL 7 DAY);
```

**Deliverables**:
- Functional dashboard with statistics
- Role-specific views

### Phase 4: Student Management (Days 4-6)
**Objective**: Full CRUD for student records

**Tasks**:
- [ ] List students with search/filter
- [ ] Add new student form
- [ ] Edit student details
- [ ] View student profile (with enrollments)
- [ ] Delete student (soft delete)
- [ ] Export student list (CSV)
- [ ] Import students (CSV)

**Features**:
- Pagination (20 per page)
- Search by name/student number
- Filter by status
- View enrollment history
- Attendance summary per student

**Deliverables**:
- Complete student management system
- Data import/export functionality

### Phase 5: Faculty Management (Days 6-7)
**Objective**: Manage faculty members and assignments

**Tasks**:
- [ ] List faculty members
- [ ] Add faculty form
- [ ] Edit faculty details
- [ ] View faculty profile
- [ ] Assign courses to faculty
- [ ] View teaching schedule

**Features**:
- Department filter
- Course assignment interface
- Schedule display

**Deliverables**:
- Complete faculty management
- Course assignment system

### Phase 6: Course & Section Management (Days 7-8)
**Objective**: Manage courses and their offerings

**Tasks**:
- [ ] List courses with search
- [ ] Add/edit courses
- [ ] Create section offerings
- [ ] Assign faculty to sections
- [ ] Set schedule (day/time/room)
- [ ] View section roster

**Features**:
- Semester/year selection
- Capacity management
- Schedule conflict detection

**Deliverables**:
- Course catalog management
- Section scheduling system

### Phase 7: Enrollment System (Days 8-9)
**Objective**: Student enrollment in sections

**Tasks**:
- [ ] Enroll students in sections
- [ ] Drop/withdraw students
- [ ] View enrollment history
- [ ] Check prerequisites (optional)
- [ ] Capacity validation

**Features**:
- Prevent duplicate enrollments
- Check section capacity
- Enrollment date tracking

**Deliverables**:
- Functional enrollment system
- Capacity management

### Phase 8: Attendance Tracking (Days 9-10)
**Objective**: Daily attendance recording

**Tasks**:
- [ ] Record attendance by section/date
- [ ] Bulk attendance entry
- [ ] Edit attendance records
- [ ] Attendance reports
- [ ] Absence alerts

**Features**:
- Date picker for attendance
- Present/Absent/Late/Excused options
- Bulk update interface
- Attendance percentage calculation

**Deliverables**:
- Attendance recording system
- Reports and analytics

### Phase 9: Grade Management (Days 10-11)
**Objective**: Record and calculate grades

**Tasks**:
- [ ] Record grades by type
- [ ] Edit/delete grades
- [ ] Calculate weighted averages
- [ ] Generate transcripts
- [ ] Grade reports per section

**Features**:
- Multiple grade types (quiz, midterm, final, etc.)
- Weighted scoring
- Letter grade conversion
- GPA calculation

**Deliverables**:
- Grade recording system
- Transcript generation

### Phase 10: Additional Features (Days 11-12)
**Objective**: Complete remaining pages

**Tasks**:
- [ ] User management (admin only)
- [ ] Settings page
- [ ] Contact us form
- [ ] About us page
- [ ] Help/FAQ page

**Deliverables**:
- Complete website with all pages

### Phase 11: Testing & Polish (Days 12-13)
**Objective**: Ensure quality and fix issues

**Tasks**:
- [ ] Test all CRUD operations
- [ ] Validate data integrity
- [ ] Test edge cases
- [ ] Fix bugs
- [ ] Optimize slow queries
- [ ] Add error handling
- [ ] Security audit

**Deliverables**:
- Fully tested application
- Documentation

---

## 5. CODE EXAMPLES

### 5.1 Database Connection (config/database.php)
```php
<?php
/**
 * Database Configuration
 * XAMPP Default: host=localhost, user=root, password=''
 */

$host = 'localhost';
$dbname = 'university_sms';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false
        ]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}
?>
```

### 5.2 Authentication Check (includes/auth_check.php)
```php
<?php
/**
 * Authentication Middleware
 * Redirects to login if not authenticated
 */

session_start();

// Allow login page without authentication
$current_page = basename($_SERVER['PHP_SELF']);
$public_pages = ['login.php', 'logout.php'];

if (!in_array($current_page, $public_pages)) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Please login to continue'];
        header('Location: ../modules/auth/login.php');
        exit;
    }
    
    // Optional: Check if user is active
    require_once '../config/database.php';
    $stmt = $pdo->prepare("SELECT is_active FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user || !$user['is_active']) {
        session_destroy();
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Account disabled'];
        header('Location: ../modules/auth/login.php');
        exit;
    }
}
?>
```

### 5.3 Login Handler (modules/auth/login.php)
```php
<?php
require_once '../../config/database.php';
require_once '../../includes/flash.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        // Fetch user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Check if account is active
            if (!$user['is_active']) {
                $error = 'Account is disabled. Please contact administrator.';
            } else {
                // Regenerate session ID to prevent fixation
                session_regenerate_id(true);
                
                // Set session data
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['last_login'] = date('Y-m-d H:i:s');
                
                // Log login (optional)
                error_log("User logged in: {$user['username']} ({$user['role']})");
                
                // Redirect based on role
                $redirect = match($user['role']) {
                    'admin' => '../dashboard/index.php',
                    'faculty' => '../dashboard/index.php',
                    'student' => '../dashboard/index.php',
                    default => '../modules/auth/login.php'
                };
                
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Welcome back, ' . $user['first_name'] . '!'];
                header("Location: $redirect");
                exit;
            }
        } else {
            $error = 'Invalid username or password';
            // Log failed attempt (optional)
            error_log("Failed login attempt for username: $username");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University SMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        body {
            font-family: 'Lexend', sans-serif;
            background: linear-gradient(135deg, #e6eff6 0%, #ecf5fc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-container {
            background: var(--surface-container);
            border-radius: 32px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 16px 16px 32px #d1d9e6, -16px -16px 32px #ffffff;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .login-header h1 {
            color: var(--on-surface);
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }
        
        .login-header p {
            color: var(--on-surface-variant);
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: var(--on-surface-variant);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-control {
            width: 100%;
            padding: 16px;
            background: var(--surface-container);
            border: none;
            border-radius: 16px;
            font-family: 'Lexend', sans-serif;
            font-size: 16px;
            color: var(--on-surface);
            box-shadow: inset 6px 6px 12px #d1d9e6, inset -6px -6px 12px #ffffff;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            box-shadow: inset 8px 8px 16px #d1d9e6, inset -8px -8px 16px #ffffff, 0 0 0 2px var(--primary);
        }
        
        .form-control::placeholder {
            color: var(--on-surface-variant);
            opacity: 0.6;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--surface-container);
            border: none;
            border-radius: 16px;
            font-family: 'Lexend', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
            box-shadow: 8px 8px 16px #d1d9e6, -8px -8px 16px #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }
        
        .btn-login:hover {
            box-shadow: 6px 6px 12px #d1d9e6, -6px -6px 12px #ffffff;
        }
        
        .btn-login:active {
            box-shadow: inset 4px 4px 8px #d1d9e6, inset -4px -4px 8px #ffffff;
            transform: scale(0.98);
        }
        
        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .brand-logo {
            width: 64px;
            height: 64px;
            background: var(--surface-container);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 6px 6px 12px #d1d9e6, -6px -6px 12px #ffffff;
        }
        
        .brand-logo svg {
            width: 32px;
            height: 32px;
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="brand-logo">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                </svg>
            </div>
            <h1>University SMS</h1>
            <p>Sign in to your account</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required autocomplete="username">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn-login">Sign In</button>
        </form>
    </div>
</body>
</html>
```

### 5.4 Student List (modules/students/index.php)
```php
<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Search and filter
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT s.*, u.first_name, u.last_name, u.email 
          FROM students s 
          JOIN users u ON s.user_id = u.user_id 
          WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (s.student_number LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?)";
    $search_param = "%$search%";
    $params = [$search_param, $search_param, $search_param];
}

if ($status) {
    $query .= " AND s.status = ?";
    $params[] = $status;
}

$query .= " ORDER BY s.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Students</h1>
            <p class="text-gray-600 mt-1">Manage student records and enrollments</p>
        </div>
        <a href="create.php" class="btn-neo px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-shadow">
            + Add Student
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card-neo p-6 rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Students</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo count($students); ?></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card-neo p-6 rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Active</p>
                    <p class="text-2xl font-bold text-green-600">
                        <?php 
                        echo count(array_filter($students, fn($s) => $s['status'] === 'active'));
                        ?>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card-neo p-6 rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Inactive</p>
                    <p class="text-2xl font-bold text-orange-600">
                        <?php 
                        echo count(array_filter($students, fn($s) => $s['status'] === 'inactive'));
                        ?>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card-neo p-6 rounded-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Graduated</p>
                    <p class="text-2xl font-bold text-purple-600">
                        <?php 
                        echo count(array_filter($students, fn($s) => $s['status'] === 'graduated'));
                        ?>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card-neo p-6 rounded-2xl mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" class="form-control" placeholder="Search students..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="w-48">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="graduated" <?php echo $status === 'graduated' ? 'selected' : ''; ?>>Graduated</option>
                </select>
            </div>
            <button type="submit" class="btn-neo px-6 py-3 rounded-lg font-semibold">Search</button>
            <?php if ($search || $status): ?>
                <a href="index.php" class="btn-neo px-6 py-3 rounded-lg font-semibold">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Students Table -->
    <div class="card-neo rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Student ID</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Name</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Email</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Enrollment Date</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Status</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (count($students) > 0): ?>
                        <?php foreach ($students as $student): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6 font-medium text-gray-800"><?php echo htmlspecialchars($student['student_number']); ?></td>
                                <td class="py-4 px-6 text-gray-700"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                <td class="py-4 px-6 text-gray-600"><?php echo htmlspecialchars($student['email']); ?></td>
                                <td class="py-4 px-6 text-gray-600"><?php echo date('M d, Y', strtotime($student['enrollment_date'])); ?></td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php 
                                        echo match($student['status']) {
                                            'active' => 'bg-green-100 text-green-700',
                                            'inactive' => 'bg-orange-100 text-orange-700',
                                            'graduated' => 'bg-purple-100 text-purple-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    ?>">
                                        <?php echo ucfirst($student['status']); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex gap-2">
                                        <a href="view.php?id=<?php echo $student['student_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                                        <a href="edit.php?id=<?php echo $student['student_id']; ?>" class="text-yellow-600 hover:text-yellow-800 font-medium">Edit</a>
                                        <a href="delete.php?id=<?php echo $student['student_id']; ?>" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Are you sure?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                No students found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
```

---

## 6. SECURITY CONSIDERATIONS

### 6.1 SQL Injection Prevention
- **Always use prepared statements** with PDO
- Never concatenate user input into SQL queries
- Use parameterized queries for all database operations

### 6.2 Cross-Site Scripting (XSS) Prevention
- Escape all output with `htmlspecialchars()`
- Use `htmlentities()` for complex output
- Implement Content Security Policy (CSP) headers

### 6.3 Cross-Site Request Forgery (CSRF) Protection
- Generate CSRF tokens for all forms
- Validate tokens on form submission
- Store tokens in session

### 6.4 Password Security
- Use `password_hash()` with `PASSWORD_DEFAULT`
- Verify with `password_verify()`
- Never store plain text passwords
- Implement password strength requirements

### 6.5 Session Security
- Use `session_regenerate_id()` on login
- Set session cookie parameters (HttpOnly, Secure, SameSite)
- Implement session timeout
- Destroy session on logout

### 6.6 Input Validation
- Validate all user inputs server-side
- Use filter_var() for emails, URLs
- Implement length and format checks
- Sanitize file uploads

---

## 7. TESTING STRATEGY

### 7.1 Unit Testing
- Test database connection
- Test CRUD operations for each module
- Test authentication functions
- Test form validation

### 7.2 Integration Testing
- Test user workflows (enrollment, grading)
- Test role-based access
- Test data integrity across related tables

### 7.3 Security Testing
- SQL injection attempts
- XSS attack attempts
- CSRF vulnerability testing
- Session hijacking attempts

### 7.4 Performance Testing
- Load testing with multiple concurrent users
- Query optimization
- Index performance analysis

---

## 8. DEPLOYMENT CHECKLIST

### Pre-Deployment
- [ ] All code reviewed and tested
- [ ] Database optimized (indexes, query performance)
- [ ] Security audit completed
- [ ] Backup procedures documented
- [ ] Error logging configured
- [ ] Environment variables set (DB credentials)

### Deployment
- [ ] XAMPP installed on production server
- [ ] Database created and schema imported
- [ ] Sample data loaded
- [ ] File permissions set correctly
- [ ] PHP configuration optimized
- [ ] MySQL configuration optimized

### Post-Deployment
- [ ] Smoke testing completed
- [ ] User acceptance testing
- [ ] Documentation provided
- [ ] Training materials created
- [ ] Support procedures established

---

## 9. MAINTENANCE & SUPPORT

### Regular Tasks
- Daily: Backup database
- Weekly: Review error logs
- Monthly: Update dependencies
- Quarterly: Security audit
- Annually: Performance review

### Monitoring
- Database size and growth
- Query performance
- User activity logs
- Error rates
- System uptime

---

## 10. DOCUMENTATION

### Required Documentation
1. **User Manual**: For administrators, faculty, and students
2. **Technical Documentation**: For developers and maintainers
3. **Database Documentation**: Schema, relationships, queries
4. **API Documentation**: If web services are added
5. **Installation Guide**: Step-by-step setup instructions

---

## 11. TIMELINE SUMMARY

| Phase | Duration | Key Deliverables |
|-------|----------|------------------|
| Database & Core | 2 days | Database, auth system, UI framework |
| Authentication | 1 day | Login, logout, role management |
| Dashboard | 1 day | Statistics, overview |
| Student Management | 2 days | CRUD, import/export |
| Faculty Management | 2 days | CRUD, assignments |
| Course Management | 2 days | Courses, sections |
| Enrollment System | 1 day | Enrollment, capacity |
| Attendance | 2 days | Recording, reports |
| Grades | 2 days | Recording, transcripts |
| Additional Pages | 1 day | Contact, about, settings |
| Testing & Polish | 2 days | Bug fixes, optimization |
| **Total** | **18 days** | **Complete system** |

---

## 12. SUCCESS CRITERIA

### Functional Requirements
- [ ] All CRUD operations work correctly
- [ ] Database relationships enforced
- [ ] User authentication secure
- [ ] Role-based access control functional
- [ ] Data integrity maintained
- [ ] All modules integrated

### Non-Functional Requirements
- [ ] Code is simple and readable
- [ ] No frameworks or external libraries
- [ ] Neomorphic design consistent
- [ ] Performance acceptable
- [ ] Security best practices followed
- [ ] Documentation complete

### Academic Requirements
- [ ] Database properly normalized
- [ ] ER diagram implemented
- [ ] Complex queries demonstrated
- [ ] Transactions used where appropriate
- [ ] Indexes optimized
- [ ] Constraints enforced

---

## 13. CONCLUSION

This project plan provides a comprehensive roadmap for building a University School Management System using vanilla technologies with a focus on database implementation. The plan emphasizes:

1. **Database Excellence**: Proper normalization, constraints, and relationships
2. **Security**: Best practices for authentication, authorization, and data protection
3. **Simplicity**: Clean, readable code without framework complexity
4. **Consistency**: Uniform neomorphic design throughout
5. **Maintainability**: Well-structured code with documentation

The implementation follows a logical progression from database setup through authentication, core modules, and finishing with testing and polish. Each phase builds upon the previous, ensuring a solid foundation and systematic development.

**Estimated Total Time**: 18 working days  
**Technology**: Vanilla JS, HTML5, CSS3, PHP, MySQL (XAMPP)  
**Focus**: Database implementation and robust CRUD operations  
**Design**: Neomorphic UI matching existing prototypes  

---

*Document Version: 1.0*  
*Last Updated: April 27, 2026*  
*Course: CSE 425 - Database Management*