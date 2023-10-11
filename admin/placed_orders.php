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
    <title>Placed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>
    <?php include '../components/admin_header.php'; ?>
    <section class="placed-orders">
        <h1 class="heading">placed orders</h1>
        <div class="box-container">

            <?php
            $select_orders = $connection->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            if ($select_orders->rowCount() > 0) {
            } else {
                echo ' <p class="empty">No orders placed yet!</p>';
            }
            ?>
    </section>

    </div>
    <script src="../js/admin.js"></script>
</body>

</html>