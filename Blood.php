<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$bloodGroupFilter = "";
$query = "SELECT * FROM blood";

// Apply search filter if BloodGroup is specified
if (isset($_GET['search']) && !empty($_GET['bloodGroup'])) {
    $bloodGroupFilter = $_GET['bloodGroup'];
    $query = "SELECT * FROM blood WHERE BloodGroup = '$bloodGroupFilter'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donor Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #f8b6d2, #9ab3f5);
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Blood Donor Information</h1>
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="bloodGroup" placeholder="Search by Blood Group" value="<?php echo htmlspecialchars($bloodGroupFilter); ?>">
            <button type="submit" name="search" class="btn btn-primary">Search</button>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>Blood Group</th>
                <th>Phone No</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['BloodGroup']); ?></td>
                        <td><?php echo htmlspecialchars($row['PhoneNo']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No donors found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
