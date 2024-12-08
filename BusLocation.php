
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
// Logout logic
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

   <title>Student Home</title>
   <style>
       * {
           margin: 0;
           padding: 0;
           box-sizing: border-box;
       }
       html, body {
            height: 100%; 
            overflow: hidden; 
            margin: 0;
        }

       body {
           font-family: Arial, sans-serif;
           background-color: #f3f3f3;
           color: #333;
           margin: 0; 
           padding: 0;
           transition: background-color 0.3s, color 0.3s;
       }

       /* Dark mode styles */
       body.dark-mode {
           background-color: #333;
           color: #f3f3f3;
       }

       /* Navbar Styles */
       .navbar {
        
           background: linear-gradient(to right, #6a11cb, #2575fc);
           color: white;
           display: flex;
           align-items: center;
           justify-content: space-between;
           padding: 10px 20px;
           position: fixed;
           width: 100%;
           top: 0;
           z-index: 1000;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
       }

       .navbar .left-section {
           display: flex;
           align-items: center;
       }

       .navbar img {
           width: 50px;
           height: 50px;
           border-radius: 50%;
       }

       .navbar h2 {
           margin-left: 10px;
           font-size: 24px;
           color: white;
       }

       .nav-links {
           display: flex;
           align-items: center;
       }

       .nav-item {
           margin: 0 10px;
           position: relative;
       }

       .nav-item a {
           color: white;
           text-decoration: none;
           font-size: 16px;
           font-weight: bold;
           padding: 10px 15px;
           border-radius: 20px;
           background-color: #2575fc;
           transition: background-color 0.3s, color 0.3s;
       }

       .nav-item a:hover {
           background-color: white;
           color: black;
       }

       .dropdown-content {
           visibility: hidden;
           opacity: 0;
           position: absolute;
           top: 50px;
           width: 150px;
           left: 0;
           background-color: #2575fc;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
           border-radius: 20px;
           overflow: hidden;
           z-index: 1000;
           transition: visibility 0.3s, opacity 0.3s;
       }
       .dropdown-content a{
        font-size: 12px;
        font-weight: bolder;
       }

       .nav-item:hover .dropdown-content {
           visibility: visible;
           opacity: 1;
       }

       .dropdown-content a {
           display: block;
           color: white;
           text-decoration: none;
           padding: 10px 15px;
           transition: background-color 0.3s, color 0.3s;
       }

       .dropdown-content a:hover {
           background-color: white;
           color: black;
           
       }

       /* Toggle Button */
       .toggle-btn {
           background-color: transparent;
           color: white;
           font-size: 18px;
           border: none;
           cursor: pointer;
           transition: color 0.3s;
       }

       .toggle-btn:hover {
           color: yellow;
       }

       .container {
           margin-top: 71px;
           padding: 20px;
           width: 100%;
           background-color: #fff;
           box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
           border-radius: 2px;
           text-align: center;
           position: relative;
       }
        
        

       .container .content h3 {
           margin-bottom: 10px;
           font-size: 24px;
       }

       .container .content h1 {
           font-size: 28px;
           margin-bottom: 20px;
           color: red;
       }

       .container .content p {
           margin-bottom: 20px;
           font-size: 18px;
       }

       .user-info {
           position: absolute;
           top: 5px;
           right: 10px;
           display: flex;
           align-items: center;
       }

       .user {
           background-color: #6C8293;
           padding: 5px 15px;
           border-radius: 20px;
           color: white;
           font-size: 12px;
           font-weight: bold;
           margin-right: 15px;
       }

       .logout-btn {
           background: none;
           font-weight: bold;
           border: none;
           color: #6C8293;
           font-size: 12px;
           cursor: pointer;
           text-decoration: none;
           padding: 0;
       }
       body.dark-mode .logout-btn {
           color: red;
       }

       .logout-btn:hover {
        color: white;
        background-color: #0babeb;
        border-radius: 20px;
        padding: 5px;     
       }
       body.dark-mode .logout-btn:hover {
           color: red;
           background-color: black;
           border-radius: 20px;
           padding: 5px;
       }

       /* Dark Mode Adjustments */
       body.dark-mode .container {
           background-color: #444;
           color: white;
       }

      

       body.dark-mode .dropdown-content a {
           color: white;
       }

       body.dark-mode .dropdown-content a:hover {
           background-color: white;
           color: black;
       }

       .in-container {
            width: 90%; 
            max-width: 1300px; 
            margin: 20px auto;
            height: 400px;
            background-color: #e1d5eb;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 0px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .bus {
            width: 100%;
            height: 100vh; 
            margin: 0;
            padding: 0;
            overflow: hidden; 
            border-radius: 10px;
        }

        .bus iframe {
            width: 100%;
            height: 100%;
            border: none; 
        }


        body.dark-mode .in-container {
           background-color: #444;
           color: white;
       }

   </style>
   <script>
       function toggleDarkMode() {
           const body = document.body;
           const toggleBtn = document.querySelector('.toggle-btn');
           body.classList.toggle('dark-mode');

           // Change icon based on mode
           if (body.classList.contains('dark-mode')) {
               toggleBtn.textContent = '🌞'; // Dark mode active
           } else {
               toggleBtn.textContent = '🌙'; // Light mode active
           }
       }
   </script>
</head>
<body>
<div class="navbar">
    <div class="left-section">
        <img src="photo/graduation.png" alt="">
        <h2>SMART STUDENT PORTAL</h2>
    </div>
    <div class="nav-links">
        <div class="nav-item"><a href="StudentHome.php"><i class="fas fa-home"></i> Home</a></div>
        
        <div class="nav-item">
            <a href="#"><i class="fas fa-user-circle"></i> Profile</a>
            <div class="dropdown-content">
                <a href="sProfileEdit.html"><i class="fas fa-edit"></i> Edit Profile</a>
                <a href="sPasswordChange.html"><i class="fas fa-key"></i> Change Password</a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#"><i class="fas fa-book"></i> Courses</a>
            <div class="dropdown-content">
                <a href="#"><i class="fas fa-book-reader"></i> Request Book</a>
                <a href="#"><i class="fas fa-clipboard-list"></i> Pre-Registration</a>
                <a href="#"><i class="fas fa-registered"></i> Registration</a>
                <a href="sCourseEvalution.html"><i class="fas fa-chart-bar"></i> Evaluate Course</a>
                <a href="#"><i class="fas fa-times-circle"></i> Drop Course or Semester</a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#"><i class="fas fa-graduation-cap"></i> Academics</a>
            <div class="dropdown-content">
                <a href="#"><i class="fas fa-clipboard"></i> Result</a>
                <a href="#"><i class="fas fa-calendar-alt"></i> Class Routine</a>
                <a href="#"><i class="fas fa-ticket-alt"></i> Exam Routine & Admit Card</a>
                <a href="BusLocation.html"><i class="fas fa-bus"></i> Buses Location</a>
            </div>
        </div>
        <button class="toggle-btn" onclick="toggleDarkMode()">🌙</button>
    </div>
</div>



<div class="container">
<div class="user-info">
        <div class="user">
        <span><?php echo htmlspecialchars($_SESSION['id']) . " | " . htmlspecialchars($_SESSION['username']); ?></span>
        </div>
        <div class="logout">
        <form action="" method="GET" style="margin: 0;">
                <button class="logout-btn" name="logout">Log Out</button>
            </form>
        </div>
    </div>
   <!-- in-containner-->

   <div class="in-container">
        <div class="bus">
            <iframe 
                src="https://vts.obhai.com/resource/dev/index.html#/monitorObject" 
                allowfullscreen>
            </iframe>
        </div>
    
    </div>

</div>
</body>
</html>



