<?php
session_start();
require 'conexion.php';

$stmt = $pdo->prepare("SELECT p.*, (SELECT pf.ruta_foto FROM propiedad_fotos pf WHERE pf.propiedad_id = p.id LIMIT 1) AS primera_foto FROM propiedades p WHERE p.tipo_operacion = 'Venta' OR p.tipo_operacion = 'Venta/Alquiler'");
$stmt->execute();
$propiedades_venta = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ventas - Corporacion Valex</title>
    <style>
        /* ... (tu CSS aquí) ... */
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo"><a href="index.html">Corporacion Valex</a></div>
            <ul>
                <li><a href="#about">Nosotros</a></li>
                <li>
                    <a href="#properties" aria-haspopup="true" aria-expanded="false">Propiedades ▾</a>
                    <ul>
                        <li><a href="ventas.php">Ventas</a></li>
                        <li><a href="alquileres.php">Alquileres</a></li>
                    </ul>
                </li>
                <li><a href="#services">Servicios</a></li>
                <li><a href="#contact">Contacto</a></li>
                <li><a href="login.php">Acceso</a></li>
            </ul>
        </nav>
    </header>
        </br>
</br>
<div id="titletop" style="margin-top: 30px !important;">
<h2 class="section-title mt-2">Ventas
</div>
    <section id="properties" aria-label="Listado de Propiedades en Venta">
        <div id="properties">
            <?php if (empty($propiedades_venta)): ?>
                <p>No hay propiedades en venta disponibles.</p>
            <?php else: ?>
                <?php foreach ($propiedades_venta as $propiedad): ?>
                    <article class="property-card" tabindex="0" aria-label="<?php echo htmlspecialchars($propiedad['nombre']); ?>">
                        <img src="<?php echo htmlspecialchars($propiedad['primera_foto'] ?? 'https://via.placeholder.com/350x200'); ?>" alt="<?php echo htmlspecialchars($propiedad['nombre']); ?>" class="property-image" style="height: 180px; object-fit: cover;" />
                        <?php
                            $tipo_operacion_clase = strtolower(str_replace(' ', '', $propiedad['tipo_operacion']));
                            echo '<div class="property-label ' . htmlspecialchars($tipo_operacion_clase) . '">' . htmlspecialchars($propiedad['tipo_operacion']) . '</div>';
                        ?>
                        <div class="property-content">
                            <h3 class="property-title"><?php echo htmlspecialchars($propiedad['nombre']); ?></h3>
                            <p class="property-desc"><?php echo htmlspecialchars($propiedad['descripcion']); ?></p>
                            <p class="property-price"><?php echo htmlspecialchars($propiedad['precio']); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <Style>
            .texto-blanco {
    color: white !important;
}
                </style>
        <p>© 2025 -  Corporaciones Valex. Todos los derechos reservados.</p>
        <p> Desarrollado por <a class="texto-blanco" href="www.rmbcorp.com">RMBCORP </a></p>
    </footer>

</body>
</html>