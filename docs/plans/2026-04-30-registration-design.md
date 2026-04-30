# Registration System Design

## Overview
A multi-step registration flow for Students and Faculty members, following the existing neomorphic design system and PHP/MySQL stack.

## Architecture
- **Choice Page (`modules/auth/register_choice.php`)**: Two large neomorphic cards for selecting "Student" or "Teacher".
- **Student Form (`modules/auth/register_student.php`)**: Specific form for student data.
- **Teacher Form (`modules/auth/register_teacher.php`)**: Specific form for faculty data.
- **Backend Handler (`modules/auth/register_process.php`)**: Processes submissions, handles transactions for `users` + role table.

## Data Schema Mapping
### Users Table (Common)
- `username`, `email`, `password` (hashed), `role`, `first_name`, `last_name`

### Students Table
- `student_number` (generated or manual?), `date_of_birth`, `phone`, `address`, `enrollment_date`

### Faculty Table
- `employee_number` (generated or manual?), `department`, `title`, `hire_date`, `office_location`

## UI/UX
- **Neomorphic Design**: Consistent with `login.php`.
- **Feedback**: Error messages for duplicate email/username, success message on completion.

## Security
- Password hashing with `password_hash()`.
- Prepared statements for all DB interactions.
- Input sanitization.

## Implementation Steps
1. Create `register_choice.php`.
2. Create `register_student.php` and `register_teacher.php` based on templates.
3. Implement `register_process.php` with transaction support.
4. Add "Register" link to `login.php`.
