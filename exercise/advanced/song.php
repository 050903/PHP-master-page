<?php
include 'includes/header.php';
require_once 'includes/db_config.php';
$artistName = $_GET['artist'] ?? '';
$songName = $_GET['song'] ?? '';
$desiredArtistsAndSongs = [
    "Khánh Ly" => ["Hạ trắng"],
    "Quang Dũng" => ["Tiếng Gà Trưa", "Cát bụi", "Diễm xưa"],
    "Hồng Nhung" => ["Như cánh vạc bay", "Ru em", "Cỏ xót xa đưa"],
    "Trịnh Công Sơn" => ["Con mắt còn lại"],
    "Hương Lan" => ["Chùm bông hoa khế", "Đám cưới trên đường quê", "Hồn Quê", "Tiếng xưa", "Lòng Mẹ"],
    "Thu Hiền" => ["Bèo giạt mây trôi", "Đường về hai thôn"]
];
$songs = $desiredArtistsAndSongs[$artistName] ?? null;
$found = $songs && in_array($songName, $songs);
// Find audio url if exists
$url = '';
if ($found) {
    $sql = "SELECT urlBH FROM webnhac_baihat b JOIN webnhac_casi c ON b.idCS = c.idCS WHERE c.HoTenCS = :artist AND b.TenBH = :song LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':artist', $artistName, PDO::PARAM_STR);
    $stmt->bindValue(':song', $songName, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row && $row['urlBH']) {
        $url = 'audio/' . $row['urlBH'];
    }
}
?>
<main class="container my-5">
  <div class="card about-card mx-auto" style="max-width: 600px; animation: card-in 1.2s cubic-bezier(.25,1.5,.5,1) both;">
    <div class="card-body text-center">
      <?php if ($found): ?>
        <h2 class="mb-3 artist-name"><?php echo htmlspecialchars($songName); ?></h2>
        <p class="mb-2 text-muted">Trình bày: <a class="song-link" href="artist.php?name=<?php echo urlencode($artistName); ?>"><?php echo htmlspecialchars($artistName); ?></a></p>
        <?php if ($url): ?>
          <audio controls class="w-100 mb-3" style="max-width: 400px;">
            <source src="<?php echo htmlspecialchars($url); ?>" type="audio/mpeg">
            Trình duyệt của bạn không hỗ trợ phát nhạc.
          </audio>
        <?php else: ?>
          <p class="text-warning">Không tìm thấy file nhạc.</p>
        <?php endif; ?>
      <?php else: ?>
        <h2 class="mb-3 artist-name">Không tìm thấy bài hát</h2>
        <p class="text-muted">Bài hát bạn tìm không tồn tại trong hệ thống.</p>
      <?php endif; ?>
      <a href="index.php" class="btn btn-primary hero-cta mt-3">Quay về Trang chủ</a>
    </div>
  </div>
</main>
<?php include 'includes/footer.php'; ?> 