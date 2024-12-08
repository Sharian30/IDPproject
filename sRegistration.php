<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "portal");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input data
    $studentID = $_POST['studentID'];
    if (!preg_match("/^\d{9}$/", $studentID)) {
        die("Student ID must be exactly 9 digits.");
    }

    $department = $conn->real_escape_string($_POST['department']);
    $batch = $conn->real_escape_string($_POST['batch']);
    $semester = $conn->real_escape_string($_POST['semester']);
    $studentName = $conn->real_escape_string($_POST['studentName']);
    $dateOfBirth = $_POST['dateOfBirth'];
    $gender = $_POST['gender'];
    $bloodGroup = $_POST['bloodGroup'];
    $email = $conn->real_escape_string($_POST['email']);
    $phoneNo = $conn->real_escape_string($_POST['phoneNo']);
    $address = $conn->real_escape_string($_POST['address']);
    $fathersName = $conn->real_escape_string($_POST['fathersName']);
    $fathersProfession = $conn->real_escape_string($_POST['fathersProfession']);
    $fathersPhoneNo = $conn->real_escape_string($_POST['fathersPhoneNo']);
    $mothersName = $conn->real_escape_string($_POST['mothersName']);
    $mothersProfession = $conn->real_escape_string($_POST['mothersProfession']);
    $mothersPhoneNo = $conn->real_escape_string($_POST['mothersPhoneNo']);
    $studentsPassword = $_POST['studentsPassword'];

    // Handle the uploaded file
    $studentsPhoto = $_FILES['studentsPhoto'];

    // Validate the uploaded file
    $allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml'];
    $fileType = mime_content_type($studentsPhoto['tmp_name']);
    if (!in_array($fileType, $allowedExtensions)) {
        die("Invalid image type. Allowed types: JPG, PNG, GIF, WEBP, SVG.");
    }

    // Convert the uploaded file to binary data for storage
    $photoData = addslashes(file_get_contents($studentsPhoto['tmp_name']));

    // Insert into the database
    $sql = "INSERT INTO StudentInformation (
                StudentID, Department, Batch, Semester, StudentName, DateOfBirth, Gender, BloodGroup, 
                Email, PhoneNo, Address, FathersName, FathersProfession, FathersPhoneNo, 
                MothersName, MothersProfession, MothersPhoneNo, StudentsPhoto, StudentsPassword
            ) 
            VALUES (
                '$studentID', '$department', '$batch', '$semester', '$studentName', '$dateOfBirth', '$gender', '$bloodGroup',
                '$email', '$phoneNo', '$address', '$fathersName', '$fathersProfession', '$fathersPhoneNo', 
                '$mothersName', '$mothersProfession', '$mothersPhoneNo', '$photoData', '$studentsPassword'
            )";

    if ($conn->query($sql) === TRUE) {
        echo "Student registered successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            color: #343a40;
        }
        .container {
            width: 60%;
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.3s ease;
        }
        .container:hover {
            box-shadow: 0 6px 40px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #495057;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Registration</h2>
        <form action="sRegistration.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="studentID">Student ID:</label>
                <input type="text" name="studentID" id="studentID" required pattern="\d{9}" placeholder="Enter 9-digit Student ID">
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" name="department" id="department" required placeholder="Enter Department">
            </div>
            <div class="form-group">
                <label for="batch">Batch:</label>
                <input type="text" name="batch" id="batch" required placeholder="Enter Batch">
            </div>
            <div class="form-group">
                <label for="semester">Semester:</label>
                <input type="text" name="semester" id="semester" required placeholder="Enter Semester">
            </div>
            <div class="form-group">
                <label for="studentName">Student Name:</label>
                <input type="text" name="studentName" id="studentName" required placeholder="Enter Student Name">
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Date of Birth:</label>
                <input type="date" name="dateOfBirth" id="dateOfBirth" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" id="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bloodGroup">Blood Group:</label>
                <select name="bloodGroup" id="bloodGroup" required>
                    <option value="" disabled selected>Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required placeholder="Enter Email">
            </div>
            <div class="form-group">
                <label for="phoneNo">Phone Number:</label>
                <input type="text" name="phoneNo" id="phoneNo" required placeholder="Enter Phone Number">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea name="address" id="address" rows="3" required placeholder="Enter Address"></textarea>
            </div>
            <div class="form-group">
                <label for="fathersName">Father's Name:</label>
                <input type="text" name="fathersName" id="fathersName" required placeholder="Enter Father's Name">
            </div>
            <div class="form-group">
                <label for="fathersProfession">Father's Profession:</label>
                <input type="text" name="fathersProfession" id="fathersProfession" placeholder="Enter Father's Profession">
            </div>
            <div class="form-group">
                <label for="fathersPhoneNo">Father's Phone Number:</label>
                <input type="text" name="fathersPhoneNo" id="fathersPhoneNo" placeholder="Enter Father's Phone Number">
            </div>
            <div class="form-group">
                <label for="mothersName">Mother's Name:</label>
                <input type="text" name="mothersName" id="mothersName" required placeholder="Enter Mother's Name">
            </div>
            <div class="form-group">
                <label for="mothersProfession">Mother's Profession:</label>
                <input type="text" name="mothersProfession" id="mothersProfession" placeholder="Enter Mother's Profession">
            </div>
            <div class="form-group">
                <label for="mothersPhoneNo">Mother's Phone Number:</label>
                <input type="text" name="mothersPhoneNo" id="mothersPhoneNo" placeholder="Enter Mother's Phone Number">
            </div>
            <div class="form-group">
                <label for="studentsPhoto">Student's Photo:</label>
                <input type="file" name="studentsPhoto" id="studentsPhoto" required>
            </div>
            <div class="form-group">
                <label for="studentsPassword">Password:</label>
                <input type="password" name="studentsPassword" id="studentsPassword" required placeholder="Enter Password">
            </div>
            <button type="submit">Register Student</button>
        </form>
    </div>
</body>
</html>