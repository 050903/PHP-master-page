<?php
include 'includes/header.php';
require_once 'includes/db_config.php';
$artistName = $_GET['name'] ?? '';
$bio = '';
$desiredArtistsAndSongs = [
    "Khánh Ly" => ["Hạ trắng"],
    "Quang Dũng" => ["Tiếng Gà Trưa", "Cát bụi", "Diễm xưa"],
    "Hồng Nhung" => ["Như cánh vạc bay", "Ru em", "Cỏ xót xa đưa"],
    "Trịnh Công Sơn" => ["Con mắt còn lại"],
    "Hương Lan" => ["Chùm bông hoa khế", "Đám cưới trên đường quê", "Hồn Quê", "Tiếng xưa", "Lòng Mẹ"],
    "Thu Hiền" => ["Bèo giạt mây trôi", "Đường về hai thôn"]
];
$songs = $desiredArtistsAndSongs[$artistName] ?? null;
?>
<main class="container my-5">
  <div class="card about-card mx-auto" style="max-width: 600px; animation: card-in 1.2s cubic-bezier(.25,1.5,.5,1) both;">
    <div class="card-body text-center">
      <?php if ($songs): ?>
        <h2 class="mb-3 artist-name"><?php echo htmlspecialchars($artistName); ?></h2>
        <p class="mb-4 text-muted">(Thông tin tiểu sử nghệ sĩ sẽ cập nhật sau)</p>
        <h5 class="mb-3">Bài hát nổi bật:</h5>
        <ul class="song-list-inner">
          <?php foreach ($songs as $song): ?>
            <li><a class="song-link" href="song.php?artist=<?php echo urlencode($artistName); ?>&song=<?php echo urlencode($song); ?>"><?php echo htmlspecialchars($song); ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <h2 class="mb-3 artist-name">Không tìm thấy nghệ sĩ</h2>
        <p class="text-muted">Nghệ sĩ bạn tìm không tồn tại trong hệ thống.</p>
      <?php endif; ?>
      <a href="index.php" class="btn btn-primary hero-cta mt-3">Quay về Trang chủ</a>
    </div>
  </div>
</main>
<?php include 'includes/footer.php'; ?> 