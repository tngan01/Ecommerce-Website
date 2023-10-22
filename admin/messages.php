<?php
require '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('localhost:admin_login.php');
}

// delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_message = $connection->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_message->execute([$delete_id]);
    header('location:messages.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <section class="messages">
        <h1 class="heading">New messages</h1>

        <div class="box-container">
            <?php
            $select_messages = $connection->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            if ($select_messages->rowCount() > 0) {
                while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <div class="box">
                            <p> user id : <span><?= $fetch_message['user_id']; ?></span></p>
                            <p> name : <span><?= $fetch_message['name']; ?></span></p>
                            <p> email : <span><?= $fetch_message['email']; ?></span></p>
                            <p> number : <span><?= $fetch_message['number']; ?></span></p>
                            <p> message : <span><?= $fetch_message['message']; ?></span></p>
                            <a href="messages.php??delete=<?= $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete</a>

                        </div>
                <?php
                }
            } else {
                echo '<p class="empty">You have no messages</p>';
            }
                ?>
                    </div>
    </section>
    <script src="../js/admin.js"></script>
</body>

</html>