<?php
require '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('localhost:admin_login.php');
}

// btn delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_admin = $connection->prepare("DELETE FROM `admins` WHERE id = ? ");
    $delete_admin->execute([$delete_id]);
    header('location:admin_accounts.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Accounts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <section class="accounts">

        <h1 class="heading">Admins accounts</h1>
        <div class="box-container">
            <div class="box">
                <p>register new admin</p>
                <a href="register_admin.php" class="option-btn">register</a>
            </div>
            <?php
            $select_account = $connection->prepare("SELECT * FROM `admins`");
            $select_account->execute();
            if ($select_account->rowCount() > 0) {
                while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
            ?>

                    <div class="box">
                        <p> admin id : <span> <?= $fetch_accounts['id']; ?></span></p>
                        <p> username : <span><?= $fetch_accounts['name']; ?></span></p>
                        <div class="flex-btn">
                            <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">delete</a>
                            <?php
                            if ($select_account['id'] == $admin_id) {
                                echo '<a href="update_profile.php" class = "option-btn">update</a>';
                            }
                            ?>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No accounts available</p>';
            }
            ?>
        </div>
    </section>

    <script src="../js/admin_js.js"></script>
</body>

</html>