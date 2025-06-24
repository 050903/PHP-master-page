<?php
// index.php
require_once 'includes/db_config.php'; // Nhúng file cấu hình database
include 'includes/header.php';

// --- Logic xử lý dữ liệu ---

// Danh sách các ca sĩ và bài hát cụ thể theo mẫu đã gửi
// Chúng ta sẽ truy vấn và lọc dựa trên danh sách này để đảm bảo hiển thị đúng
$desiredArtistsAndSongs = [
    "Khánh Ly" => ["Hạ trắng"],
    "Quang Dũng" => ["Tiếng Gà Trưa", "Cát bụi", "Diễm xưa"],
    "Hồng Nhung" => ["Như cánh vạc bay", "Ru em", "Cỏ xót xa đưa"],
    "Trịnh Công Sơn" => ["Con mắt còn lại"],
    "Hương Lan" => ["Chùm bông hoa khế", "Đám cưới trên đường quê", "Hồn Quê", "Tiếng xưa", "Lòng Mẹ"],
    "Thu Hiền" => ["Bèo giạt mây trôi", "Đường về hai thôn"]
];

$artistNames = array_keys($desiredArtistsAndSongs); 

// Xử lý tìm kiếm
$searchTerm = $_GET['search'] ?? ''; // Lấy từ khóa tìm kiếm từ URL

$songsByArtist = [];

// TẠO THAM SỐ ĐỊNH DANH CHO IN CLAUSE
$inParameters = [];
foreach ($artistNames as $key => $name) {
    $inParameters[] = ":artistName" . $key; // Ví dụ: :artistName0, :artistName1, ...
}
$placeholders = implode(",", $inParameters); 

// Tạo một chuỗi các tên ca sĩ được bao quanh bởi dấu nháy đơn cho FIELD()
// Phần này vẫn cần tên trực tiếp trong SQL, không phải placeholder
$fieldOrder = implode(", ", array_map(function($val){ return "'" . $val . "'"; }, $artistNames));

// Câu truy vấn SQL để lấy các bài hát và thông tin ca sĩ cụ thể
// Bao gồm urlBH để tạo hyperlink
// Thêm điều kiện tìm kiếm nếu có
$sql = "SELECT
            b.TenBH,
            b.urlBH,
            c.HoTenCS
        FROM
            webnhac_baihat AS b
        JOIN
            webnhac_casi AS c ON b.idCS = c.idCS
        WHERE
            c.HoTenCS IN ($placeholders)"; // Dùng tham số định danh ở đây

if (!empty($searchTerm)) {
    // Vẫn dùng tham số định danh cho tìm kiếm
    $sql .= " AND (b.TenBH LIKE :searchTerm OR c.HoTenCS LIKE :searchTerm)";
}

$sql .= " ORDER BY
            FIELD(c.HoTenCS, $fieldOrder), 
            b.TenBH";

try {
    $stmt = $pdo->prepare($sql);

    // BIND CÁC THAM SỐ CHO IN CLAUSE (sử dụng bindValue và tên tham số)
    foreach ($artistNames as $key => $name) {
        $stmt->bindValue(":artistName" . $key, $name, PDO::PARAM_STR);
    }

    // Bind tham số tìm kiếm nếu có
    if (!empty($searchTerm)) {
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->bindValue(':searchTerm', $likeTerm, PDO::PARAM_STR);
    }

    $stmt->execute();
    $rawSongsFromDb = $stmt->fetchAll();

} catch (PDOException $e) {
    // Xử lý lỗi một cách thân thiện hơn trong môi trường production
    error_log("Database error: " . $e->getMessage()); // Ghi lỗi vào log
    die("Có lỗi xảy ra khi truy vấn dữ liệu. Vui lòng thử lại sau."); // Thông báo cho người dùng
}

// Tạo cấu trúc dữ liệu cuối cùng theo thứ tự và bộ lọc mong muốn (đảm bảo đúng thứ tự bài hát)
$finalSongsByArtist = [];
foreach ($desiredArtistsAndSongs as $artist => $songs) {
    $finalSongsByArtist[$artist] = []; 
    foreach ($songs as $desiredSongName) {
        $found = false;
        foreach ($rawSongsFromDb as $dbRow) {
            // Kiểm tra khớp tên ca sĩ VÀ tên bài hát
            if ($dbRow['HoTenCS'] === $artist && $dbRow['TenBH'] === $desiredSongName) {
                // Thêm điều kiện tìm kiếm bổ sung nếu từ khóa không rỗng
                if (empty($searchTerm) || 
                    (stripos($dbRow['TenBH'], $searchTerm) !== false || stripos($dbRow['HoTenCS'], $searchTerm) !== false)) {
                    
                    $finalSongsByArtist[$artist][] = [
                        'name' => $dbRow['TenBH'],
                        'url' => $dbRow['urlBH']
                    ];
                    $found = true;
                    // Không break ở đây nếu có nhiều bài hát cùng tên nhưng khác URL/metadata
                    // Tuy nhiên, với dữ liệu hiện tại thì break an toàn
                    break;
                }
            }
        }
    }
    // Nếu sau khi lọc, ca sĩ không có bài hát nào khớp với tìm kiếm, xóa ca sĩ đó khỏi danh sách hiển thị
    // Hoặc nếu searchTerm không rỗng và ca sĩ đó không có bài hát nào khớp
    if (empty($finalSongsByArtist[$artist]) && !empty($searchTerm)) {
        unset($finalSongsByArtist[$artist]);
    }
}
?>

<main>
    <section class="container mb-5">
        <form class="d-flex mb-4 search-form" role="search" method="GET" action="index.php">
            <input class="form-control me-2" type="search" placeholder="Tìm kiếm bài hát hoặc ca sĩ..." aria-label="Search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </form>
        <div class="card shadow-sm" id="music-list">
            <div class="card-body p-4">
                <?php if (empty($finalSongsByArtist) && !empty($searchTerm)): ?>
                    <p class="text-center text-muted">Không tìm thấy bài hát/ca sĩ nào với từ khóa "<?php echo htmlspecialchars($searchTerm); ?>".</p>
                <?php elseif (empty($finalSongsByArtist)): ?>
                    <p class="text-center text-muted">Không có dữ liệu bài hát để hiển thị.</p>
                <?php else: ?>
                    <ul class="artist-list-outer">
                        <?php
                        foreach ($finalSongsByArtist as $artist => $songs) {
                            if (!empty($songs)) {
                                echo '<li>';
                                echo '<span class="artist-name"><a class="song-link" href="artist.php?name=' . urlencode($artist) . '">' . htmlspecialchars($artist) . '</a></span>';
                                echo '<ul class="song-list-inner">';
                                foreach ($songs as $song) {
                                    $fullUrl = 'audio/' . $song['url'];
                                    $songName = htmlspecialchars($song['name']);
                                    $artistUrl = urlencode($artist);
                                    $songUrl = urlencode($song['name']);
                                    echo '<li><a href="song.php?artist=' . $artistUrl . '&song=' . $songUrl . '" class="song-link" data-title="' . $songName . '">' . $songName . '</a></li>';
                                }
                                echo '</ul>';
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php include 'includes/footer.php'; ?>