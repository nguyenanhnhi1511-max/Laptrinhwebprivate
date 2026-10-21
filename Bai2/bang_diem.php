<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>2.8 – Bảng điểm 3 sinh viên</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 700px; margin: 0 auto; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #2d7a4f; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        .dat { color: #155724; font-weight: 600; }
        .chuadat { color: #721c24; font-weight: 600; }
        a { display: inline-block; margin-top: 15px; color: #2d7a4f; }
    </style>
</head>
<body>
<div class="box">
    <h2>2.8 – Bảng điểm 3 sinh viên</h2>
    <?php
    // 1. Tạo mảng $students
    $students = [
        ['name' => 'Nguyễn Văn An',  'midterm' => 7.5, 'final' => 8.0],
        ['name' => 'Trần Thị Bình',  'midterm' => 5.0, 'final' => 4.5],
        ['name' => 'Lê Hoàng Chi',   'midterm' => 6.0, 'final' => 7.0],
    ];

    // 2. Hàm tính điểm trung bình
    function calculateAverage(float $mid, float $final): float
    {
        return ($mid + $final) / 2;
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Giữa kỳ</th>
                <th>Cuối kỳ</th>
                <th>Trung bình</th>
                <th>Kết quả</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $i => $sv):
            $avg = calculateAverage($sv['midterm'], $sv['final']);
            $pass = $avg >= 5;
        ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td style="text-align:left"><?= htmlspecialchars($sv['name']) ?></td>
                <td><?= $sv['midterm'] ?></td>
                <td><?= $sv['final'] ?></td>
                <td><?= number_format($avg, 2) ?></td>
                <td class="<?= $pass ? 'dat' : 'chuadat' ?>">
                    <?= $pass ? 'Đạt' : 'Chưa đạt' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="index.php">← Về trang chủ Bài 2</a>
</div>
</body>
</html>
