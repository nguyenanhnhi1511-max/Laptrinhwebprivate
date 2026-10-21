<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>3.7 – Form đăng ký sinh viên</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; padding: 30px; }
        .box { background: #fff; max-width: 480px; margin: 0 auto; padding: 28px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { color: #2d7a4f; margin-bottom: 15px; }
        label { display: block; margin-top: 12px; font-weight: 500; }
        input { width: 100%; padding: 10px; margin-top: 4px; border: 1px solid #ccc; border-radius: 5px; }
        input.error { border-color: #dc3545; }
        .err { color: #dc3545; font-size: 13px; margin-top: 3px; }
        button { margin-top: 18px; width: 100%; background: #2d7a4f; color: #fff; border: none; padding: 12px; border-radius: 6px; font-size: 16px; cursor: pointer; }
        .success { margin-top: 15px; padding: 14px; background: #d4edda; border-radius: 6px; color: #155724; }
        a { display: inline-block; margin-top: 15px; color: #2d7a4f; }
    </style>
</head>
<body>
<div class="box">
    <h2>3.7 – Form đăng ký sinh viên</h2>

    <?php
    $errors = [];
    $data   = ['name' => '', 'email' => '', 'birthday' => ''];
    $success = false;

    function e($v) {
        return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data['name']     = trim($_POST['name']     ?? '');
        $data['email']    = trim($_POST['email']    ?? '');
        $data['birthday'] = trim($_POST['birthday'] ?? '');

        if ($data['name'] === '') {
            $errors['name'] = 'Vui lòng nhập họ tên.';
        }
        if ($data['email'] === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        }
        if ($data['birthday'] === '') {
            $errors['birthday'] = 'Vui lòng nhập ngày sinh.';
        }

        if (empty($errors)) {
            // Thành công → có thể redirect
            // header('Location: success.php'); exit;
            $success = true;
        }
    }
    ?>

    <?php if ($success): ?>
        <div class="success">
            Đăng ký thành công!<br>
            Họ tên: <strong><?= e($data['name']) ?></strong><br>
            Email: <strong><?= e($data['email']) ?></strong><br>
            Ngày sinh: <strong><?= e($data['birthday']) ?></strong>
        </div>
        <a href="dangky.php">Đăng ký tiếp</a>
    <?php else: ?>
        <form method="post" novalidate>
            <label>Họ tên *</label>
            <input type="text" name="name" class="<?= isset($errors['name']) ? 'error' : '' ?>"
                   value="<?= e($data['name']) ?>">
            <?php if (isset($errors['name'])): ?><div class="err"><?= $errors['name'] ?></div><?php endif; ?>

            <label>Email *</label>
            <input type="email" name="email" class="<?= isset($errors['email']) ? 'error' : '' ?>"
                   value="<?= e($data['email']) ?>">
            <?php if (isset($errors['email'])): ?><div class="err"><?= $errors['email'] ?></div><?php endif; ?>

            <label>Ngày sinh *</label>
            <input type="date" name="birthday" class="<?= isset($errors['birthday']) ? 'error' : '' ?>"
                   value="<?= e($data['birthday']) ?>">
            <?php if (isset($errors['birthday'])): ?><div class="err"><?= $errors['birthday'] ?></div><?php endif; ?>

            <button type="submit">Đăng ký</button>
        </form>
    <?php endif; ?>

    <a href="index.php">← Về trang chủ Bài 3</a>
</div>
</body>
</html>
