<?php 
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $SELECT = "SELECT * FROM operation WHERE id = $id";
    $run  = mysqli_query($con, $SELECT);

    if ($run && mysqli_num_rows($run) > 0) {
        $data = mysqli_fetch_array($run);
    } else {
        echo "Record not found!";
        $data = null; // Prevents errors if the record doesn't exist
    }
} else {
    echo "ID parameter is missing!";
    $data = null; // Prevents errors if ID is not passed
}
?>
<div>
<form action="" method="POST">
        <input value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" type="text" name="name" placeholder="Enter your name">
        
        <input type="email" name="email" placeholder="Enter your email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
        
        <input type="password" name="password" placeholder="Enter your password">
        
        <input type="submit" name="update" value="update">
        
        <button><a href="view.php">Back</a></button>
    </form>
</div>
<?php
    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $update="UPDATE operation SET name='$name', email='$email', password='$password' WHERE id=$id";
        $update = mysqli_query($con, $update);
        if ($update) {
            ?>
            <script>
                alert("Data updated successfully");
                window.location.href = "view.php";
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("Data not updated");
                window.location.href = "view.php";
            </script>
            <?php
        }
    }
?>