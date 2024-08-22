<?php
// Kết nối đến cơ sở dữ liệu
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form modal
    $id = $_POST['id'];
    $tenhanghoa = $_POST['tenhanghoa'];
    $nhacungcap = $_POST['nhacungcap'];

    // Thực hiện truy vấn cập nhật dữ liệu vào cơ sở dữ liệu
    $sql = "UPDATE hanghoa SET tenhanghoa = '$tenhanghoa', nhacungcap = '$nhacungcap' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Cập nhật dữ liệu thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
    
    // Đóng kết nối
    $conn->close();
}
?>