<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];
?>

<?php
require 'conexion.php';

// Obtener todas las propiedades
$stmt = $pdo->query("SELECT * FROM propiedades");
$propiedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        h1 {
            color: #343a40;
            margin-bottom: 20px;
        }
        .btn {
            margin-right: 5px;
        }

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
    }
    nav ul li a {
      font-weight: 600;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    </style>
</head>
<body>
<div class="container">
    <header>
    <nav>
      <div class="logo"><a href="index.html">Corporacion Valex</a></div>
      <ul>
        <li><a href="#about">Inicio</a></li>
        <li> <a href="metricas.php">Metricas</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
      </ul>
     </nav>

     </header>
  
        <h1 style="margin-top: 150px;">Administración. Bienvenido, <?php echo $_SESSION['username']; ?>!</h1>
        <p></p>
       
        <p><a href="alta_propiedad.php" class="btn btn-success">
        <i class='bx bx-plus me-2'></i> Nueva
        </a></p>

        <?php if (empty($propiedades)): ?>
            <p class="alert alert-info">No hay propiedades registradas.</p>
        <?php else: ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Precio</th>
                        <th>Ubicación</th>
                        <th>Tipo Operación</th> <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($propiedades as $propiedad): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($propiedad['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($propiedad['codigo_unico']); ?></td>
                            <td><?php echo htmlspecialchars($propiedad['precio']); ?></td>
                            <td><?php echo htmlspecialchars($propiedad['ubicacion']); ?></td>
                            <td><?php echo htmlspecialchars($propiedad['tipo_operacion']); ?></td> <td>
                                <a href="editar_propiedad.php?id=<?php echo $propiedad['id']; ?>" class="btn btn-primary btn-sm"><i class='bx bx-edit me-2'></i></a>
                                <a href="procesar_propiedad.php?accion=eliminar&id=<?php echo $propiedad['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?')"><i class='bx bx-trash me-2'></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="logout.php">
            <button type="submit" class="btn btn-warning">
        <i class='bx bx-log-out me-2'>
        Cerrar Sesión</i>
        </button></a>
                    </br>
                    </br>
                    </br>

        <p> Desarrollado por <a class="texto-blanco" href="www.rmbcorp.com">RMBCORP </a></p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>