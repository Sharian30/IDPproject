<?php
session_start();
require_once 'config.php'; 


if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}


$student_id = $_SESSION['id'];
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM StudentInformation WHERE StudentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "<p>User not found.</p>";
    exit();
}

// Handle form submission for updating data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = $_POST['department'];
    $batch = $_POST['batch'];
    $semester = $_POST['semester'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $email = $_POST['email'];
    $phone_no = $_POST['phone_no'];
    $address = $_POST['address'];
    $father_name = $_POST['fathers_name'];
    $father_profession = $_POST['fathers_profession'];
    $father_phone_no = $_POST['fathers_phone_no'];
    $mother_name = $_POST['mothers_name'];
    $mother_profession = $_POST['mothers_profession'];
    $mother_phone_no = $_POST['mothers_phone_no'];

    $update_sql = "
        UPDATE StudentInformation 
        SET 
            Department = ?, 
            Batch = ?, 
            Semester = ?, 
            DateOfBirth = ?, 
            Gender = ?, 
            BloodGroup = ?, 
            Email = ?, 
            PhoneNo = ?, 
            Address = ?, 
            FathersName = ?, 
            FathersProfession = ?, 
            FathersPhoneNo = ?, 
            MothersName = ?, 
            MothersProfession = ?, 
            MothersPhoneNo = ?
        WHERE StudentID = ?
    ";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param(
        "sssssssssssssssi",
        $department,
        $batch,
        $semester,
        $dob,
        $gender,
        $blood_group,
        $email,
        $phone_no,
        $address,
        $father_name,
        $father_profession,
        $father_phone_no,
        $mother_name,
        $mother_profession,
        $mother_phone_no,
        $student_id
    );

    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: sProfileEdit.php");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9ff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .profile-header div {
            font-size: 18px;
        }
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        label {
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .full-width {
            grid-column: span 2;
        }
        button {
            grid-column: span 2;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-header">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($student['StudentsPhoto']); ?>" alt="Profile Picture">
            <div>
                <p><span>Name:</span> <?php echo htmlspecialchars($student['StudentName']); ?></p>
                <p><span>Student ID:</span> <?php echo htmlspecialchars($student['StudentID']); ?></p>
            </div>
        </div>
        <?php if (isset($_SESSION['success_message'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="department">Department</label>
            <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($student['Department']); ?>" required>

            <label for="batch">Batch</label>
            <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($student['Batch']); ?>" required>

            <label for="semester">Semester</label>
            <input type="text" id="semester" name="semester" value="<?php echo htmlspecialchars($student['Semester']); ?>" required>

            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($student['DateOfBirth']); ?>" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if ($student['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($student['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($student['Gender'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>

            <label for="blood_group">Blood Group</label>
            <select id="blood_group" name="blood_group" required>
                <?php
                $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                foreach ($blood_groups as $group) {
                    echo "<option value=\"$group\"" . ($student['BloodGroup'] == $group ? ' selected' : '') . ">$group</option>";
                }
                ?>
            </select>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['Email']); ?>" required>

            <label for="phone_no">Phone No</label>
            <input type="text" id="phone_no" name="phone_no" value="<?php echo htmlspecialchars($student['PhoneNo']); ?>" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($student['Address']); ?></textarea>

            <label for="fathers_name">Father's Name</label>
            <input type="text" id="fathers_name" name="fathers_name" value="<?php echo htmlspecialchars($student['FathersName']); ?>">

            <label for="fathers_profession">Father's Profession</label>
            <input type="text" id="fathers_profession" name="fathers_profession" value="<?php echo htmlspecialchars($student['FathersProfession']); ?>">

            <label for="fathers_phone_no">Father's Phone No</label>
            <input type="text" id="fathers_phone_no" name="fathers_phone_no" value="<?php echo htmlspecialchars($student['FathersPhoneNo']); ?>">

            <label for="mothers_name">Mother's Name</label>
            <input type="text" id="mothers_name" name="mothers_name" value="<?php echo htmlspecialchars($student['MothersName']); ?>">

            <label for="mothers_profession">Mother's Profession</label>
            <input type="text" id="mothers_profession" name="mothers_profession" value="<?php echo htmlspecialchars($student['MothersProfession']); ?>">

            <label for="mothers_phone_no">Mother's Phone No</label>
            <input type="text" id="mothers_phone_no" name="mothers_phone_no" value="<?php echo htmlspecialchars($student['MothersPhoneNo']); ?>">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
