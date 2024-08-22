<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <h2>Hover Rows</h2>
        <div class="m-2"><button id="deleteButton" class="btn btn-danger">Xóa</button></div>
        <div class="m-2">
            <form method="POST" id="addForm" action="add_data.php" enctype="multipart/form-data">
                <label for="tenhanghoa">Tên hàng hóa:</label>
                <input type="text" name="tenhanghoa" id="tenhanghoa" required>
                <label for="nhacungcap">Nhà cung cấp:</label>
                <input type="text" name="nhacungcap" id="nhacungcap" required>
                <label for="hinhanh">Hình ảnh:</label>
                <input type="file" name="hinhanh" id="hinhanh" required>
                <button class="mt-2" type="submit">Thêm hàng hóa</button>
            </form>
        </div>

        <?php
        require_once "db.php";
        $query = "SELECT * from hanghoa";
        $result = $conn->query($query);
        if ($result->num_rows > 0) { ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tên Hàng Hóa</th>
                    <th>Nhà cung cấp</th>
                    <th>Xóa</th>
                    <th>Sửa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["tenhanghoa"] . "</td>";
                        echo "<td>" . $row["nhacungcap"] . "</td>";
                        echo "<td><a href='uploads/" . $row["hinhanh"] . "' target='_blank'><img src='uploads/" . $row["hinhanh"] . "' alt='" . $row["tenhanghoa"] . "' width='100' height='100'></a></td>";
                        echo "<td><input type='checkbox' class='delete-checkbox' value='" . $row["id"] . "'></td>";
                        echo '<td><button type="button" id ="edit_' . $row['id'] . '" class="btn btn-primary">Sửa</button></td>';
                        echo "</tr>";
                    }
                    ?>
            </tbody>
        </table>
        <?php
        } else {
            echo "Không có dữ liệu.";
        }
        ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="editForm">
                        <div class="form-group">
                            <label for="tenhanghoa">Tên Hàng Hóa</label>
                            <input type="text" class="form-control" id="tenhanghoa">
                        </div>
                        <div class="form-group">
                            <label for="nhacungcap">Nhà Cung Cấp</label>
                            <input type="text" class="form-control" id="nhacungcap">
                        </div>
                        <input type="text" class="hidden" id="id_edit">
                        <button type="submit" class="btn btn-info">Lưu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('#deleteButton').on('click', function() {
        var idsToDelete = []; // Mảng chứa các ID để xóa
        $('.delete-checkbox:checked').each(function() {
            idsToDelete.push($(this).val()); // Thêm ID được chọn vào mảng
        });
        if (idsToDelete.length === 0) {
            alert("Vui lòng chọn một ô để xóa");
            return; // Dừng xử lý nếu không có checkbox nào được chọn
        }
        // Gửi yêu cầu xóa bằng Ajax
        $.ajax({
            url: 'delete_records.php', // Đường dẫn đến file xử lý xóa
            type: 'POST',
            data: {
                ids: idsToDelete
            },
            success: function(response) {
                // Xử lý phản hồi từ máy chủ sau khi xóa thành công
                // Ví dụ: có thể làm mới bảng dữ liệu sau khi xóa
                // location.reload();
                alert(response);
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // $('#addButton').on('click', function() {
    // });



    $('.btn-primary').on('click', function() {
        var id = $(this).attr('id').split('_')[1]; // Lấy ID từ ID của nút "Sửa"
        $.ajax({
            url: 'get_data.php', // Đường dẫn đến file xử lý Ajax để lấy dữ liệu hàng hóa
            type: 'POST',
            data: {
                id: id
            },
            success: function(response) {
                var data = JSON.parse(response);
                // Điền dữ liệu vào modal
                $('#tenhanghoa').val(data.tenhanghoa);
                $('#nhacungcap').val(data.nhacungcap);
                $('#id_edit').val(data.id);
                // Hiển thị modal
                $('#editModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault(); // Ngăn chặn form submit mặc định

        // var id = $(this).attr('id_edit'); // Lấy ID từ data-id đã thiết lập trước đó
        var id = $('#id_edit').val(); // Lấy giá trị mới từ form
        var tenhanghoa = $('#tenhanghoa').val(); // Lấy giá trị mới từ form
        var nhacungcap = $('#nhacungcap').val();

        // Gửi yêu cầu cập nhật bằng Ajax
        $.ajax({
            url: 'update_data.php', // Đường dẫn đến file xử lý Ajax để cập nhật dữ liệu hàng hóa
            type: 'POST',
            data: {
                id: id,
                tenhanghoa: tenhanghoa,
                nhacungcap: nhacungcap
            },
            success: function(response) {
                alert('Cập nhật thành công');
                $('#editModal').modal('hide'); // Đóng modal sau khi cập nhật thành công
                location.reload(); // Tải lại trang để cập nhật dữ liệu hiển thị
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
    // $('#addForm').on('submit', function(e) {
    //     e.preventDefault(); // Ngăn chặn form submit mặc định

    //     // var id = $(this).attr('id_edit'); // Lấy ID từ data-id đã thiết lập trước đó

    //     var tenhanghoa = $('#tenhanghoa').val(); // Lấy giá trị mới từ form
    //     var nhacungcap = $('#nhacungcap').val();

    //     // Gửi yêu cầu cập nhật bằng Ajax
    //     if (tenhanghoa != '' && nhacungcap != '') {
    //         $.ajax({
    //             url: 'add_data.php', // Đường dẫn đến file xử lý Ajax để cập nhật dữ liệu hàng hóa
    //             type: 'POST',
    //             data: {
    //                 tenhanghoa: tenhanghoa,
    //                 nhacungcap: nhacungcap,
    //                 hinhanh: hinhanh,
    //             },
    //             success: function(response) {
    //                 alert('Cập nhật thành công');

    //                 location.reload(); // Tải lại trang để cập nhật dữ liệu hiển thị
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error(xhr.responseText);
    //             }
    //         });
    //     } else {
    //         alert('Vui lòng nhập thông tin đầy đủ các trường!');
    //     }
    // });
});
</script>

</html>