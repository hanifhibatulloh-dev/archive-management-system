# Archive Management System

A web-based archive management system developed as an academic Software Engineering project.

The application is designed to support digital archive management through an integrated web-based system, including archive records, classifications, storage locations, employee data, user management, and reporting.

> **Academic Project Disclaimer**  
> This project was developed solely for academic, educational, and portfolio purposes. It is not an official application, website, or information system of any government institution or organization.

---

## Overview

Archive Management System is a database-driven web application developed to implement Software Engineering, system analysis, database design, authentication, role-based access control, and CRUD operations in a practical project.

The system provides centralized management for archive-related information and allows users with different access levels to manage records according to their assigned roles.

---

## Key Features

- Secure user authentication
- Role-based access control
- Archive data management
- Archive category management
- Archive classification management
- Storage location management
- Employee data management
- User account management
- Archive status management
- Search and filtering
- Dashboard statistics
- Archive reporting
- Printable reports
- Responsive web interface

---

## User Roles

The application supports three user roles:

### Admin

Admin users have the highest level of access and can:

- Manage archive records
- Manage archive categories
- Manage archive classifications
- Manage storage locations
- Manage employee data
- Manage user accounts
- View reports
- Delete records where permitted

### Operator

Operator users can:

- Add archive records
- Edit archive records
- Manage selected master data
- View archive information
- Access reports

### Viewer

Viewer users have limited access and are primarily intended to view information available within the system.

---

## Main Modules

### Dashboard

The dashboard provides an overview of system information such as:

- Total archive records
- Active archives
- Total employees
- Total users
- Recent archive records
- Archive distribution by category

### Archive Management

The archive module allows users to:

- Add archive records
- Edit archive records
- Delete archive records
- Assign archive categories
- Assign classifications
- Assign storage locations
- Assign responsible employees
- Define archive status
- Add tags and descriptions

### Archive Categories

Manages different archive categories used throughout the system.

### Archive Classification

Provides structured classification of archive records.

### Storage Management

Manages physical and digital archive storage locations.

### Employee Management

Manages employee information associated with archive responsibilities.

### User Management

Allows administrators to manage:

- Username
- Password
- Full name
- Email
- Role
- Account status

### Reports

The reporting module provides archive reports with filters such as:

- Archive status
- Archive category
- Date range

Reports can also be printed directly from the application.

---

## Technologies

The project was developed using:

- PHP
- MySQL / MariaDB
- PDO
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Font Awesome
- DataTables
- XAMPP

---

## Database

The application uses a relational MySQL database.

Main database tables include:

- `Users`
- `Pegawai`
- `Jenis_Arsip`
- `Klasifikasi_Arsip`
- `Penyimpanan`
- `Arsip`
- `Pengguna`
- `Proses_Pengarsipan`
- `Roles`
- `Audit_Log`

The database also uses relational connections between archive records, archive categories, classifications, storage locations, and employees.

The SQL database file is stored in:

```text
sql/archive_management_system.sql
```

---

## Project Structure

```text
Archive_Management_System/
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   ├── images/
│   │   └── archive-bg.jpg
│   │
│   └── js/
│       └── script.js
│
├── config/
│   ├── config.php
│   └── database.php
│
├── includes/
│   ├── header.php
│   ├── sidebar.php
│   ├── topbar.php
│   └── footer.php
│
├── sql/
│   └── archive_management_system.sql
│
├── screenshots/
│   ├── login.png
│   ├── dashboard.png
│   ├── archive-management.png
│   ├── storage-management.png
│   └── report.png
│
├── index.php
├── login.php
├── logout.php
├── arsip.php
├── jenis_arsip.php
├── klasifikasi.php
├── penyimpanan.php
├── pegawai.php
├── users.php
├── laporan.php
│
├── .gitignore
├── LICENSE
└── README.md
```

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/USERNAME/archive-management-system.git
```

Alternatively, download the repository as a ZIP file.

---

### 2. Move the Project to XAMPP

Place the project directory inside:

```text
C:\xampp\htdocs\
```

Example:

```text
C:\xampp\htdocs\Archive_Management_System
```

---

### 3. Start XAMPP

Open the XAMPP Control Panel and start:

```text
Apache
MySQL
```

---

### 4. Create the Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a database named:

```text
archive_management_system
```

---

### 5. Import the Database

Select the database:

```text
archive_management_system
```

Then import:

```text
sql/archive_management_system.sql
```

---

### 6. Configure Database Connection

Open:

```text
config/database.php
```

Make sure the database configuration matches your local environment.

Example:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'archive_management_system');
```

