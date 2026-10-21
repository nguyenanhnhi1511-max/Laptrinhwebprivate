<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>2.4 – Mảng sinh viên</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 500px; margin: 0 auto; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #2d7a4f; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        a { display: inline-block; margin-top: 15px; color: #2d7a4f; }
    </style>
</head>
<body>
<div class="box">
    <h2>2.4 – Mảng 3 sinh viên (name + score)</h2>
    <?php
    // Mảng kết hợp: mỗi sinh viên có name và score
    $students = [
        ['name' => 'An',   'score' => 8.0],
        ['name' => 'Bình', 'score' => 6.5],
        ['name' => 'Chi',  'score' => 4.5],
    ];
    ?>
    <table>
        <thead>
            <tr><th>STT</th><th>Họ tên</th><th>Điểm</th></tr>
        </thead>
        <tbody>
        <?php foreach ($students as $i => $sv): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($sv['name']) ?></td>
                <td><?= $sv['score'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="index.php">← Về trang chủ Bài 2</a>
</div>
</body>
</html>
