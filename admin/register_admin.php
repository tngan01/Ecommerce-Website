<?php
require '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('localhost:admin_login.php');
}

$sql = "SELECT * FROM `admins` WHERE name = ?";
$sql_insert = "INSERT INTO `admins` (name, password) VALUE (?,?)";
if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_admin = $connection->prepare($sql);
    $select_admin->execute([$name]);
    $row = $select_admin->fetch(PDO::FETCH_ASSOC);

    if ($select_admin->rowCount() > 0) {
        $message[] = 'Username already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password not matched!';
        } else {
            $insert_admin = $connection->prepare($sql_insert);
            $insert_admin->execute([$name, $cpass]);
            $message[] = 'New admin registered!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <section class="login-container">
        <form action="" method="post">
            <h3>Register new</h3>
            <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" name="submit" value="Register now" class="btn">
        </form>
    </section>
    <script src="../js/admin.js"></script>
</body>

</html>