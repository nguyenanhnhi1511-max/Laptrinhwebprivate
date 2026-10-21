<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>2.5 – Hàm tính điểm tổng kết</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 480px; margin: 0 auto; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; margin-bottom: 15px; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 9px; margin: 5px 0 10px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #2d7a4f; color: #fff; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; }
        .result { margin-top: 18px; padding: 12px; background: #e8f5e9; border-radius: 6px; font-weight: 600; }
        a { display: inline-block; margin-top: 15px; color: #2d7a4f; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="box">
    <h2>2.5 – Hàm tính điểm tổng kết</h2>
    <p>Công thức: 10% chuyên cần + 30% giữa kỳ + 60% cuối kỳ</p>
    <?php
    /**
     * Tính điểm tổng kết
     * @param float $cc  Điểm chuyên cần
     * @param float $mid Điểm giữa kỳ
     * @param float $final Điểm cuối kỳ
     * @return float
     */
    function finalScore(float $cc, float $mid, float $final): float
    {
        return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
    }

    $ketqua = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cc  = (float)($_POST['cc'] ?? 0);
        $mid = (float)($_POST['mid'] ?? 0);
        $fi  = (float)($_POST['final'] ?? 0);
        $ketqua = finalScore($cc, $mid, $fi);
    }
    ?>
    <form method="post">
        <label>Điểm chuyên cần (0–10):</label>
        <input type="number" name="cc" step="0.1" min="0" max="10" required
               value="<?= htmlspecialchars($_POST['cc'] ?? '8') ?>">
        <label>Điểm giữa kỳ (0–10):</label>
        <input type="number" name="mid" step="0.1" min="0" max="10" required
               value="<?= htmlspecialchars($_POST['mid'] ?? '7') ?>">
        <label>Điểm cuối kỳ (0–10):</label>
        <input type="number" name="final" step="0.1" min="0" max="10" required
               value="<?= htmlspecialchars($_POST['final'] ?? '9') ?>">
        <button type="submit">Tính điểm</button>
    </form>
    <?php if ($ketqua !== null): ?>
        <div class="result">Điểm tổng kết: <?= number_format($ketqua, 2) ?></div>
    <?php endif; ?>
    <a href="index.php">← Về trang chủ Bài 2</a>
</div>
</body>
</html>
