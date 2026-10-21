<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>2.9 – Máy tính điểm học phần</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 520px; margin: 0 auto; padding: 28px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; margin-bottom: 8px; }
        .sub { color: #666; margin-bottom: 18px; font-size: 14px; }
        label { display: block; margin-top: 12px; font-weight: 500; }
        input { width: 100%; padding: 10px; margin-top: 4px; border: 1px solid #ccc; border-radius: 5px; font-size: 15px; }
        input.error { border-color: #dc3545; }
        button { margin-top: 18px; background: #2d7a4f; color: #fff; border: none; padding: 12px 30px; border-radius: 6px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background: #1a5c3a; }
        .err { color: #dc3545; font-size: 13px; margin-top: 3px; }
        .result { margin-top: 22px; padding: 16px; border-radius: 8px; }
        .ok { background: #d4edda; border: 1px solid #c3e6cb; }
        .fail { background: #f8d7da; border: 1px solid #f5c6cb; }
        .result h3 { margin-bottom: 8px; }
        a { display: inline-block; margin-top: 18px; color: #2d7a4f; }
    </style>
</head>
<body>
<div class="box">
    <h2>2.9 – Máy tính điểm học phần</h2>
    <p class="sub">Công thức: 10% chuyên cần + 30% giữa kỳ + 60% cuối kỳ<br>
       Xếp loại: Giỏi (≥8) | Khá (≥6.5) | Trung bình (≥5) | Chưa đạt</p>

    <?php
    // ===== Hàm kiểm tra điểm hợp lệ =====
    function isValidScore($score): bool
    {
        return is_numeric($score) && $score >= 0 && $score <= 10;
    }

    // ===== Hàm tính điểm tổng kết =====
    function finalScore(float $cc, float $mid, float $final): float
    {
        return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
    }

    // ===== Hàm xếp loại =====
    function xepLoai(float $diem): string
    {
        if ($diem >= 8)   return 'Giỏi';
        if ($diem >= 6.5) return 'Khá';
        if ($diem >= 5)   return 'Trung bình';
        return 'Chưa đạt';
    }

    $errors = [];
    $data   = ['name' => '', 'cc' => '', 'mid' => '', 'final' => ''];
    $result = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Giữ lại dữ liệu đã nhập
        $data['name']  = trim($_POST['name']  ?? '');
        $data['cc']    = trim($_POST['cc']    ?? '');
        $data['mid']   = trim($_POST['mid']   ?? '');
        $data['final'] = trim($_POST['final'] ?? '');

        // Kiểm tra họ tên
        if ($data['name'] === '') {
            $errors['name'] = 'Vui lòng nhập họ tên.';
        }

        // Kiểm tra từng điểm
        foreach (['cc' => 'chuyên cần', 'mid' => 'giữa kỳ', 'final' => 'cuối kỳ'] as $key => $label) {
            if ($data[$key] === '') {
                $errors[$key] = "Vui lòng nhập điểm $label.";
            } elseif (!isValidScore($data[$key])) {
                $errors[$key] = "Điểm $label phải nằm trong khoảng 0–10.";
            }
        }

        // Nếu không lỗi → tính toán
        if (empty($errors)) {
            $tong = finalScore((float)$data['cc'], (float)$data['mid'], (float)$data['final']);
            $result = [
                'tong'    => $tong,
                'xepLoai' => xepLoai($tong),
            ];
        }
    }
    ?>

    <form method="post" novalidate>
        <label>Họ tên sinh viên</label>
        <input type="text" name="name"
               class="<?= isset($errors['name']) ? 'error' : '' ?>"
               value="<?= htmlspecialchars($data['name']) ?>">
        <?php if (isset($errors['name'])): ?><div class="err"><?= $errors['name'] ?></div><?php endif; ?>

        <label>Điểm chuyên cần (0–10)</label>
        <input type="number" name="cc" step="0.1"
               class="<?= isset($errors['cc']) ? 'error' : '' ?>"
               value="<?= htmlspecialchars($data['cc']) ?>">
        <?php if (isset($errors['cc'])): ?><div class="err"><?= $errors['cc'] ?></div><?php endif; ?>

        <label>Điểm giữa kỳ (0–10)</label>
        <input type="number" name="mid" step="0.1"
               class="<?= isset($errors['mid']) ? 'error' : '' ?>"
               value="<?= htmlspecialchars($data['mid']) ?>">
        <?php if (isset($errors['mid'])): ?><div class="err"><?= $errors['mid'] ?></div><?php endif; ?>

        <label>Điểm cuối kỳ (0–10)</label>
        <input type="number" name="final" step="0.1"
               class="<?= isset($errors['final']) ? 'error' : '' ?>"
               value="<?= htmlspecialchars($data['final']) ?>">
        <?php if (isset($errors['final'])): ?><div class="err"><?= $errors['final'] ?></div><?php endif; ?>

        <button type="submit">Tính điểm &amp; Xếp loại</button>
    </form>

    <?php if ($result !== null): ?>
        <div class="result <?= $result['tong'] >= 5 ? 'ok' : 'fail' ?>">
            <h3>Kết quả</h3>
            <p>Họ tên: <strong><?= htmlspecialchars($data['name']) ?></strong></p>
            <p>Điểm tổng kết: <strong><?= number_format($result['tong'], 2) ?></strong></p>
            <p>Xếp loại: <strong><?= $result['xepLoai'] ?></strong></p>
        </div>
    <?php endif; ?>

    <a href="index.php">← Về trang chủ Bài 2</a>
</div>
</body>
</html>
