# Design Doc: Restricting Student Login and Removing Login ID

**Date**: 2026-05-06
**Topic**: Authentication & Student Schema Simplification

## Problem Statement
The current student login system is too permissive, allowing students to log in with their Email, Student ID (`student_number`), Username, or Student Login ID (`login_id`). Additionally, the "Student Login ID" field is redundant and creates confusion alongside the "Student ID" (`student_number`).

## Objective
- Restrict student login to **Username** and **Password** only.
- Remove the **Student Login ID** (`login_id`) column from the database and all UI references.
- Retain the **Student ID** (`student_number`) for display and administrative purposes, but disable it as a login identifier.

## Proposed Changes

### 1. Database Layer
- **File**: `database.sql`
- **Actions**:
    - Remove `login_id VARCHAR(50) UNIQUE NULL,` from the `students` table definition.
    - Remove `INDEX idx_login_id (login_id),`.
- **Migration**: Drop the `login_id` column from the live `students` table.

### 2. Authentication Layer
- **File**: `modules/auth/login_student.php`
- **Actions**:
    - Update the SQL query to only check `u.username`.
    - Update UI labels and placeholders:
        - "Email or Student ID" -> "Username"
        - "Email, ID or Username" -> "Username"
    - Update error messages to reflect the username-only policy.

### 3. Student Management (Admin)
- **Files**: `modules/students/create.php`, `modules/students/edit.php`, `modules/students/view.php`, `modules/students/index.php`
- **Actions**:
    - Remove all input fields, labels, and display rows related to "Student Login ID".
    - Update `INSERT` and `UPDATE` queries to stop processing the `login_id` column.
    - Update the "Add Student" error handling to stop checking for `login_id` uniqueness.

### 4. Student Dashboard
- **File**: `modules/dashboard/index.php`
- **Actions**:
    - Remove "Login ID" from the student profile information display.

## Verification Plan

### Manual Verification
- **Login Tests**:
    - Attempt login with valid username/password (Success expected).
    - Attempt login with email (Failure expected).
    - Attempt login with Student ID (`student_number`) (Failure expected).
- **CRUD Tests**:
    - Create a new student (Verify no Login ID field).
    - Edit an existing student (Verify no Login ID field).
    - View student profile (Verify Login ID is gone).
