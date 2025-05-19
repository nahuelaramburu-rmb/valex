<?php
require 'conexion.php';

$stmt = $pdo->prepare("SELECT * FROM propiedades WHERE tipo_operacion = 'Alquiler' OR tipo_operacion = 'Venta/Alquiler'");
$stmt->execute();
$propiedades_alquiler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Alquileres - Corporacion Valex</title>
    <style>
        /* Reset & base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f9fc;
            scroll-behavior: smooth;
        }
        a {
            color: #0d6efd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #0a58ca;
        }

        header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            background: rgba(255,255,255,0.95);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        nav {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
        }
        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: #0d6efd;
            letter-spacing: 2px;
            user-select: none;
        }
        nav ul {
            list-style: none;
            display: flex;
        }
        nav ul li {
            margin-left: 1.5rem;
            position: relative; /* Necesario para el dropdown */
        }
        nav ul li a {
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        /* Dropdown Styles */
        nav ul li ul {
            display: none; /* Ocultar el dropdown por defecto */
            position: absolute;
            top: 100%; /* Colocar debajo del elemento padre */
            left: 0;
            background-color: white; /* Fondo blanco para el dropdown */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1000; /* Asegurarse de que esté por encima de otros elementos */
        }
        nav ul li:hover > ul {
            display: block; /* Mostrar el dropdown al pasar el mouse */
        }
        nav ul li ul li {
            margin: 0; /* Sin margen para los elementos del dropdown */
        }
        nav ul li ul li a {
            padding: 0.5rem 1rem; /* Espaciado para los enlaces del dropdown */
            white-space: nowrap; /* Evitar que el texto se divida en varias líneas */
        }
        nav ul li ul li a:hover {
            background-color: #f1f1f1; /* Color de fondo al pasar el mouse */
        }

        /* Sections */
        section {
            max-width: 1100px;
            margin: 4rem auto;
            padding: 0 1rem;
        }
        .section-title {
            font-size: 2.4rem;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            position: relative;
        }
        .section-title::after {
            content: "";
            display: block;
            width: 80px;
            height: 4px;
            margin: 0.7rem auto 0;
            background-color: #0d6efd;
            border-radius: 2px;
        }

        /* Properties */
        #properties {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .property-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
            overflow: hidden;
            transition: transform 0.3s ease;
            cursor: pointer;
            position: relative; /* Para posicionar la etiqueta */
        }
        .property-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.25);
        }
        .property-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .property-content {
            padding: 1rem;
        }
        .property-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #0d6efd;
        }
        .property-desc {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 0.7rem;
        }
        .property-price {
            font-weight: 700;
            font-size: 1.1rem;
            color: #198754;
        }

        /* Footer */
        footer {
            background-color: #0d6efd;
            color: white;
            text-align: center;
            padding: 1.5rem 1rem;
            margin-top: 4rem;
            user-select: none;
        }
        footer p {
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #about {
                flex-direction: column;
            }
            .hero h1 {
                font-size: 2.5rem;
            }
            .hero p {
                font-size: 1.1rem;
            }
        }

        /* Etiqueta de Tipo de Operación */
        .property-label {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #f0ad4e; /* Amarillo/Naranja para destacar */
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 1; /* Asegurar que esté por encima de la imagen */
            text-transform: uppercase;
        }
        .property-label.venta {
            background-color: #d9534f; /* Rojo para Venta */
        }
        .property-label.alquiler {
            background-color: #5cb85c; /* Verde para Alquiler */
        }
        .property-label.ambas {
            background-color: #0275d8; /* Azul para Ambas */
        }
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
<h2 class="section-title mt-2">Alquileres
</div>
    <section id="properties" aria-label="Listado de Propiedades en Alquiler">
        <div id="properties">
            <?php if (empty($propiedades_alquiler)): ?>
                <p>No hay propiedades en alquiler disponibles.</p>
            <?php else: ?>
                <?php foreach ($propiedades_alquiler as $propiedad): ?>
                    <article class="property-card" tabindex="0" aria-label="<?php echo htmlspecialchars($propiedad['nombre']); ?>">
                        <img src="https://via.placeholder.com/350x200" alt="<?php echo htmlspecialchars($propiedad['nombre']); ?>" class="property-image" />
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