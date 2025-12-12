# 📝 Task Management System (PHP & MySQL)

A **role-based Task Management System** developed using **PHP, MySQL, and Tailwind CSS** as part of the **CSE415: Web Engineering Lab** course.
This project demonstrates **authentication, authorization, and full CRUD operations** with separate **Admin** and **User** dashboards.

---

## 📌 Project Overview

The Task Management System allows admins to create and assign tasks to users, while users can manage and update their assigned tasks.
The system uses **session-based authentication**, **role-based access control**, and a **MySQL database** to ensure secure and dynamic functionality.

---

## 🚀 Features

### 🔐 Authentication & Authorization

* Secure login and registration system
* Password hashing using `password_hash()`
* Session-based authentication
* Role-based access (Admin & User)

### 👨‍💼 Admin Features

* Admin dashboard overview
* Create, edit, delete tasks
* Assign tasks to users
* View and manage all users
* Edit user details (username, email, role)
* Delete users
* Manage task status (Pending, In Progress, Completed)

### 👤 User Features

* User dashboard with personalized greeting
* View assigned tasks categorized by status:

  * Pending
  * In Progress
  * Completed
* Edit task details and update task status
* Cannot modify completed tasks (intentional design)

### 🛠 CRUD Operations

* **Create:** Users & Tasks
* **Read:** Tasks and Users
* **Update:** Task status, task details, user information
* **Delete:** Tasks and Users

---

## 🧰 Technologies Used

* **Frontend:**

  * HTML5
  * Tailwind CSS
  * Font Awesome

* **Backend:**

  * PHP (Procedural + MySQLi)

* **Database:**

  * MySQL

* **Security Practices:**

  * Prepared statements
  * Password hashing
  * Session validation
  * Role-based route protection

---

## 🗂 Project Structure

```
task-manager/
│
├── admin/
│   ├── dashboard.php
│   ├── add_task.php
│   ├── edit_task.php
│   ├── delete_task.php
│   ├── manage_tasks.php
│   ├── manage_users.php
│   ├── edit_user.php
│   └── delete_user.php
│
├── user/
│   ├── dashboard.php
│   └── edit_task.php
│
├── includes/
│   ├── db.php
│   ├── navbar.php
│   └── footer.php
│
├── login.php
├── register.php
├── logout.php
├── index.php
└── README.md
```

---

## 🗄 Database Structure (Overview)

### `users` table

| Column   | Type              |
| -------- | ----------------- |
| id       | INT (PK)          |
| name     | VARCHAR           |
| username | VARCHAR           |
| email    | VARCHAR           |
| password | VARCHAR           |
| role     | ENUM(admin, user) |

### `tasks` table

| Column      | Type                                  |
| ----------- | ------------------------------------- |
| id          | INT (PK)                              |
| title       | VARCHAR                               |
| description | TEXT                                  |
| due_date    | DATE                                  |
| status      | ENUM(pending, in-progress, completed) |
| user_id     | INT (FK)                              |

---

## ⚙️ Setup Instructions

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/task-management-system.git
   ```

2. Import the database:

   * Create a MySQL database
   * Import the provided `.sql` file (if available)

3. Configure database connection:

   * Update `includes/db.php` with your database credentials

4. Run the project:

   * Place the project in `htdocs` (XAMPP) or `www` (WAMP)
   * Start Apache & MySQL
   * Open browser and go to:

     ```
     https://mominulislam.com/diu/6/
     ```



## 🎓 Academic Purpose

This project was developed for **educational purposes** to fulfill the requirements of:

> **CSE415 – Web Engineering Lab**
> Department of Computer Science & Engineering
> Daffodil International University (DIU)


## 👤 Author

**Mominul Islam**

* ID: 192-15-13156
* Department of CSE
* Daffodil International University


## 📜 License

This project is for **academic and learning purposes only**.
You are free to modify and extend it for educational use.

