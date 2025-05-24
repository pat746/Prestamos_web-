<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title><?= $title ?? 'Sistema de Préstamos' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        nav {
            background-color: #343a40;
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        nav .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 12px;
            font-weight: bold;
        }
        nav .nav-links a:hover,
        nav .nav-links a.active {
            text-decoration: underline;
        }
        .container {
            padding: 30px;
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">Sistema de Préstamos</div>
    <div class="nav-links">
        <a href="/Prestamos_web/public/views/beneficiarios/index.php" class="<?= ($active === 'beneficiarios') ? 'active' : '' ?>">Beneficiarios</a>
        <a href="/Prestamos_web/public/views/contratos/indexContrato.php" class="<?= ($active === 'contratos') ? 'active' : '' ?>">Contratos</a>
        <a href="/Prestamos_web/public/views/pagos/index.php" class="<?= ($active === 'pagos') ? 'active' : '' ?>">Pagos</a>
    </div>
</nav>

<div class="container">
    <?= $content ?? '' ?>
</div>

</body>
</html>
