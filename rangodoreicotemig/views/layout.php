<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Rango do Rei' ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <header class="header">
        <div class="header-content">
            <div class="logo">👑 Rango do Rei</div>
            <nav class="nav">
                <?php if ($_SESSION['is_admin']): ?>
                    <a href="/admin">Admin</a>
                <?php else: ?>
                    <a href="/cardapio">Cardápio</a>
                    <a href="/carrinho">Carrinho</a>
                <?php endif; ?>
                <a href="/logout">Sair (<?= $_SESSION['user_name'] ?>)</a>
            </nav>
        </div>
    </header>
    <?php endif; ?>

    <main class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <script src="/public/js/app.js"></script>
</body>
</html>