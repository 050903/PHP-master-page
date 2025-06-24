<?php
$servername = "localhost"; // Tên máy chủ database, thường là localhost
$username = "root";        // Tên người dùng database, mặc định của XAMPP/WAMP là root
$password = "root";            // Mật khẩu database, mặc định của XAMPP/WAMP là trống
$dbname = "shopsua";     // Tên cơ sở dữ liệu bạn đã tạo

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập bộ ký tự cho kết nối (quan trọng để hiển thị tiếng Việt đúng)
$conn->set_charset("utf8mb4");
?>