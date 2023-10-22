<?php
require '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('localhost:admin_login.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <section class="dashboard">

        <h1 class="heading">Dashboard</h1>

        <div class="box-container">

            <div class="box">
                <h3>Welcome!</h3>
                <p><?= $fetch_profile['name']; ?></p>
                <a href="update_profile.php" class="btn">Update profile</a>
            </div>

            <div class="box">
                <?php
                $total_pendings = 0;
                $select_pendings = $connection->prepare("SELECT *FROM `orders` WHERE payment_status=?");
                $select_pendings->execute(['pending']);
                while ($fetch_pending = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
                    $total_pendings += $fetch_pending['total_price'];
                }
                ?>
                <h3><span>$</span><?= $total_pendings; ?><span>/-</span></h3>
                <p>Total pendings</p>
                <a href="uplaced_orders.php" class="btn">See orders</a>
            </div>

            <div class="box">
                <?php
                $total_completes = 0;
                $select_completes = $connection->prepare("SELECT *FROM `orders` WHERE payment_status=?");
                $select_completes->execute(['completed']);
                while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
                    $total_completes += $fetch_completes['total_price'];
                }
                ?>
                <h3><span>$</span><?= $total_completes; ?><span>/-</span></h3>

                <p>Total completes</p>
                <a href="placced_order.php" class="btn">See orders</a>
            </div>

            <div class="box">
                <?php
                $select_orders = $connection->prepare("SELECT * FROM `orders`");
                $select_orders->execute();
                $number_of_orders = $select_orders->rowCount();
                ?>
                <h3><?= $number_of_orders; ?></h3>
                <p>Total orders</p>
                <a href="placed_orders.php" class="btn">see orders</a>
            </div>

            <div class="box">
                <?php
                $select_products = $connection->prepare("SELECT * FROM `products`");
                $select_products->execute();
                $number_of_products = $select_products->rowCount();
                ?>
                <h3><?= $number_of_products; ?></h3>
                <p>products added</p>
                <a href="products.php" class="btn">see products</a>
            </div>

            <div class="box">
                <?php
                $select_users = $connection->prepare("SELECT * FROM `users`");
                $select_users->execute();
                $number_of_users = $select_users->rowCount();
                ?>
                <h3><?= $number_of_users; ?></h3>
                <p>normal users</p>
                <a href="users_accounts.php" class="btn">see users</a>
            </div>

            <div class="box">
                <?php
                $select_admins = $connection->prepare("SELECT * FROM `admins`");
                $select_admins->execute();
                $number_of_admins = $select_admins->rowCount();
                ?>
                <h3><?= $number_of_admins; ?></h3>
                <p>admin users</p>
                <a href="admin_accounts.php" class="btn">see admins</a>
            </div>

            <div class="box">
                <?php
                $select_messages = $connection->prepare("SELECT * FROM `messages`");
                $select_messages->execute();
                $number_of_messages = $select_messages->rowCount();
                ?>
                <h3><?= $number_of_messages; ?></h3>
                <p>new messages</p>
                <a href="messagess.php" class="btn">see messages</a>
            </div>
        </div>
    </section>


    <script src="../js/admin_js.js"></script>
</body>

</html>