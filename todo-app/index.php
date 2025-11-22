<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;

$tasks = [];
if ($is_logged_in) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        if ($_POST['action'] === 'add_task' && $is_logged_in && !empty($_POST['title'])) {
            $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, trim($_POST['title']), trim($_POST['description'] ?? '')]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            exit;
        }
        
        if ($_POST['action'] === 'toggle_task' && $is_logged_in && !empty($_POST['id'])) {
            $stmt = $pdo->prepare("SELECT is_completed FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->execute([$_POST['id'], $user_id]);
            $task = $stmt->fetch();
            if ($task) {
                $new_status = $task['is_completed'] ? 0 : 1;
                $stmt = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$new_status, $_POST['id'], $user_id]);
                echo json_encode(['success' => true, 'completed' => (bool)$new_status]);
                exit;
            }
        }
        
        if ($_POST['action'] === 'delete_task' && $is_logged_in && !empty($_POST['id'])) {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->execute([$_POST['id'], $user_id]);
            echo json_encode(['success' => true]);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
    
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'header.php'; ?>

<main class="container py-4">
    <?php if (!$is_logged_in): ?>
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="mb-4">Добро пожаловать в To-Do List!</h1>
                <p class="lead">Организуйте свои задачи эффективно.</p>
                <div class="mt-4">
                    <a href="/auth/login.php" class="btn btn-primary btn-lg me-2">Войти</a>
                    <a href="/auth/register.php" class="btn btn-outline-secondary btn-lg">Регистрация</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h2 class="mb-4">Ваши задачи</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="addTaskForm">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="taskTitle" placeholder="Название задачи" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="taskDescription" placeholder="Описание (необязательно)"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Добавить задачу</button>
                        </form>
                    </div>
                </div>
                <div id="tasksList">
                    <?php if (empty($tasks)): ?>
                        <div class="alert alert-info">Список задач пуст. Добавьте первую!</div>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($tasks as $task): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start <?= $task['is_completed'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><?= htmlspecialchars($task['title']) ?></div>
                                        <?php if (!empty($task['description'])): ?>
                                            <small><?= htmlspecialchars($task['description']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm <?= $task['is_completed'] ? 'btn-outline-success' : 'btn-success' ?> me-1 toggle-task" data-id="<?= $task['id'] ?>">
                                            <?= $task['is_completed'] ? '✔️' : '⏹' ?>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-task" data-id="<?= $task['id'] ?>">×</button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/index.js"></script>
</body>
</html> 