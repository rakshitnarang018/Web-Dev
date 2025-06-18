<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$message = "";


if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $rollNo = $_POST['rollNo'];
    $email = $_POST['email'];
    
    
    $sql = "INSERT INTO students (name, roll_no, email) VALUES ('$name', '$rollNo', '$email')";
    
    if($conn->query($sql) === TRUE) {
        $message = "New student record created successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}


$editMode = false;
$editName = "";
$editRollNo = "";
$editEmail = "";

if(isset($_POST['fetch_edit'])) {
    $rollNoToEdit = $_POST['roll_to_edit'];
    
    
    $sql = "SELECT * FROM students WHERE roll_no='$rollNoToEdit'";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $editMode = true;
        $editName = $row['name'];
        $editRollNo = $row['roll_no'];
        $editEmail = $row['email'];
    } else {
        $message = "No student found with Roll No: $rollNoToEdit";
    }
}


if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $rollNo = $_POST['rollNo'];
    $email = $_POST['email'];
    $oldRollNo = $_POST['old_roll_no'];
    
    
    $sql = "UPDATE students SET name='$name', roll_no='$rollNo', email='$email' WHERE roll_no='$oldRollNo'";
    
    if($conn->query($sql) === TRUE) {
        $message = "Student record updated successfully!";
        $editMode = false;
    } else {
        $message = "Error updating record: " . $conn->error;
    }
}


if(isset($_POST['delete'])) {
    $rollNoToDelete = $_POST['roll_to_delete'];
    
    
    $sql = "DELETE FROM students WHERE roll_no='$rollNoToDelete'";
    
    if($conn->query($sql) === TRUE) {
        $message = "Student record deleted successfully!";
    } else {
        $message = "Error deleting record: " . $conn->error;
    }
}

// Display records flag
$displayRecords = false;
if(isset($_POST['display'])) {
    $displayRecords = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - CRUD Operations</title>
    <link rel="stylesheet" href="crud.css">
</head>
<body>
    <div class="container">
        <h1>Student Records</h1>
        
        <?php if(!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $editMode ? $editName : ''; ?>" placeholder="Enter full name" required>
            </div>
            
            <div class="form-group">
                <label for="rollNo">Roll No:</label>
                <input type="text" id="rollNo" name="rollNo" value="<?php echo $editMode ? $editRollNo : ''; ?>" placeholder="Enter roll number" required <?php echo $editMode ? '' : ''; ?>>
                <?php if($editMode): ?>
                    <input type="hidden" name="old_roll_no" value="<?php echo $editRollNo; ?>">
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $editMode ? $editEmail : ''; ?>" placeholder="Enter email address" required>
            </div>
            
            <?php if($editMode): ?>
                <button type="submit" class="btn btn-warning" name="update">Update</button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <?php endif; ?>
        </form>
        
        
        <div class="action-buttons">
            <form method="POST" action="" style="display: inline;">
                <button type="submit" class="btn btn-success" name="display">Display Records</button>
            </form>
            
            <button class="btn btn-warning" onclick="toggleEditForm()">Edit Record</button>
            <button class="btn btn-danger" onclick="toggleDeleteForm()">Remove Record</button>
        </div>
        
        
        <div id="editForm" class="modal">
            <form method="POST" action="">
                <h3>Edit Record</h3>
                <div class="form-group">
                    <label for="roll_to_edit">Enter Roll No:</label>
                    <input type="text" id="roll_to_edit" name="roll_to_edit" placeholder="Enter roll number to edit" required>
                </div>
                <button type="submit" class="btn btn-warning" name="fetch_edit">Fetch Record</button>
            </form>
        </div>
        
        
        <div id="deleteForm" class="modal">
            <form method="POST" action="" onsubmit="return confirmDelete()">
                <h3>Remove Record</h3>
                <div class="form-group">
                    <label for="roll_to_delete">Enter Roll No:</label>
                    <input type="text" id="roll_to_delete" name="roll_to_delete" placeholder="Enter roll number to delete" required>
                </div>
                <button type="submit" class="btn btn-danger" name="delete">Delete Record</button>
            </form>
        </div>
        
        
        <?php if($displayRecords): ?>
            <div id="records">
                <h2>Student Records</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Roll No</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM students";
                        $result = $conn->query($sql);
                        
                        if($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["roll_no"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' style='text-align: center;'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="crud.js"></script>
</body>
</html>