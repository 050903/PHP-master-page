<?php
include 'db_connect.php'; // Bao gồm file kết nối CSDL

// Truy vấn dữ liệu sản phẩm
$sql = "SELECT product_name, original_price, discount_price, discount_percentage, image_url, short_description FROM products";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flash Sale - Sản phẩm sữa</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="flash-sale-container">
        <h2 class="flash-sale-title">FLASH SALE</h2>
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                // Output data của mỗi hàng
                while($row = $result->fetch_assoc()) {
                    // Định dạng giá tiền Việt Nam Đồng
                    $original_price_formatted = number_format($row["original_price"], 0, ',', '.') . 'đ';
                    $discount_price_formatted = number_format($row["discount_price"], 0, ',', '.') . 'đ';
            ?>
            <div class="product-card">
                <div class="discount-badge">-<?php echo $row["discount_percentage"]; ?>%</div>
                <div class="product-image-wrapper">
                    <img src="<?php echo $row["image_url"]; ?>" alt="<?php echo $row["product_name"]; ?>">
                </div>
                <div class="product-info">
                    <p class="product-name"><?php echo $row["product_name"]; ?></p>
                    <p class="product-description"><?php echo $row["short_description"]; ?></p>
                    <div class="price-section">
                        <span class="discount-price"><?php echo $discount_price_formatted; ?></span>
                        <span class="original-price"><?php echo $original_price_formatted; ?></span>
                    </div>
                    <button class="add-to-cart-btn">Chọn mua</button>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p>Không có sản phẩm nào trong kho.</p>";
            }
            $conn->close(); // Đóng kết nối CSDL
            ?>
        </div>
    </div>
</body>
</html>