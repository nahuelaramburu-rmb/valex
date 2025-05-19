<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? null;

    if ($accion === 'alta') {
        $nombre = $_POST['nombre'] ?? '';
        $codigo_unico = $_POST['codigo_unico'] ?? '';
        $precio = $_POST['precio'] ?? 0;
        $ubicacion = $_POST['ubicacion'] ?? '';
        $tipo_operacion = $_POST['tipo_operacion'] ?? 'Venta/Alquiler';

        $stmt = $pdo->prepare("INSERT INTO propiedades (nombre, codigo_unico, precio, ubicacion, tipo_operacion) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $codigo_unico, $precio, $ubicacion, $tipo_operacion]);
        $propiedad_id = $pdo->lastInsertId();

        // Manejar la carga de fotos
        if (isset($_FILES['fotos']) && is_array($_FILES['fotos']['name'])) {
            $total_files = count($_FILES['fotos']['name']);
            $upload_dir = 'uploads/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['fotos']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['fotos']['tmp_name'][$i];
                    $name = basename($_FILES['fotos']['name'][$i]);
                    $filepath = $upload_dir . $name;
                    move_uploaded_file($tmp_name, $filepath);
                    $stmt_fotos = $pdo->prepare("INSERT INTO propiedad_fotos (propiedad_id, ruta_foto) VALUES (?, ?)");
                    $stmt_fotos->execute([$propiedad_id, $filepath]);
                }
            }
        }

        header("Location: admin_propiedades.php?mensaje=alta_exitosa");
        exit();

    } elseif ($accion === 'editar') {
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $codigo_unico = $_POST['codigo_unico'] ?? '';
        $precio = $_POST['precio'] ?? 0;
        $ubicacion = $_POST['ubicacion'] ?? '';
        $tipo_operacion = $_POST['tipo_operacion'] ?? 'Venta/Alquiler';

        $stmt = $pdo->prepare("UPDATE propiedades SET nombre = ?, codigo_unico = ?, precio = ?, ubicacion = ?, tipo_operacion = ? WHERE id = ?");
        $stmt->execute([$nombre, $codigo_unico, $precio, $ubicacion, $tipo_operacion, $id]);

        // Manejar la carga de nuevas fotos
        if (isset($_FILES['fotos']) && is_array($_FILES['fotos']['name'])) {
            $total_files = count($_FILES['fotos']['name']);
            $upload_dir = 'uploads/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['fotos']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['fotos']['tmp_name'][$i];
                    $name = basename($_FILES['fotos']['name'][$i]);
                    $filepath = $upload_dir . $name;
                    move_uploaded_file($tmp_name, $filepath);
                    $stmt_fotos = $pdo->prepare("INSERT INTO propiedad_fotos (propiedad_id, ruta_foto) VALUES (?, ?)");
                    $stmt_fotos->execute([$id, $filepath]);
                }
            }
        }

        header("Location: admin_propiedades.php?mensaje=edicion_exitosa");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_eliminar = $_GET['id'];
    $stmt_eliminar_fotos = $pdo->prepare("DELETE FROM propiedad_fotos WHERE propiedad_id = ?");
    $stmt_eliminar_fotos->execute([$id_eliminar]);

    $stmt_eliminar = $pdo->prepare("DELETE FROM propiedades WHERE id = ?");
    $stmt_eliminar->execute([$id_eliminar]);

    header("Location: admin_propiedades.php?mensaje=eliminacion_exitosa");
    exit();
} else {
    header("Location: admin_propiedades.php");
    exit();
}
?>