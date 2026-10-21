<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>2.3 – Xếp loại điểm</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 450px; margin: 0 auto; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; margin-bottom: 15px; }
        input[type=number] { width: 100%; padding: 10px; margin: 8px 0 15px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        button { background: #2d7a4f; color: #fff; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-size: 15px; }
        button:hover { background: #1a5c3a; }
        .result { margin-top: 20px; padding: 12px; border-radius: 6px; font-weight: 600; }
        .gioi { background: #d4edda; color: #155724; }
        .kha { background: #cce5ff; color: #004085; }
        .tb { background: #fff3cd; color: #856404; }
        .yeu { background: #f8d7da; color: #721c24; }
        a { display: inline-block; margin-top: 15px; color: #2d7a4f; }
    </style>
</head>
<body>
<div class="box">
    <h2>2.3 – Xếp loại điểm</h2>
    <p>Điểm ≥ 8: Giỏi | ≥ 6.5: Khá | ≥ 5: Trung bình | còn lại: Chưa đạt</p>
    <form method="post">
        <label>Nhập điểm (0–10):</label>
        <input type="number" name="diem" step="0.1" min="0" max="10" required
               value="<?= isset($_POST['diem']) ? htmlspecialchars($_POST['diem']) : '' ?>">
        <button type="submit">Xếp loại</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $diem = (float)($_POST['diem'] ?? -1);
        if ($diem < 0 || $diem > 10) {
            echo '<div class="result yeu">Điểm phải trong khoảng 0–10!</div>';
        } else {
            // Thứ tự điều kiện từ cao xuống thấp
            if ($diem >= 8) {
                $xl = 'Giỏi';
                $cls = 'gioi';
            } elseif ($diem >= 6.5) {
                $xl = 'Khá';
                $cls = 'kha';
            } elseif ($diem >= 5) {
                $xl = 'Trung bình';
                $cls = 'tb';
            } else {
                $xl = 'Chưa đạt';
                $cls = 'yeu';
            }
            echo "<div class='result $cls'>Điểm $diem → Xếp loại: <strong>$xl</strong></div>";
        }
    }
    ?>
    <a href="index.php">← Về trang chủ Bài 2</a>
</div>
</body>
</html>
