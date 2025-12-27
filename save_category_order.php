<?php
include 'config.php';

if (isset($_POST['order'])) {
    foreach ($_POST['order'] as $item) {
        $id = $item['id'];
        $position = $item['position'];

        $stmt = $conn->prepare("UPDATE categories SET position = ? WHERE id = ?");
        $stmt->bind_param("ii", $position, $id);
        $stmt->execute();
    }
    echo 'success';
} else {
    echo 'error';
}
?>
