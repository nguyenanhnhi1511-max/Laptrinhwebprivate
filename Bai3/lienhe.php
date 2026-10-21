<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>3.8/3.9 – Form liên hệ an toàn</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #eef2f7; padding: 30px; }
        .box {
            background: #fff; max-width: 520px; margin: 0 auto; padding: 30px;
            border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        h2 { text-align: center; color: #1a3a5c; margin-bottom: 6px; }
        .sub { text-align: center; color: #888; margin-bottom: 22px; font-size: 14px; }
        label { display: block; margin-top: 14px; font-weight: 500; color: #333; }
        input[type=text], input[type=email], select, textarea {
            width: 100%; padding: 11px 14px; margin-top: 5px;
            border: 1px solid #d0d5dd; border-radius: 8px; font-size: 15px;
            transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #3b82f6; }
        input.error, select.error, textarea.error { border-color: #ef4444; }
        textarea { resize: vertical; min-height: 100px; }
        .err { color: #ef4444; font-size: 13px; margin-top: 4px; }
        .hint { color: #888; font-size: 12px; margin-top: 3px; }
        button {
            margin-top: 20px; width: 100%; padding: 13px;
            background: #2563eb; color: #fff; border: none; border-radius: 8px;
            font-size: 16px; font-weight: 600; cursor: pointer;
        }
        button:hover { background: #1d4ed8; }
        .success {
            margin-top: 18px; padding: 16px; background: #dcfce7;
            border: 1px solid #86efac; border-radius: 8px; color: #166534;
        }
        .preview { max-width: 120px; margin-top: 8px; border-radius: 6px; }
        a { display: inline-block; margin-top: 18px; color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="box">
    <h2>Liên hệ</h2>
    <p class="sub">Vui lòng nhập đầy đủ thông tin bên dưới.</p>

    <?php
    // ===== Helper encode =====
    function e(string $v): string
    {
        return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }

    // ===== Thư mục upload =====
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $errors  = [];
    $data    = [
        'name'    => '',
        'email'   => '',
        'subject' => '',
        'content' => '',
    ];
    $success = false;
    $avatarPath = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Giữ lại dữ liệu đã nhập
        $data['name']    = trim($_POST['name']    ?? '');
        $data['email']   = trim($_POST['email']   ?? '');
        $data['subject'] = trim($_POST['subject'] ?? '');
        $data['content'] = trim($_POST['content'] ?? '');

        // 1. Họ tên bắt buộc
        if ($data['name'] === '') {
            $errors['name'] = 'Vui lòng nhập họ tên.';
        } elseif (mb_strlen($data['name']) < 2) {
            $errors['name'] = 'Họ tên quá ngắn.';
        }

        // 2. Email bắt buộc + định dạng
        if ($data['email'] === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }

        // 3. Chủ đề
        $allowedSubjects = ['Hỗ trợ kỹ thuật', 'Góp ý sản phẩm', 'Hợp tác', 'Khác'];
        if ($data['subject'] === '' || !in_array($data['subject'], $allowedSubjects, true)) {
            $errors['subject'] = 'Vui lòng chọn chủ đề.';
        }

        // 4. Nội dung: 10–500 ký tự
        $len = mb_strlen($data['content']);
        if ($data['content'] === '') {
            $errors['content'] = 'Vui lòng nhập nội dung.';
        } elseif ($len < 10) {
            $errors['content'] = 'Nội dung phải từ 10 ký tự trở lên.';
        } elseif ($len > 500) {
            $errors['content'] = 'Nội dung không quá 500 ký tự.';
        }

        // 5. Upload ảnh đại diện (3 kiểm tra bắt buộc)
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors['avatar'] = 'Vui lòng chọn ảnh đại diện.';
        } else {
            $file = $_FILES['avatar'];

            // Kiểm tra lỗi upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors['avatar'] = 'Lỗi upload file (mã ' . $file['error'] . ').';
            } else {
                // a) Kích thước tối đa 2 MB
                if ($file['size'] > 2 * 1024 * 1024) {
                    $errors['avatar'] = 'Ảnh không được vượt quá 2 MB.';
                } else {
                    // b) Định dạng cho phép
                    $allowedExt  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime  = $finfo->file($file['tmp_name']);

                    if (!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
                        $errors['avatar'] = 'Chỉ chấp nhận ảnh JPG, PNG, GIF, WEBP.';
                    } else {
                        // c) Đổi tên file an toàn
                        $newName = bin2hex(random_bytes(8)) . '.' . $ext;
                        $dest    = $uploadDir . $newName;
                        if (move_uploaded_file($file['tmp_name'], $dest)) {
                            $avatarPath = 'uploads/' . $newName;
                        } else {
                            $errors['avatar'] = 'Không thể lưu file.';
                        }
                    }
                }
            }
        }

        // Nếu không có lỗi → thành công
        if (empty($errors)) {
            $success = true;
            // Thực tế: lưu DB / gửi email… Ở đây chỉ hiển thị
        }
    }
    ?>

    <?php if ($success): ?>
        <div class="success">
            <strong>Gửi liên hệ thành công!</strong><br><br>
            Họ tên: <?= e($data['name']) ?><br>
            Email: <?= e($data['email']) ?><br>
            Chủ đề: <?= e($data['subject']) ?><br>
            Nội dung: <?= e($data['content']) ?><br>
            <?php if ($avatarPath): ?>
                Ảnh đại diện:<br>
                <img class="preview" src="<?= e($avatarPath) ?>" alt="Avatar">
            <?php endif; ?>
        </div>
        <a href="lienhe.php">Gửi liên hệ mới</a>
    <?php else: ?>
        <form method="post" enctype="multipart/form-data" novalidate>
            <label>Họ tên *</label>
            <input type="text" name="name"
                   class="<?= isset($errors['name']) ? 'error' : '' ?>"
                   value="<?= e($data['name']) ?>"
                   placeholder="Lê Văn Hào">
            <?php if (isset($errors['name'])): ?><div class="err"><?= $errors['name'] ?></div><?php endif; ?>

            <label>Email *</label>
            <input type="email" name="email"
                   class="<?= isset($errors['email']) ? 'error' : '' ?>"
                   value="<?= e($data['email']) ?>"
                   placeholder="haovanle@edu.vn">
            <?php if (isset($errors['email'])): ?><div class="err"><?= $errors['email'] ?></div><?php endif; ?>

            <label>Chủ đề *</label>
            <select name="subject" class="<?= isset($errors['subject']) ? 'error' : '' ?>">
                <option value="">-- Chọn chủ đề --</option>
                <?php
                $subjects = ['Hỗ trợ kỹ thuật', 'Góp ý sản phẩm', 'Hợp tác', 'Khác'];
                foreach ($subjects as $s):
                ?>
                    <option value="<?= e($s) ?>" <?= $data['subject'] === $s ? 'selected' : '' ?>>
                        <?= e($s) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['subject'])): ?><div class="err"><?= $errors['subject'] ?></div><?php endif; ?>

            <label>Nội dung *</label>
            <textarea name="content"
                      class="<?= isset($errors['content']) ? 'error' : '' ?>"
                      placeholder="Nhập nội dung liên hệ..."><?= e($data['content']) ?></textarea>
            <div class="hint">Nội dung phải từ 10 đến 500 ký tự</div>
            <?php if (isset($errors['content'])): ?><div class="err"><?= $errors['content'] ?></div><?php endif; ?>

            <label>Ảnh đại diện *</label>
            <input type="file" name="avatar" accept="image/*">
            <div class="hint">JPG/PNG/GIF/WEBP – tối đa 2 MB</div>
            <?php if (isset($errors['avatar'])): ?><div class="err"><?= $errors['avatar'] ?></div><?php endif; ?>

            <button type="submit">Gửi liên hệ</button>
        </form>
    <?php endif; ?>

    <a href="index.php">← Về trang chủ Bài 3</a>
</div>
</body>
</html>
