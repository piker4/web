<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
$code = $_GET['code'] ?? null;
$error = '';
$inviter = null;
if ($code) {
    $stmt = $pdo->prepare("
        SELECT u.username, i.user_id 
        FROM invites i 
        JOIN users u ON i.user_id = u.id 
        WHERE i.invite_code = ? AND i.expires_at > datetime('now')
    ");
    $stmt->execute([$code]);
    $inviter = $stmt->fetch();
    if (!$inviter) {
        $error = "Неверная или просроченная ссылка.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Присоединиться — To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__ . '/../header.php'; ?>
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center bg-info text-white">
                    <h4>Приглашение</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <a href="/" class="btn btn-primary">На главную</a>
                    <?php elseif ($inviter): ?>
                        <p>Пользователь <strong><?= htmlspecialchars($inviter['username']) ?></strong> приглашает вас посмотреть его задачи.</p>
                        <div class="alert alert-warning">
                            Эта функция пока только для просмотра (в будущем — общий список).
                        </div>
                        <a href="/view_tasks.php?user=<?= $inviter['user_id'] ?>" class="btn btn-success">Посмотреть задачи</a>
                        <a href="/" class="btn btn-secondary ms-2">Отмена</a>
                    <?php else: ?>
                        <div class="alert alert-warning">Ссылка не указана.</div>
                        <a href="/" class="btn btn-primary">На главную</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>