<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nueva Propiedad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 30px auto;
        }
        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="form-container">
            <h1>Agregar Nueva Propiedad</h1>
            <form action="procesar_propiedad.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="alta">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Propiedad:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="mb-3">
                    <label for="codigo_unico" class="form-label">Código Único:</label>
                    <input type="text" class="form-control" id="codigo_unico" name="codigo_unico" required>
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="text" class="form-control" id="precio" name="precio" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                </div>

                <div class="mb-3">
                    <label for="fotos" class="form-label">Fotos (puedes seleccionar múltiples):</label>
                    <input type="file" class="form-control" id="fotos" name="fotos[]" multiple>
                </div>

                <div class="mb-3">
                    <label for="ubicacion" class="form-label">Ubicación:</label>
                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                </div>

                <div class="mb-3">
                    <label for="tipo_operacion" class="form-label">Tipo Operación:</label>
                    <select class="form-select" id="tipo_operacion" name="tipo_operacion" required>
                        <option value="Venta">Venta</option>
                        <option value="Alquiler">Alquiler</option>
                        <option value="Venta/Alquiler" selected>Venta/Alquiler</option>
                    </select>
                </div>
    </br>
                <button type="submit" class="btn btn-primary">Guardar Propiedad</button>
                <a href="admin_propiedades.php" class="btn btn-secondary">Volver al Panel</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>