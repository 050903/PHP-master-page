<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // Tên người dùng MySQL của bạn
$password = "root";     // Mật khẩu MySQL của bạn
$dbname = "userphp"; // Tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập mã hóa ký tự để hiển thị tiếng Việt đúng
$conn->set_charset("utf8");

// Định nghĩa danh sách các ca sĩ và bài hát cụ thể theo mẫu bạn gửi
// Mảng này cũng dùng để duy trì thứ tự hiển thị
$desiredArtistsAndSongs = [
    "Khánh Ly" => ["Hạ trắng"],
    "Quang Dũng" => ["Tiếng Gà Trưa", "Cát bụi", "Diễm xưa"],
    "Hồng Nhung" => ["Như cánh vạc bay", "Ru em", "Cỏ xót xa đưa"],
    "Trịnh Công Sơn" => ["Con mắt còn lại"],
    "Hương Lan" => ["Chùm bông hoa khế", "Đám cưới trên đường quê", "Hồn Quê", "Tiếng xưa", "Lòng Mẹ"],
    "Thu Hiền" => ["Bèo giạt mây trôi", "Đường về hai thôn"]
];

$artistNames = array_keys($desiredArtistsAndSongs); // Lấy tên các ca sĩ cần tìm

// Chuẩn bị các placeholder cho câu lệnh SQL IN
$placeholders = implode(",", array_fill(0, count($artistNames), "?")); 

// Tạo một chuỗi các tên ca sĩ được bao quanh bởi dấu nháy đơn cho FIELD()
$fieldOrder = implode(", ", array_map(function($val){ return "'" . $val . "'"; }, $artistNames));

// Câu truy vấn SQL để lấy các bài hát và thông tin ca sĩ cụ thể
// Bao gồm urlBH để tạo hyperlink
$sql = "SELECT
            b.TenBH,
            b.urlBH,
            c.HoTenCS
        FROM
            webnhac_baihat AS b
        JOIN
            webnhac_casi AS c ON b.idCS = c.idCS
        WHERE
            c.HoTenCS IN ($placeholders)
        ORDER BY
            FIELD(c.HoTenCS, $fieldOrder), -- Sắp xếp theo thứ tự ca sĩ trong mảng PHP
            b.TenBH"; // Sắp xếp bài hát theo tên (hoặc bạn có thể thêm FIELD cho bài hát nếu muốn thứ tự cụ thể)

// Chuẩn bị statement để tránh SQL Injection
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Lỗi chuẩn bị truy vấn: " . $conn->error);
}

// Bind các tham số (tên ca sĩ)
$types = str_repeat('s', count($artistNames)); // Tất cả đều là string
$stmt->bind_param($types, ...$artistNames);

// Thực thi truy vấn
$stmt->execute();
$result = $stmt->get_result();

$rawSongsFromDb = []; // Mảng tạm để lưu trữ kết quả từ DB
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rawSongsFromDb[] = $row;
    }
}

// Đóng statement và kết nối
$stmt->close();
$conn->close();

// Tạo cấu trúc dữ liệu cuối cùng theo thứ tự và bộ lọc mong muốn
$finalSongsByArtist = [];
foreach ($desiredArtistsAndSongs as $artist => $songs) {
    $finalSongsByArtist[$artist] = []; // Khởi tạo mảng rỗng cho mỗi ca sĩ

    foreach ($songs as $desiredSongName) {
        // Tìm bài hát và URL tương ứng trong kết quả từ database
        $found = false;
        foreach ($rawSongsFromDb as $dbRow) {
            if ($dbRow['HoTenCS'] === $artist && $dbRow['TenBH'] === $desiredSongName) {
                $finalSongsByArtist[$artist][] = [
                    'name' => $dbRow['TenBH'],
                    'url' => $dbRow['urlBH'] // Lấy URL bài hát
                ];
                $found = true;
                break; // Thoát vòng lặp khi tìm thấy bài hát
            }
        }
        // Nếu bài hát không tìm thấy trong database (ví dụ, tên không khớp hoàn toàn),
        // vẫn thêm nó vào với URL rỗng để đảm bảo hiển thị đúng cấu trúc như mẫu
        if (!$found) {
             $finalSongsByArtist[$artist][] = [
                 'name' => $desiredSongName,
                 'url' => '#' // Hoặc một URL mặc định khác nếu không tìm thấy
             ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Bài hát theo Ca sĩ</title>
    <style>
        body {
            font-family: serif; /* Phông chữ mặc định serif để gần với Times New Roman */
            margin: 20px;
            background-color: #f4f4f4;
            display: flex; /* Dùng flexbox để căn giữa khối chính */
            justify-content: center;
        }
        .main-container {
            background-color: #fff;
            border: 1px solid #c0c0c0; /* Viền màu xám nhẹ như mẫu */
            padding: 15px 20px; /* Padding lớn hơn cho toàn bộ khung */
            width: 350px; /* Điều chỉnh độ rộng để gần với mẫu */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05); /* Đổ bóng nhẹ */
            line-height: 1.5; /* Khoảng cách dòng */
        }
        .artist-list-outer {
            list-style-type: disc; /* Dấu chấm đen cho ca sĩ */
            padding-left: 20px; /* Thụt lề cho danh sách ca sĩ */
            margin: 0; /* Bỏ margin mặc định */
        }
        .artist-list-outer li {
            margin-bottom: 5px; /* Khoảng cách giữa các ca sĩ */
        }
        .artist-name {
            font-weight: bold;
            color: #000;
        }
        .song-list-inner {
            list-style-type: circle; /* Dấu chấm tròn rỗng cho bài hát */
            padding-left: 20px; /* Thụt lề cho danh sách bài hát */
            margin-top: 5px; /* Khoảng cách từ tên ca sĩ đến bài hát */
            margin-bottom: 5px; /* Khoảng cách dưới cùng của danh sách bài hát */
        }
        .song-list-inner li {
            margin-bottom: 2px; /* Khoảng cách giữa các bài hát */
        }
        .song-list-inner a {
            color: #0000FF; /* Màu xanh dương cho hyperlink */
            text-decoration: underline; /* Gạch chân hyperlink */
            font-size: 0.9em; /* Kích thước chữ bài hát nhỏ hơn */
        }
    </style>
</head>
<body>
    <div class="main-container">
        <ul class="artist-list-outer">
            <?php
            foreach ($finalSongsByArtist as $artist => $songs) {
                echo '<li>';
                echo '<span class="artist-name">' . htmlspecialchars($artist) . '</span>';
                if (!empty($songs)) {
                    echo '<ul class="song-list-inner">';
                    foreach ($songs as $song) {
                        // Tạo hyperlink cho bài hát
                        echo '<li><a href="' . htmlspecialchars($song['url']) . '">' . htmlspecialchars($song['name']) . '</a></li>';
                    }
                    echo '</ul>';
                }
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</body>
</html>