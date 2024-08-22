<?php
// db.php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Thực hiện truy vấn để lấy dữ liệu của hàng hóa với ID tương ứng
    $sql = "SELECT * FROM hanghoa WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row); // Trả về dữ liệu dưới dạng JSON
    } else {
        echo "Không tìm thấy dữ liệu.";
    }
} else {
    echo "Lỗi: Yêu cầu không hợp lệ.";
}
?>