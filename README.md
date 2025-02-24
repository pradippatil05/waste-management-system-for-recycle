# waste-management-system-for-recycle
The Waste Management System for Recycling is a web-based platform developed using HTML, CSS, JavaScript, PHP, and MySQL, and implemented using XAMPP for local server hosting.

Key features include:
- User-friendly interface for scheduling pickups and categorizing recyclables.
- User roles (Admin, Recycle Centre, Users) with secure authentication.
- Recycling center locator and recycling guidelines.
- Admin dashboard to manage users, schedules, and stats.
- Notifications for reminders and updates.
- MySQL database (via XAMPP) to store user data, schedules, and center details.
- 
Built and tested on XAMPP, this system simplifies waste collection, encourages recycling, and provides insights, making it an efficient tool for communities and organizations.

### Step 1: Download and Install XAMPP
1. Go to the official XAMPP website: https://www.apachefriends.org.
2. Download the version compatible with your operating system (Windows, macOS, or Linux).
3. Run the installer and follow the instructions.
4. During installation, ensure Apache and MySQL are selected.
5. Open the XAMPP Control Panel and start Apache and MySQL.

### Step 2: Download the Project
(Download and extract the zip file)
1. Obtain the project files (the folder should be named waste-management-system-main).
2. Place the project folder inside the XAMPP htdocs directory:
   - Windows: C:\xampp\htdocs\
   - macOS/Linux: /opt/lampp/htdocs/

### Step 3: Import the Database
1. Open phpMyAdmin in your browser: http://localhost/phpmyadmin.
2. Create a new database named wms.
3. Import the SQL file provided with the project into the wms database.

### Step 4: Configure the Connection File
1. Open the project folder (waste-management-system-main) in a text editor.
2. Locate the connection.php file.
3. Update the database credentials to match the wms database:
$con = mysqli_connect('localhost', 'root','', 'wms'); //edit with your username and password by default the username and password is given

### Step 5: Access the Project
1. Open a browser and enter:
   http://localhost/waste-management-system-main/
2. Ensure the folder name in the URL matches exactly (waste-management-system-main).

### Optional: Access Over a Network
1. Find the device’s local IP address (e.g., 192.168.1.100).
2. On another device, enter:
   http://<device_ip>/waste-management-system-main/

