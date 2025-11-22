<!-- header.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/">To-Do List</a>
        <div class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="nav-link" href="/invite/">Пригласить</a>
                <a class="nav-link" href="/privacy.php">Приватность</a>
                <a class="nav-link" href="/auth/logout.php">Выйти</a>
            <?php else: ?>
                <a class="nav-link" href="/auth/login.php">Вход</a>
                <a class="nav-link" href="/auth/register.php">Регистрация</a>
            <?php endif; ?>
        </div>
    </div>
</nav>