# Registration System Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Implement a multi-step registration flow for Students and Faculty with neomorphic UI.

**Architecture:** A branching flow starting from a role selection page (`register_choice.php`), leading to role-specific forms (`register_student.php`, `register_teacher.php`), and a unified backend processor (`register_process.php`).

**Tech Stack:** PHP 8.2 (XAMPP), MySQL, Tailwind CSS, Neomorphic UI.

---

### Task 1: Create Role Selection Page

**Files:**
- Create: `modules/auth/register_choice.php`

**Step 1: Write the implementation**
Create a page with two large neomorphic cards for "Student" and "Teacher" selection.

**Step 2: Verify visually**
Since I can't see the browser, I'll check if the file exists and contains the correct links.
Run: `ls modules/auth/register_choice.php`

**Step 3: Commit**
```bash
git add modules/auth/register_choice.php
git commit -m "feat: add registration role choice page"
```

### Task 2: Create Student Registration Form

**Files:**
- Create: `modules/auth/register_student.php`

**Step 1: Write the implementation**
Use the neomorphic registration template to create a form with student-specific fields: username, email, password, first_name, last_name, student_number, date_of_birth, phone, address.

**Step 2: Commit**
```bash
git add modules/auth/register_student.php
git commit -m "feat: add student registration form"
```

### Task 3: Create Teacher Registration Form

**Files:**
- Create: `modules/auth/register_teacher.php`

**Step 1: Write the implementation**
Create a form with teacher-specific fields: username, email, password, first_name, last_name, employee_number, department, title, hire_date, office_location.

**Step 2: Commit**
```bash
git add modules/auth/register_teacher.php
git commit -m "feat: add teacher registration form"
```

### Task 4: Implement Registration Backend Processor

**Files:**
- Create: `modules/auth/register_process.php`

**Step 1: Write the implementation**
Handle POST data, validate inputs, hash password, and perform a database transaction to insert into `users` and then either `students` or `faculty`.

**Step 2: Create a test script to verify registration**
Create `tests/test_registration.php` to simulate a POST request and check DB records.

**Step 3: Run the test**
Run: `/opt/lampp/bin/php tests/test_registration.php`
Expected: "Student registered successfully" and "Teacher registered successfully"

**Step 4: Commit**
```bash
git add modules/auth/register_process.php tests/test_registration.php
git commit -m "feat: implement registration processing logic"
```

### Task 5: Link Registration to Login Page

**Files:**
- Modify: `modules/auth/login.php`

**Step 1: Add the "Register" link**
Add a link to `register_choice.php` below the "Sign In" button or next to "Forgot Password?".

**Step 2: Commit**
```bash
git add modules/auth/login.php
git commit -m "feat: link registration choice to login page"
```
