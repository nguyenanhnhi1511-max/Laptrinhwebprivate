<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>3.2 – Validation / Sanitization / Output encoding</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 520px; margin: 0 auto; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; }
        input { width: 100%; padding: 10px; margin: 8px 0 12px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #2d7a4f; color: #fff; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; }
        .step { background: #f8f9fa; padding: 10px 14px; margin: 6px 0; border-radius: 5px; font-family: monospace; font-size: 14px; }
        .ok { background: #d4edda; }
        .err { background: #f8d7da; }
        a { display: inline-block; margin-top: 15px; color: #2d7a4f; }
    </style>
</head>
<body>
<div class="box">
    <h2>3.2 – Xử lý Email</h2>
    <p>Ví dụ: <code>A@Example.COM</code> cần xử lý những bước nào?</p>

    <form method="post">
        <label>Nhập email:</label>
        <input type="text" name="email" placeholder="A@Example.COM"
               value="<?= htmlspecialchars($_POST['email'] ?? 'A@Example.COM') ?>">
        <button type="submit">Xử lý</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $raw = $_POST['email'] ?? '';

        // 1. Sanitization: trim khoảng trắng
        $step1 = trim($raw);
        echo '<div class="step">1. Trim: <strong>' . htmlspecialchars($step1) . '</strong></div>';

        // 2. Có thể chuyển chữ thường (nếu quy ước hệ thống)
        $step2 = strtolower($step1);
        echo '<div class="step">2. Lowercase: <strong>' . htmlspecialchars($step2) . '</strong></div>';

        // 3. Validation: kiểm tra định dạng email
        $valid = filter_var($step2, FILTER_VALIDATE_EMAIL);
        if ($valid) {
            echo '<div class="step ok">3. Validate: Hợp lệ ✓</div>';
        } else {
            echo '<div class="step err">3. Validate: Không hợp lệ ✗</div>';
        }

        // 4. Output encoding khi hiển thị
        echo '<div class="step">4. Encode khi hiển thị: <strong>' . htmlspecialchars($step2, ENT_QUOTES, 'UTF-8') . '</strong></div>';
    }
    ?>
    <a href="index.php">← Về trang chủ Bài 3</a>
</div>
</body>
</html>
