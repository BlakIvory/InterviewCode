<?php
require_once "db.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $inputEmail = $_POST['inputEmail'];
    $inputPassword = $_POST['inputPassword'];

    // Mã hóa mật khẩu (để so sánh với dữ liệu đã được mã hóa trong cơ sở dữ liệu)
    $hashedPassword = md5($inputPassword); // Đây chỉ là ví dụ, bạn nên sử dụng phương pháp mã hóa an toàn hơn



    // Truy vấn kiểm tra thông tin đăng nhập trong cơ sở dữ liệu
    $sql = "SELECT * FROM login WHERE username = '$inputEmail' AND password = '$inputPassword'";
    $result = $conn->query($sql);


    if ($result->num_rows == 1) {
        // Nếu thông tin đăng nhập đúng, chuyển hướng đến trang dashboard.php
        echo "<script>alert('Login success!')
            window.location.href = 'dashboard.php'
        </script>" ;
    } else {
        // Nếu thông tin không đúng, chuyển hướng trở lại trang đăng nhập
        // header("Location: index.php");
        echo "<script>alert('Login failed asasadsadsa')
            window.location.href = 'index.php'
        </script>" ; 
        // exit;
    }
    
}