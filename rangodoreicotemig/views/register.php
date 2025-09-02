<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Rango do Rei</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">👑 Cadastro</h1>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register">
                <div class="form-group">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Cadastrar</button>
            </form>

            <div class="auth-link">
                <p>Já tem conta? <a href="/login">Faça login aqui</a></p>
            </div>
        </div>
    </div>

    <script src="/public/js/app.js"></script>
</body>
</html>