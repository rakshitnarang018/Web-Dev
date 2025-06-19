<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$message = "";
$edit = false;
$name = $roll = $email = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $roll = $_POST['roll'];
    $email = $_POST['email'];
    $sql = "INSERT INTO students (name, roll_no, email) VALUES ('$name', '$roll', '$email')";
    $message = $conn->query($sql) ? "Record added!" : "Error: " . $conn->error;
}

if (isset($_POST['fetch'])) {
    $roll = $_POST['roll'];
    $res = $conn->query("SELECT * FROM students WHERE roll_no='$roll'");
    if ($res->num_rows) {
        $row = $res->fetch_assoc();
        $name = $row['name'];
        $roll = $row['roll_no'];
        $email = $row['email'];
        $edit = true;
    } else $message = "No record found!";
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $roll = $_POST['roll'];
    $email = $_POST['email'];
    $old_roll = $_POST['old_roll'];
    $sql = "UPDATE students SET name='$name', roll_no='$roll', email='$email' WHERE roll_no='$old_roll'";
    $message = $conn->query($sql) ? "Record updated!" : "Error: " . $conn->error;
    $edit = false;
}

if (isset($_POST['delete'])) {
    $roll = $_POST['roll'];
    $message = $conn->query("DELETE FROM students WHERE roll_no='$roll'") ? "Record deleted!" : "Error: " . $conn->error;
}

if (isset($_POST['show'])) {
    $students = $conn->query("SELECT * FROM students");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student CRUD</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; max-width: 600px; margin: auto; }
        input, button { padding: 0.5rem; margin: 0.3rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 0.5rem; text-align: center; }
        .msg { margin-bottom: 1rem; color: green; }
    </style>
</head>
<body>

<h2>Student Record System</h2>

<?php if ($message): ?>
    <div class="msg"><?php echo $message; ?></div>
<?php endif; ?>

<form method="post">
    <input type="text" name="name" placeholder="Name" required value="<?= $name ?>">
    <input type="text" name="roll" placeholder="Roll No" required value="<?= $roll ?>">
    <input type="email" name="email" placeholder="Email" required value="<?= $email ?>">
    <?php if ($edit): ?>
        <input type="hidden" name="old_roll" value="<?= $roll ?>">
        <button type="submit" name="update">Update</button>
    <?php else: ?>
        <button type="submit" name="submit">Add</button>
    <?php endif; ?>
</form>

<form method="post" style="margin-top: 1rem;">
    <input type="text" name="roll" placeholder="Roll No to Fetch/Update">
    <button type="submit" name="fetch">Fetch</button>
    <button type="submit" name="delete" onclick="return confirm('Delete record?')">Delete</button>
</form>

<form method="post">
    <button type="submit" name="show">Show All Records</button>
</form>

<?php if (isset($students) && $students->num_rows): ?>
    <table>
        <tr><th>Name</th><th>Roll No</th><th>Email</th></tr>
        <?php while($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['roll_no'] ?></td>
                <td><?= $row['email'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php elseif (isset($students)): ?>
    <p>No records found.</p>
<?php endif; ?>

</body>
</html>
