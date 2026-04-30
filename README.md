# Student Management System

[![Database](https://img.shields.io/badge/Database-MySQL-blue.svg)](https://www.mysql.com/)
[![Language](https://img.shields.io/badge/Language-PHP-777bb4.svg)](https://www.php.net/)
[![Design](https://img.shields.io/badge/Design-Neomorphism-e6eff6.svg)](#academic-tactility)

The Student Management System is a comprehensive, database-focused School Management System (SMS) designed for academic institutions. Built as part of the **CSE 425 - Database Management** course, it prioritizes robust database architecture, clean vanilla implementation, and a cutting-edge "Academic Tactility" neomorphic user interface.

![ER Diagram](university_er_diagram_v4.png)

## Key Features

The system is organized into specialized modules designed to handle the complex workflows of a modern university:

- **Comprehensive Dashboards**: Role-specific overviews for Administrators, Faculty, and Students featuring real-time statistics.
- **Student Management**: Full lifecycle tracking from registration to graduation, including detailed profiles and enrollment history.
- **Faculty Portal**: Management of academic staff, department assignments, and teaching schedules.
- **Course & Section Catalog**: Dynamic management of course offerings, credits, and per-semester section scheduling.
- **Enrollment System**: Intelligent student-to-section mapping with capacity validation and conflict detection.
- **Attendance Tracking**: Daily, bulk-entry attendance recording with automated percentage calculations.
- **Gradebook & Transcripts**: Weighted assessment tracking (quizzes, midterms, finals) and automated transcript generation.
- **Notification Engine**: System-wide alerts for important academic updates and status changes.

---

### Core Principles

- **Dual Shadow Dynamics**: Using light and shadow (135° source) to create **Raised (Convex)** elements for interactivity and **Sunken (Concave)** elements for data input.
- **Lexend Typography**: Utilizing the Lexend typeface, specifically engineered to improve reading proficiency and reduce visual stress during long administrative sessions.
- **Physical Feedback**: Interactive elements provide tactile feedback by transitioning from raised to sunken states upon interaction.
- **Softened Geometry**: A strict "no sharp corners" rule ensures a soft, molded aesthetic that feels premium and modern.

---

## Tech Stack

- **Backend**: PHP 8.x (Vanilla)
- **Database**: MySQL (InnoDB Engine, 3NF Normalization)
- **Frontend**: HTML5, CSS3 (Custom Neomorphic Framework), Vanilla JavaScript
- **Environment**: XAMPP / Apache

---

## Project Structure

```text
/
├── config/             # Database connection & PDO configuration
├── css/                # Neomorphic "Academic Tactility" stylesheets
├── includes/           # Reusable components (Header, Footer, Navbar, Auth)
├── js/                 # Vanilla JS utilities and component logic
├── modules/            # core functional modules
│   ├── attendance/     # Attendance tracking & reports
│   ├── auth/           # Secure login, logout, & registration
│   ├── courses/        # Catalog management
│   ├── dashboard/      # Statistics & role-based overviews
│   ├── enrollments/    # Student section mapping
│   ├── faculty/        # Staff management
│   ├── grades/         # Assessment & transcripts
│   └── students/       # Student records (CRUD)
├── student_management_system/ # UI Prototypes & Screen Mockups
└── university_er_diagram_v4.png      # Database Schema Documentation
```

---

## Installation & Setup (XAMPP on Windows)

Follow these steps to get the system running on your local machine using XAMPP.

### 1. Prerequisites

- **XAMPP**: Download and install from [apachefriends.org](https://www.apachefriends.org/).
- **Git**: (Optional) For cloning the repository.

### 2. Environment Setup

1.  **Start XAMPP**: Open the **XAMPP Control Panel** and click **Start** for both **Apache** and **MySQL**.
2.  **Place Project Files**:
    - Navigate to your XAMPP installation directory (usually `C:\xampp\htdocs`).
    - Create a folder named `dbsm`.
    - Copy all project files into `C:\xampp\htdocs\dbsm`.
    - _Alternatively, if using Git:_
      ```bash
      cd C:\xampp\htdocs
      git clone https://github.com/noshinnawal119-bot/dbsm.git
      ```

### 3. Database Configuration

1.  **Open phpMyAdmin**: Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your browser.
2.  **Create Database**:
    - Click on **New** in the left sidebar.
    - Database name: `university_sms`
    - Collation: `utf8mb4_general_ci`
    - Click **Create**.
3.  **Import Schema**:
    - Select the `university_sms` database.
    - Click the **Import** tab at the top.
    - Click **Choose File** and select `database.sql` from the project root.
    - Scroll to the bottom and click **Go**.

### 4. Run the Application

- Open your browser and navigate to: [**http://localhost/dbsm**](http://localhost/dbsm)

---

## Default Credentials

The system comes pre-loaded with sample accounts for testing:

| Role              | Username  | Password     |
| :---------------- | :-------- | :----------- |
| **Administrator** | `admin`   | `admin123`   |
| **Faculty**       | `jdoe`    | `faculty123` |
| **Student**       | `sasmith` | `student123` |

---
