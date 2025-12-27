<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT c.id, c.quantity, p.name, p.price, p.image 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<h2>Your Cart</h2>
<table border="1">
    <tr>
        <th>Image</th>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>
    <?php
    $grand_total = 0;
    while($row = mysqli_fetch_assoc($result)) {
        $total = $row['price'] * $row['quantity'];
        $grand_total += $total;
        echo "<tr>
                <td><img src='uploads/{$row['image']}' width='50'></td>
                <td>{$row['name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['quantity']}</td>
                <td>$total</td>
              </tr>";
    }
    ?>
    <tr>
        <td colspan="4">Grand Total</td>
        <td><?php echo $grand_total; ?></td>
    </tr>
</table>
