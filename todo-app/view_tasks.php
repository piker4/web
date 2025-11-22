<?php
require_once __DIR__ . '/includes/db.php';
$user_id = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
if (!$user_id || $user_id <= 0) {
    http_response_code(400);
    die('Некорректный ID пользователя.');
}
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$owner = $stmt->fetch();
if (!$owner) {
    http_response_code(404);
    die('Пользователь не найден.');
}
$stmt = $pdo->prepare("SELECT title, description, created_at FROM tasks WHERE user_id = ? AND is_completed = 0 ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задачи пользователя <?= htmlspecialchars($owner['username']) ?> — To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__ . '/header.php'; ?>
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4>Задачи пользователя: <strong><?= htmlspecialchars($owner['username']) ?></strong></h4>
                </div>
                <div class="card-body">
                    <?php if (empty($tasks)): ?>
                        <div class="alert alert-info">Нет активных задач.</div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($tasks as $task): ?>
                                <li class="list-group-item">
                                    <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                    <?php if (!empty($task['description'])): ?>
                                        <p class="text-muted mb-0"><?= htmlspecialchars($task['description']) ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted"><?= date('d.m.Y H:i', strtotime($task['created_at'])) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <div class="mt-3">
                        <a href="/" class="btn btn-secondary">← Назад</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include __DIR__ . '/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>