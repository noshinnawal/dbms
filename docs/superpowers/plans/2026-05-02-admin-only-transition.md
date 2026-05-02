# Admin-Only Transition Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Remove all teacher-related functionality and implement a single hardcoded admin account while preserving student features.

**Architecture:** 
- Intercept login attempts in `login.php` for hardcoded credentials.
- Streamline registration by removing the role choice and defaulting to students.
- Remove all "Faculty" management modules and UI elements.
- Cleanup database by dropping the `faculty` table and removing the database-backed admin.

**Tech Stack:** PHP, MySQL, TailwindCSS (for UI cleanup).

---

### Task 1: Hardcoded Admin Login

**Files:**
- Modify: `modules/auth/login.php`

- [ ] **Step 1: Implement hardcoded admin check**

```php
// In modules/auth/login.php, before the database query
if ($login_input === 'admin' && $password === 'admin123') {
    $_SESSION['user_id'] = 0; // Constant for hardcoded admin
    $_SESSION['username'] = 'admin';
    $_SESSION['role'] = 'admin';
    $_SESSION['full_name'] = 'System Administrator';
    
    header("Location: ../dashboard/index.php");
    exit;
}
```

- [ ] **Step 2: Verify Admin Login**

Test: Attempt to login with `admin` / `admin123`.
Expected: Successful redirect to dashboard.

- [ ] **Step 3: Commit**

```bash
git add modules/auth/login.php
git commit -m "feat: implement hardcoded admin login"
```

### Task 2: Remove Faculty from Navbar

**Files:**
- Modify: `includes/navbar.php`

- [ ] **Step 1: Remove Faculty link**

Remove the following block:
```php
<?php if ($role === 'admin'): ?>
<a href="../faculty/index.php" ...>
    <span class="material-symbols-outlined">badge</span>
    <span class="font-medium">Faculty</span>
</a>
<?php endif; ?>
```

- [ ] **Step 2: Verify Navbar**

Check the sidebar in the dashboard as an admin.
Expected: "Faculty" link is gone.

- [ ] **Step 3: Commit**

```bash
git add includes/navbar.php
git commit -m "ui: remove faculty link from navbar"
```

### Task 3: Remove Faculty from Dashboard

**Files:**
- Modify: `modules/dashboard/index.php`

- [ ] **Step 1: Remove faculty stats query**

```php
// Remove this:
$stats['faculty'] = $pdo->query("SELECT COUNT(*) FROM faculty WHERE status = 'active'")->fetchColumn();
```

- [ ] **Step 2: Remove faculty stat card from UI**

Remove the card HTML for "Total Faculty".

- [ ] **Step 3: Verify Dashboard**

Check the dashboard as an admin.
Expected: Only Student and Course stats are visible.

- [ ] **Step 4: Commit**

```bash
git add modules/dashboard/index.php
git commit -m "ui: remove faculty stats from dashboard"
```

### Task 4: Streamline Registration

**Files:**
- Delete: `modules/auth/register_teacher.php`
- Modify: `modules/auth/register_choice.php`
- Modify: `modules/auth/register_process.php`

- [ ] **Step 1: Delete teacher registration form**

Run: `rm modules/auth/register_teacher.php`

- [ ] **Step 2: Redirect Choice Page to Student Registration**

In `register_choice.php`, instead of showing cards, immediately redirect:
```php
header("Location: register_student.php");
exit;
```

- [ ] **Step 3: Cleanup registration process logic**

Remove `faculty` role handling from `register_process.php`.

- [ ] **Step 4: Verify Student Registration**

Test: Click "Create Account" on login page.
Expected: Immediate redirect to Student Registration form. Complete registration and verify it still works.

- [ ] **Step 5: Commit**

```bash
git add modules/auth/register_choice.php modules/auth/register_process.php
git rm modules/auth/register_teacher.php
git commit -m "refactor: streamline registration to student-only"
```

### Task 5: Delete Faculty Module and Cleanup Roles

**Files:**
- Delete: `modules/faculty/` (directory)
- Modify: `modules/courses/index.php`, `modules/enrollments/index.php`, etc.

- [ ] **Step 1: Delete faculty module directory**

Run: `rm -rf modules/faculty/`

- [ ] **Step 2: Update Role Checks**

Search and replace `checkRole(['admin', 'faculty'])` with `checkRole(['admin'])`.

- [ ] **Step 3: Commit**

```bash
git add .
git commit -m "refactor: remove faculty module and update role permissions"
```

### Task 6: Database Cleanup

**Files:**
- Modify: `database.sql` (to reflect changes for future setups)

- [ ] **Step 1: Drop faculty table**

SQL: `DROP TABLE faculty;`

- [ ] **Step 2: Remove database-backed admin**

SQL: `DELETE FROM users WHERE username = 'admin';`

- [ ] **Step 3: Commit database schema update**

Update `database.sql` to remove faculty table definition and admin user insertion.

- [ ] **Step 4: Commit**

```bash
git add database.sql
git commit -m "db: remove faculty table and cleanup users"
```
