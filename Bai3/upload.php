<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>3.6 – Upload file an toàn</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 520px; margin: 0 auto; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; }
        input[type=file] { margin: 12px 0; }
        button { background: #2d7a4f; color: #fff; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; }
        .msg { margin-top: 15px; padding: 12px; border-radius: 6px; }
        .ok { background: #d4edda; color: #155724; }
        .err { background: #f8d7da; color: #721c24; }
        img { max-width: 200px; margin-top: 10px; border-radius: 6px; }
        a { display: inline-block; margin-top: 15px; color: #2d7a4f; }
        ul { margin: 10px 0 10px 20px; color: #555; font-size: 14px; }
    </style>
</head>
<body>
<div class="box">
    <h2>3.6 – Upload ảnh đại diện an toàn</h2>
    <p>3 kiểm tra bắt buộc:</p>
    <ul>
        <li>Kích thước tối đa (2 MB)</li>
        <li>Định dạng ảnh cho phép (jpg, png, gif, webp)</li>
        <li>Tên file mới an toàn (không tin tên gốc)</li>
    </ul>

    <?php
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $message = '';
    $msgClass = '';
    $preview = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
        $file = $_FILES['avatar'];

        // 1. Kiểm tra lỗi upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $message = 'Lỗi upload (mã ' . $file['error'] . ').';
            $msgClass = 'err';
        } else {
            // 2. Kiểm tra kích thước (max 2MB)
            $maxSize = 2 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                $message = 'File vượt quá 2 MB.';
                $msgClass = 'err';
            } else {
                // 3. Kiểm tra phần mở rộng & MIME type
                $allowedExt  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime  = $finfo->file($file['tmp_name']);

                if (!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
                    $message = 'Chỉ chấp nhận ảnh JPG, PNG, GIF, WEBP.';
                    $msgClass = 'err';
                } else {
                    // 4. Đổi tên file an toàn
                    $newName = bin2hex(random_bytes(8)) . '.' . $ext;
                    $dest    = $uploadDir . $newName;

                    if (move_uploaded_file($file['tmp_name'], $dest)) {
                        $message = 'Upload thành công! Tên file lưu: ' . $newName;
                        $msgClass = 'ok';
                        $preview  = 'uploads/' . $newName;
                    } else {
                        $message = 'Không thể lưu file.';
                        $msgClass = 'err';
                    }
                }
            }
        }
    }
    ?>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="avatar" accept="image/*" required>
        <br>
        <button type="submit">Upload</button>
    </form>

    <?php if ($message): ?>
        <div class="msg <?= $msgClass ?>"><?= htmlspecialchars($message) ?></div>
        <?php if ($preview): ?>
            <img src="<?= htmlspecialchars($preview) ?>" alt="Preview">
        <?php endif; ?>
    <?php endif; ?>

    <a href="index.php">← Về trang chủ Bài 3</a>
</div>
</body>
</html>
