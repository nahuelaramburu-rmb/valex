<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'conexion.php';

// Obtener la información de la propiedad a editar
$id_propiedad = $_GET['id'] ?? null;

if (!$id_propiedad || !is_numeric($id_propiedad)) {
    header('Location: admin_propiedades.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id = ?");
$stmt->execute([$id_propiedad]);
$propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$propiedad) {
    header('Location: admin_propiedades.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Propiedad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 30px auto; }
        h1 { color: #343a40; text-align: center; margin-bottom: 30px; }
        .form-label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="form-container">
            <h1>Editar Propiedad</h1>
            <form action="procesar_propiedad.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Propiedad:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($propiedad['nombre']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="codigo_unico" class="form-label">Código Único:</label>
                    <input type="text" class="form-control" id="codigo_unico" name="codigo_unico" value="<?php echo htmlspecialchars($propiedad['codigo_unico']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="text" class="form-control" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($propiedad['precio']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion"><?php echo htmlspecialchars($propiedad['descripcion']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="fotos" class="form-label">Fotos (selecciona nuevas para agregar):</label>
                    <input type="file" class="form-control" id="fotos" name="fotos[]" multiple>
                    <small class="form-text text-muted">Puedes seleccionar múltiples fotos para agregar. Las fotos existentes se gestionarán en otra sección si es necesario.</small>
                </div>

                <div class="mb-3">
                    <label for="ubicacion" class="form-label">Ubicación:</label>
                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($propiedad['ubicacion']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="tipo_operacion" class="form-label">Tipo Operación:</label>
                    <select class="form-select" id="tipo_operacion" name="tipo_operacion" required>
                        <option value="Venta" <?php if ($propiedad['tipo_operacion'] === 'Venta') echo 'selected'; ?>>Venta</option>
                        <option value="Alquiler" <?php if ($propiedad['tipo_operacion'] === 'Alquiler') echo 'selected'; ?>>Alquiler</option>
                        <option value="Venta/Alquiler" <?php if ($propiedad['tipo_operacion'] === 'Venta/Alquiler') echo 'selected'; ?>>Venta/Alquiler</option>
                    </select>
                </div>
                </br>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="admin_propiedades.php" class="btn btn-secondary">Volver al Panel</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>