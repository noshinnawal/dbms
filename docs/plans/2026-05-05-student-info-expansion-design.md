# Design Doc: Student Info Expansion
Date: 2026-05-05
Topic: student-info-expansion

## Goal
Expand the student information stored in the database and displayed across the site (Admin and Student panels) to include parental details and residential information.

## Proposed Changes

### 1. Database Schema
Update the `students` table to include new fields and rename existing ones for clarity.

```sql
ALTER TABLE students 
CHANGE COLUMN address present_address TEXT,
ADD COLUMN permanent_address TEXT AFTER present_address,
ADD COLUMN father_name VARCHAR(100) AFTER student_number,
ADD COLUMN mother_name VARCHAR(100) AFTER father_name,
ADD COLUMN father_occupation VARCHAR(100) AFTER mother_name,
ADD COLUMN mother_occupation VARCHAR(100) AFTER father_occupation;
```

### 2. Admin Panel Updates
- **`modules/students/create.php`**: 
    - Update SQL `INSERT` statement to include new fields.
    - Add form fields for Father's Name, Mother's Name, Occupations, and Permanent Address.
    - Group fields into neomorphic cards: Account, Core Profile, Parental Info, Residential Details.
- **`modules/students/edit.php`**: 
    - Update SQL `SELECT` and `UPDATE` statements.
    - Mirror the layout changes from the create page.
- **`modules/students/view.php`**: 
    - Display the new fields in a structured, neomorphic grid.

### 3. Student Panel Updates
- **`modules/dashboard/index.php`**: 
    - Update SQL `SELECT` statement for student profile.
    - Add UI cards to display Parental Information and both addresses.

### 4. Aesthetics
- Use Material Symbols for new sections.
- Maintain neomorphic design principles (inset shadows for inputs, raised shadows for cards).

## Verification Plan
- Manually test student creation with all fields.
- Verify editing preserves and updates all fields.
- Check admin view for correct data display.
- Log in as a student to verify the dashboard shows the expanded profile.
