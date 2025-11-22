<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
$code = generateInviteCode($pdo, $_SESSION['user_id']);
$invite_url = "https://$_SERVER[HTTP_HOST]/invite/join.php?code=" . $code;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пригласить — To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__ . '/../header.php'; ?>
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4>Ссылка для приглашения</h4>
                </div>
                <div class="card-body">
                    <p>Отправьте эту ссылку другу, чтобы он увидел ваши задачи:</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="inviteLink" value="<?= htmlspecialchars($invite_url) ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyLink()">Копировать</button>
                    </div>
                    <div class="alert alert-info">
                        Ссылка действительна 7 дней.
                    </div>
                    <a href="/index.php" class="btn btn-secondary">Назад к задачам</a>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../footer.php'; ?>
<script>
function copyLink() {
    const input = document.getElementById('inviteLink');
    input.select();
    document.execCommand('copy');
    alert('Ссылка скопирована!');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>