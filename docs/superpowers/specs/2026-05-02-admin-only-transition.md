# Design Spec: Transition to Hardcoded Admin and Student-only System

**Date:** 2026-05-02
**Status:** Approved
**Topic:** Removing teacher functionality and implementing a single hardcoded admin.

## 1. Overview
The project currently supports three roles: Admin, Faculty (Teacher), and Student. The goal is to remove all Faculty-related features, logic, and UI, and replace the database-driven Admin login with a single, hardcoded account. Students will remain database-driven.

## 2. Requirements
- **Single Hardcoded Admin**: Username `admin`, Password `admin123`.
- **Remove Teachers**: Delete all code, modules, and database structures related to "Faculty" or "Teachers".
- **Student Preservation**: Students must still be able to register, login, and use their dashboard.
- **Registration Flow**: Remove the choice between Student/Teacher. Redirect "Create Account" directly to Student registration.

## 3. Architecture Changes

### 3.1 Authentication (`modules/auth/login.php`)
- Intercept the POST request.
- If `login_input === 'admin'` and `password === 'admin123'`, manually set `$_SESSION` variables:
    - `user_id = 0` (or a special constant)
    - `username = 'admin'`
    - `role = 'admin'`
    - `full_name = 'System Administrator'`
- Otherwise, proceed with the existing database query for students.

### 3.2 Registration (`modules/auth/`)
- Delete `register_teacher.php`.
- Modify `register_choice.php` to immediately redirect to `register_student.php` (or replace it with a direct link).
- Cleanup `register_process.php` to remove the `faculty` role handling.

### 3.3 Admin Modules (`modules/`)
- Delete the `modules/faculty/` directory.
- Update `includes/navbar.php` to remove the Faculty management link.
- Update `modules/dashboard/index.php` to remove Faculty count and stat cards.

### 3.4 Permissions (`includes/auth_check.php`)
- Ensure `checkRole` logic remains functional but only encounters 'admin' and 'student' roles.
- Update any files that use `checkRole(['admin', 'faculty'])` to just `checkRole(['admin'])`.

### 3.5 Database (`database.sql` & Live DB)
- Drop the `faculty` table.
- Remove the `faculty` option from the `users.role` ENUM (optional but cleaner).
- Delete the existing `admin` user from the `users` table to avoid confusion.

## 4. Implementation Plan (Summary)
1.  **Backup/Safety**: Ensure current state is committed.
2.  **Auth Update**: Implement hardcoded admin in `login.php`.
3.  **UI Cleanup**: Remove Faculty links and stats from Navbar and Dashboard.
4.  **Module Deletion**: Delete `modules/faculty/` and `register_teacher.php`.
5.  **Registration Refactor**: Streamline to Student-only registration.
6.  **Code Scouring**: Search for all instances of 'faculty' or 'teacher' and remove/refactor.
7.  **Database Update**: Drop table and clean up records.
8.  **Verification**: Test Admin login, Student registration/login, and ensure no broken links.
