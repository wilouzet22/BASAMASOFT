<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto MVC</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">

    <!-- DataTables -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

    <!-- jQuery (requerido por DataTables) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <!-- JS propio -->
    <script src="<?= URL_BASE ?>public/js/datatable.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Barra de navegación -->
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <!-- Contenido de la vista actual -->
    <main class="container-fluid mb-5 mt-3">
        <?= $content ?>
    </main>

    <!-- Pie de página -->
    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
</body>
</html>
