ONLINE COMPLAINT MANAGEMENT SYSTEM - PHP + MYSQL

PROJECT SETUP (XAMPP)
1. Install XAMPP.
2. Extract the folder "online_complaint_management_system" into:
   C:\xampp\htdocs\
3. Open XAMPP Control Panel and start Apache and MySQL.
4. Open http://localhost/phpmyadmin
5. Choose Import, select database.sql from this project folder, and click Import.
   This creates the complaint_management database and its tables.
6. If your MySQL username/password differs from XAMPP defaults, update config/db.php.
7. Open http://localhost/online_complaint_management_system/register.php
   Register a student account.
8. Create the administrator:
   Open http://localhost/online_complaint_management_system/setup_admin.php
   Enter admin name, email, and password (minimum 8 characters).
   After the admin account is created, DELETE setup_admin.php from the project folder.
9. Student login: http://localhost/online_complaint_management_system/login.php
   Admin login: same login page; the app redirects based on the account role.

LOCAL FALLBACK
- For local development, the app falls back to an SQLite database in the project data/ directory if MySQL is unavailable.
- SQLite is for local development only. Production requires MySQL and uses the database session table so logins work across Vercel instances.

VERCEL DEPLOYMENT
1. Import this GitHub repository into Vercel. Vercel detects the root Dockerfile and deploys the PHP app using FrankenPHP.
2. Create a persistent MySQL database with a provider that allows connections from Vercel.
3. Create the database, then import database.sql to create the users, complaints, and sessions tables.
4. In Vercel, open Project Settings > Environment Variables and set:
   APP_ENV=production
   APP_DB_DRIVER=mysql
   DB_HOST=your-database-host
   DB_PORT=3306
   DB_NAME=complaint_management
   DB_USER=your-database-user
   DB_PASS=your-database-password
5. Redeploy after adding the variables. Do not commit credentials or use SQLite for production; Vercel container files are not persistent.
6. Visit /setup_admin.php once to create the first administrator, then remove or disable that setup page before opening the site to the public.

STUDENT WORKFLOW
- Register and log in.
- Submit a complaint with title, category, and description.
- View the generated complaint ID and status.
- Return to dashboard to track the complaint.

ADMIN WORKFLOW
- Log in with the admin account.
- Open Admin Dashboard.
- Review complaints, filter by status, and update status/remarks.
- Student logs in again to see the updated status and remarks.

IMPORTANT
- This is a local academic demonstration project. Before public deployment, add HTTPS,
  CSRF protection, rate limiting, stronger operational controls, and regular backups.
- Do not share administrator credentials.
- Delete setup_admin.php after creating the admin account.
