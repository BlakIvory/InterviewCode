<?php
// db.php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ids'])) {
    $idsToDelete = $_POST['ids'];

    // Xóa các bản ghi có ID trong danh sách $idsToDelete
    foreach ($idsToDelete as $id) {
        $sql = "DELETE FROM hanghoa WHERE id = $id";
        $conn->query($sql);
    }
    echo "Xóa thành công!";
} else {
    echo "Lỗi trong quá trình xóa!";
}
?>