# MatuTuro-A-Web-Based-Tutor-Finder-System-for-Bulacan-State-University

# User Management System

This is a simple User Management System built using PHP and MySQL. It includes features for user registration, login, and role-based access control. Admin users can manage other users through a dashboard, and regular users are directed to their respective dashboards after logging in.

## Table of Contents

1. [Overview](#overview)
2. [Features](#features)
3. [Technologies Used](#technologies-used)
4. [Installation](#installation)
5. [Usage](#usage)
6. [Database Structure](#database-structure)
7. [License](#license)

## Overview

This project allows users to register, log in, and access dashboards based on their role (Admin, Tutor, Student). The system uses PHP for the backend and MySQL for data storage. Passwords are hashed for security, and prepared statements are used to prevent SQL injection attacks.

### Key Features

- **User Registration**: Users can create accounts with a username, email, and password.
- **Role-based Authentication**: Users have roles (`admin`, `tutor`, `student`), and each role has a corresponding dashboard.
- **Secure Login**: Passwords are hashed using `password_hash()` for security.
- **Admin Dashboard**: Admin users can view and manage other users.
- **Session Management**: Sessions are used to manage login state, and users are logged out properly.
- **Security**: Prepared statements and password hashing ensure security against SQL injection and password theft.

## Features

- **Registration Page**: Users can create a new account by providing a username, email, and password.
- **Login Page**: Registered users can log in to the system, and based on their role, they are redirected to different dashboards.
- **Admin Dashboard**: Admin users can access the admin dashboard, view users, and manage the system.
- **User Dashboard**: Regular users (tutors, students) are redirected to their respective dashboards upon login.
- **Logout**: Users can log out, and their session is destroyed to prevent unauthorized access.

## Technologies Used

- **PHP**: Server-side scripting language for the backend logic.
- **MySQL**: Relational database management system to store user data.
- **HTML/CSS**: For structuring and styling the frontend.
- **Bootstrap**: For responsive, mobile-first design.
- **JavaScript**: For form validation and dynamic interactions.
- **Password Hashing**: `password_hash()` and `password_verify()` functions for secure password storage.

## Installation

### Prerequisites

1. **PHP**: Make sure PHP is installed on your system (version 7.4 or higher).
2. **MySQL**: You should have a MySQL or MariaDB database running.
3. **XAMPP/WAMP**: For local development, you can use XAMPP or WAMP to manage Apache and MySQL.

### Steps to Install

1. **Clone the repository**:
