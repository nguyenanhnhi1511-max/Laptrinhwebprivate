<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>2.6/2.7 – Hóa đơn học liệu</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 480px; margin: 0 auto; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; margin-bottom: 15px; }
        input { width: 100%; padding: 9px; margin: 5px 0 12px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #2d7a4f; color: #fff; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-size: 15px; }
        .result { margin-top: 18px; padding: 14px; background: #e8f5e9; border-radius: 6px; font-size: 17px; }
        a { display: inline-block; margin-top: 15px; color: #2d7a4f; }
    </style>
</head>
<body>
<div class="box">
    <h2>2.6/2.7 – Hóa đơn học liệu</h2>
    <?php
    function money(float $value): string
    {
        return number_format($value, 0, ',', '.') . ' đ';
    }

    $name  = '';
    $total = 0;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name  = trim($_POST['name'] ?? '');
        $qty   = (int)($_POST['qty'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $total = $qty * $price;
    }
    ?>
    <form method="post">
        <label>Tên tài liệu:</label>
        <input type="text" name="name" placeholder="Tên tài liệu" required
               value="<?= htmlspecialchars($name) ?>">
        <label>Số lượng:</label>
        <input type="number" name="qty" min="1" required
               value="<?= htmlspecialchars($_POST['qty'] ?? '1') ?>">
        <label>Đơn giá (VNĐ):</label>
        <input type="number" name="price" min="0" step="1000" required
               value="<?= htmlspecialchars($_POST['price'] ?? '200000') ?>">
        <button type="submit">Tính</button>
    </form>

    <?php if ($total > 0): ?>
        <div class="result">
            <?= htmlspecialchars($name) ?>: <?= money($total) ?>
        </div>
    <?php endif; ?>
    <a href="index.php">← Về trang chủ Bài 2</a>
</div>
</body>
</html>
