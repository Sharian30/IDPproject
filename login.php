<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // Initialize error message

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // Validate ID and Password
    if (!empty($id) && !empty($password)) {
        if (is_numeric($id)) {
            if (strlen($id) == 9) {

                $stmt = $conn->prepare("SELECT StudentName FROM StudentInformation WHERE StudentID = ? AND StudentPassword = ?");

                $stmt->bind_param("is", $id, $password);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    // Set session variables
                    $_SESSION['username'] = $row['StudentName'];
                    $_SESSION['id'] = $id;

                    // Redirect to student dashboard
                    header("Location: StudentHome.php");
                    exit();
                } else {
                    $error = "Invalid credentials for Student";
                }
            } elseif (strlen($id) == 5) {
                // Check TeacherInformation table for 5-digit IDs
                $stmt = $conn->prepare("SELECT TeacherName FROM TeacherInformation WHERE TeacherID = ? AND TeachersPassword = ?");
                $stmt->bind_param("is", $id, $password);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    // Set session variables
                    $_SESSION['username'] = $row['TeacherName'];
                    $_SESSION['id'] = $id;

                    // Redirect to teacher dashboard
                    //header("Location: TeacherHome.php");
                    header("Location: index.html");
                    exit();
                } else {
                    $error = "Invalid credentials for Teacher";
                }
            } else {
                $error = "Invalid ID length";
            }
        } else {
            $error = "ID must be numeric";
        }
    } else {
        $error = "Please fill out all fields";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(to bottom right, #a570de, #a1bef0);
      font-family: 'Arial', sans-serif;
    }
    .login-form {
      background: white;
      border-radius: 10px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
      padding: 30px;
      max-width: 500px;
      width: 100%;
      text-align: center;
    }
    .login-form img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 20px;
    }
    .login-form label {
      display: block;
      text-align: left;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .invalid-feedback {
      display: block;
      color: red;
      font-size: 0.9em;
      text-align: left;
    }
    .alert {
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="login-form">
    <img src="photo/user (1).png" alt="User Icon">
    <form method="POST" action="" id="loginForm" novalidate>
      <div class="mb-3">
        <label for="userID" class="form-label">ID</label>
        <input type="text" class="form-control <?php echo (!empty($error) && $error === "Please fill out the fields") ? 'is-invalid' : ''; ?>" id="userID" name="id" placeholder="Enter your ID">
        <div class="invalid-feedback"><?php echo (!empty($error) && $error === "Please fill out the fields") ? $error : ''; ?></div>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control <?php echo (!empty($error) && $error !== "Please fill out the fields") ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Enter your password">
        <div class="invalid-feedback"><?php echo (!empty($error) && $error !== "Please fill out the fields") ? $error : ''; ?></div>
      </div>
      <div class="d-flex justify-content-between align-items-center">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="#" class="text-decoration-none">Forgot Password?</a>
      </div>
      <button type="submit" class="btn btn-primary w-100 mt-3">Log In</button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Automatically hide the error message when the user starts typing
    document.querySelectorAll('#userID, #password').forEach(input => {
      input.addEventListener('input', function () {
        this.classList.remove('is-invalid');
        const feedback = this.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
          feedback.textContent = '';
        }
      });
    });
  </script>
</body>
</html>
