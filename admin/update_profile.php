<?php
require '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('localhost:admin_login.php');
}
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $update_profile_name = $connection->prepare("UPDATE `admins` SET name =? WHERE id =?");
    $update_profile_name->execute([$name, $admin_id]);

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    // $select_old_pass = $connection->prepare("SELECT password From `admins` WHERE id=?");
    // $update_name->execute([$name, $admin_id]);


    $prev_pass = $_POST['prev_pass'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

    $confirm_pass = sha1($_POST['confirm_pass']);
    $confirm_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

    if ($old_pass == $empty_pass) {
        $message[] = 'Please enter old password!';
    } elseif ($old_pass != $prev_pass) {
        $message[] = 'Old password not matched!';
    } elseif ($new_pass != $confirm_pass) {
        $message[] = 'Confirm password not matched!';
    } else {
        if ($new_pass != $empty_pass) {
            $update_admin_name = $connection->prepare("UPDATE `admins` SET password =? WHERE id =?");
            $update_admin_name->execute([$confirm_pass, $admin_id]);
            $message[] = 'Password updated successfully!';
        } else {
            $message[] = 'Please enter a new password!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>
    <?php include '../components/admin_header.php' ?>
    <section class="update-container">
        <form action="" method="post">
            <h3>Update Profile</h3>
            <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
            <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile['name']; ?>">
            <input type="password" name="old_pass" required placeholder="Enter your old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" required placeholder="Enter your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="confirm_pass" required placeholder="Confirm your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" name="submit" value="Update now" class="btn">
        </form>
    </section>
    <script src="../js/admin.js"></script>
</body>

</html>