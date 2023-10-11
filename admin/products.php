<?php
require '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('localhost:admin_login.php');
}
// add product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $details  = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    $image_01 = $_FILES['image_01']['name'];
    $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
    $image_01_size = $_FILES['image_01']['size'];
    $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
    $image_01_folder = '../images/' . $image_01;

    $image_02 = $_FILES['image_02']['name'];
    $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
    $image_02_size = $_FILES['image_02']['size'];
    $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
    $image_02_folder = '../images/' . $image_02;

    $image_03 = $_FILES['image_03']['name'];
    $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
    $image_03_size = $_FILES['image_03']['size'];
    $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
    $image_03_folder = '../images/' . $image_03;

    $select_products = $connection->prepare("SELECT * FROM `products`WHERE name =?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'product name already exits!';
    } else {
        $insert_product = $connection->prepare("INSERT INTO `products` (name,details, price, image_01, image_02, image_03) VALUE (?,?,?,?,?,?)");
        $insert_product->execute([$name, $details, $price, $image_01, $image_02, $image_03]);

        if ($image_01_size > 2000000 or $image_02_size > 2000000 or $image_03_size > 2000000) {
            $message[] = 'image size is too large';
        } else {
            move_uploaded_file($image_01_tmp_name, $image_01_folder);
            move_uploaded_file($image_02_tmp_name, $image_02_folder);
            move_uploaded_file($image_03_tmp_name, $image_03_folder);
            // $insert_product = $connection->prepare("INSERT INTO `products` (name,details, price, image_01, image_02, image_03) VALUE (?,?,?,?,?,?)");
            // $insert_product->execute([$name, $details, $price, $image_01, $image_02, $image_03]);

            $message[] = 'new product added!';
        }
    }
}

// delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_product_image = $connection->prepare("SELECT * FROM `products`WHERE id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../images/' . $fetch_delete_image['image_01']);
    unlink('../images/' . $fetch_delete_image['image_02']);
    unlink('../images/' . $fetch_delete_image['image_03']);
    $delete_product = $connection->prepare("DELETE FROM `products` WHERE id =?");
    $delete_product->execute([$delete_id]);

    $delete_cart = $connection->prepare("DELETE FROM `cart` WHERE pid =?");
    $delete_cart->execute([$delete_id]);

    $delete_wishlist = $connection->prepare("DELETE FROM `wishlist` WHERE pid =?");
    $delete_wishlist->execute([$delete_id]);

    header('location:products.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_s.css">

</head>

<body>
    <?php include '../components/admin_header.php'; ?>
    <!-- add product -->
    <section class="add-products">
        <h1 class="heading">add product</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="flex">
                <div class="inputBox">
                    <span>product name (required)</span>
                    <input type="text" class="box" required maxlength="100" placeholder="enter product name" name="name">
                </div>

                <div class="inputBox">
                    <span>product price (required)</span>
                    <input type="number" min="0" max="9999999999" require placeholder="enter product price" name="price" onkeypress="if(this.value.length ==10) return false;" class="box">
                </div>

                <div class="inputBox">
                    <span>image 01 (required)</span>
                    <input type="file" name="image_01" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                </div>

                <div class="inputBox">
                    <span>image 02 (required)</span>
                    <input type="file" name="image_02" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                </div>

                <div class="inputBox">
                    <span>image 03 (required)</span>
                    <input type="file" name="image_03" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
                </div>

                <div class="inputBox">
                    <span>product details</span>
                    <textarea name="details" class="box" placeholder="enter product details" required maxlength="500" cols="30" rows="10"></textarea>
                </div>

                <input type="submit" value="add product" name="add_product" class="btn">
            </div>
        </form>
    </section>
    <!-- show product -->
    <section class="show-products">
        <h1 class="heading">add product</h1>
        <div class="box-container">

            <?php
            $show_products = $connection->prepare("SELECT * From `products`");
            $show_products->execute();
            if ($show_products->rowCount() > 0) {
                while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {

            ?>
                    <div class="box">
                        <img src="../images/<?= $fetch_products['image_01']; ?>" alt="">
                        <div class="name"><?= $fetch_products['name']; ?></div>
                        <div class="price">$<span><?= $fetch_products['price']; ?></span></div>
                        <div class="details">,<span><?= $fetch_products['details']; ?></span></div>
                        <div class="flex-btn">
                            <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
                            <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">delete</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo ' <p class="empty">No products added yet!</p>';
            }
            ?>

        </div>
    </section>

    <script src="../js/admin.js"></script>
</body>

</html>