<?php
// Kết nối đến cơ sở dữ liệu
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form modal
    $tenhanghoa = $_POST['tenhanghoa'];
    $nhacungcap = $_POST['nhacungcap'];

    // Xử lý file upload
    $target_dir = "uploads/";
    $imageFileType = strtolower(pathinfo($_FILES["hinhanh"]["name"], PATHINFO_EXTENSION));
    $newFileName = $tenhanghoa . '_' . date("Ymd") . '.' . $imageFileType;
    //$target_file = $target_dir . $newFileName;
    $target_file =  $newFileName;

    $uploadOk = 1;

    // Kiểm tra xem file có phải là hình ảnh không
    $check = getimagesize($_FILES["hinhanh"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Kiểm tra kích thước file
    if ($_FILES["hinhanh"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Chỉ cho phép một số định dạng file nhất định
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Kiểm tra nếu $uploadOk bằng 0
    //KHÔNG CẦN MÃ HÓA FILE
    // if ($uploadOk == 0) {
    //     echo "Sorry, your file was not uploaded.";
    // } else {
    //     if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], "uploads/".$target_file)) {
    //         echo "The file " . htmlspecialchars($newFileName) . " has been uploaded.";

    //         // Thực hiện truy vấn cập nhật dữ liệu vào cơ sở dữ liệu
    //         $sql = "INSERT INTO hanghoa (tenhanghoa, nhacungcap, hinhanh) VALUES ('$tenhanghoa', '$nhacungcap', '$target_file')";

    //         if ($conn->query($sql) === TRUE) {
    //             echo "Thêm dữ liệu thành công!";
    //             header("Location: dashboard.php");
    //         } else {
    //             echo "Lỗi: " . $conn->error;
    //         }
    //     } else {
    //         echo "Sorry, there was an error uploading your file.";
    //     }
    // }



    //ĐÃ MÃ HÓA FILE KHI XUẤT FILE ẢNH CẦN GIẢI MÃ FILE
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Mã hóa file trước khi lưu trữ
        $key = '123456789'; // Khóa mã hóa
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc')); // Vector khởi tạo
        $encryptedData = openssl_encrypt(file_get_contents($_FILES["hinhanh"]["tmp_name"]), 'aes-256-cbc', $key, 0, $iv);
        $encryptedData = base64_encode($iv . $encryptedData); // Kết hợp IV và dữ liệu mã hóa

        if (file_put_contents($target_file, $encryptedData)) {
            echo "The file " . htmlspecialchars($newFileName) . " has been uploaded and encrypted.";

            // Thực hiện truy vấn cập nhật dữ liệu vào cơ sở dữ liệu
            $sql = "INSERT INTO hanghoa (tenhanghoa, nhacungcap, hinhanh) VALUES ('$tenhanghoa', '$nhacungcap', '$newFileName')";

            if ($conn->query($sql) === TRUE) {
                echo "Thêm dữ liệu thành công!";
                header("Location: dashboard.php");
            } else {
                echo "Lỗi: " . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    // Đóng kết nối
    $conn->close();
}
?>