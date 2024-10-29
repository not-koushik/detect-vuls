# detect-vuls
A simple XAMPP-based web application

This project is a demonstration of common web vulnerabilities and their mitigations, designed to help users understand how to identify and secure against vulnerabilities. It includes a PHP-based web application with examples of SQL Injection, XSS, Insecure File Upload, Directory Traversal, and Command Injection.

The Bounty Vulnerability Learning Project contains a web application that simulates various vulnerabilities and showcases secure coding practices for each. Users can test inputs to understand each vulnerability's effect and see how secure practices mitigate the risks.

## Features
- SQL Injection
- Cross-Site Scripting (XSS)
- Insecure File Upload
- Directory Traversal
- Command Injection
Points System: Users can guess vulnerabilities to earn points.
Answer Guide: An Answers.html page describes each vulnerability, providing mitigation techniques and example code.

## Technologies used
HTML/CSS: For the front-end layout and styling.
PHP: For server-side processing, session handling, and vulnerability demonstration.
phpMyAdmin's MySQL: Database management to store user points.

## Setup Instructions

### 1. Clone the repository
`git clone https://github.com/not-koushik/detect-vuls.git`
`cd detect-vuls`

### 2. Database setup:
Create a MySQL database named 'Bounty' with a 'users' table.
Configure database connection in the PHP file with localhost, root, and your chosen port (for example, 3307, in this project)

### 3. Start a local server:
Place the files in your local server’s directory (e.g., htdocs for XAMPP).
Start Apache and MySQL services from XAMPP.

### 4. Access the application
Visit http://localhost/directory-name/home.html in your web browser.

## NOTE:
Make sure to clone the repo into a directory in C:\xampp\htdocs\
