<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">📝 To-Do List</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/invite/create.php">Пригласить</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/privacy.php">Приватность</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/logout.php">Выйти</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/login.php">Вход</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/register.php">Регистрация</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>