---

### 7. Configure Base URL

Open:

```text
config/config.php
```

Set the base URL according to the project folder name:

```php
define('BASE_URL', '/Archive_Management_System/');
```

If the folder name is changed, update `BASE_URL` accordingly.

---

### 8. Run the Application

Open:

```text
http://localhost/Archive_Management_System/
```

or directly:

```text
http://localhost/Archive_Management_System/login.php
```

---

---

## Screenshots

The following screenshots demonstrate the main interfaces and features available in the Archive Management System.

### Login Page

The login page provides authentication access for registered users before entering the Archive Management System.

![Login Page](screenshots/login.jpg)

---

### Dashboard

The dashboard provides an overview of archive information and quick access to the main modules of the system.

![Dashboard](screenshots/dashboard.png)

---

### Archive Management

The Archive Management page is used to view, add, edit, and manage archive records stored in the system.

![Archive Management](screenshots/manajemen_arsip.png)

---

### Employee Management

The Employee Management page displays employee data associated with archive management activities.

![Employee Management](screenshots/pegawai.png)

---

### Add Employee

The Add Employee page allows authorized users to register new employee information into the system.

![Add Employee](screenshots/tambah_pegawai.png)

---

### Archive Users

The Archive Users page manages internal and external users associated with archive access and archive-related services.

![Archive Users](screenshots/pengguna.png)

---

### Archive Categories

The Archive Categories page is used to manage archive types or categories used within archive records.

![Archive Categories](screenshots/jenis_arsip.png)

---

### Archive Classification

The Archive Classification page provides structured classification information used to organize archive records.

![Archive Classification](screenshots/klasifikasi_arsip.png)

---

### Storage Locations

The Storage Locations page manages physical and server-based archive storage information.

![Storage Locations](screenshots/lokasi_penyimpanan.png)

---

### User Management

The User Management page allows administrators to manage system accounts, user roles, account status, and other account information.

![User Management](screenshots/manajemen_user.png)

---

### Roles & Access Rights

The Roles & Access Rights page displays system roles and the access permissions associated with each role.

![Roles & Access Rights](screenshots/roles_hak_akses.png)

---

### Audit Log

The Audit Log page records selected data changes to support system monitoring and activity traceability.

![Audit Log](screenshots/audit_log.png)

---

### Archive Reports

The Archive Reports page provides reporting and filtering features for reviewing archive information.

![Archive Reports](screenshots/laporan_arsip.png)

---

---

## Software Engineering Implementation

This project demonstrates the implementation of several Software Engineering concepts, including:

- Requirement analysis
- System design
- Relational database design
- Entity Relationship Diagram
- Authentication
- Authorization
- Role-Based Access Control
- CRUD operations
- Database integration
- Modular application structure
- Data filtering
- Reporting
- User interface development
- Technical documentation

---

## Security Considerations

This repository is intended primarily for academic and portfolio use.

Before using the application in a production environment:

- Use strong passwords
- Change all sample user credentials
- Do not publish real institutional data
- Do not expose database credentials
- Validate and sanitize user input
- Apply proper authorization checks
- Protect sensitive configuration files
- Use HTTPS in production
- Disable detailed database error messages
- Keep PHP and database software updated

---

## Data Privacy

The public repository should only contain sample or dummy data.

Do not upload:

- Real employee information
- Confidential documents
- Personal identification information
- Real archive documents
- Institutional credentials
- Production database backups
- Passwords or API keys

---

## Academic Context

This system was developed as a Software Engineering course project to demonstrate practical implementation of software development and information system concepts.

The application is intended to show experience in:

- Web application development
- Database design
- Information system development
- Backend development
- System integration
- Software documentation
- Problem solving
- Software Engineering practices

---

## Author

**Muhammad Hanif Hibatulloh**

Computer Science Student  
Universitas Jenderal Achmad Yani

### Areas of Interest

- Software Engineering
- Artificial Intelligence
- Machine Learning
- Web Development
- Database Systems

---

## Disclaimer

This repository is an independent academic project created for educational and portfolio purposes.

It is **not an official application, website, information system, or digital service of any government institution, public agency, company, or organization**.

The project does not claim official affiliation, endorsement, authorization, or representation of any institution.

Any organizational context used during the original academic development process was used solely as part of a Software Engineering learning activity.

All publicly shared data should consist only of fictional, sample, or anonymized information.

---

## License

This project is provided for educational and portfolio purposes.

Please review the `LICENSE` file before using, modifying, or redistributing the source code.
