<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Âm Nhạc Đỉnh Cao</title>
  <meta name="description" content="Nghe nhạc chất lượng cao, tuyển chọn ca sĩ và bài hát hàng đầu.">
  <link rel="icon" type="image/svg+xml" href="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f3b5.svg">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="galaxy-bg"></div>
<div class="blox-3d-container">
  <div class="blox-3d blox-top-left"></div>
  <div class="blox-3d blox-top-right"></div>
  <div class="blox-3d blox-bottom-left"></div>
  <div class="blox-3d blox-bottom-right"></div>
  <div class="blox-3d blox-center-left"></div>
  <div class="blox-3d blox-center-right"></div>
</div>
<?php
// includes/header.php
$page = basename($_SERVER['PHP_SELF']);
?>
<header class="site-header py-3 mb-4">
  <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">Âm Nhạc Đỉnh Cao</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link<?php if($page=="index.php") echo " active"; ?>" href="index.php">Trang chủ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?php if($page=="about.php") echo " active"; ?>" href="about.php">Giới thiệu</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

</body>
</html> 