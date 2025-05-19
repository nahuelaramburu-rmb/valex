<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];
?>


<?php
require 'conexion.php';

// Obtener la cantidad de propiedades por tipo de operación
$stmt_venta = $pdo->prepare("SELECT COUNT(*) FROM propiedades WHERE tipo_operacion = 'Venta' OR tipo_operacion = 'Venta/Alquiler'");
$stmt_venta->execute();
$cantidad_venta = $stmt_venta->fetchColumn();

$stmt_alquiler = $pdo->prepare("SELECT COUNT(*) FROM propiedades WHERE tipo_operacion = 'Alquiler' OR tipo_operacion = 'Venta/Alquiler'");
$stmt_alquiler->execute();
$cantidad_alquiler = $stmt_alquiler->fetchColumn();

$stmt_ambas = $pdo->prepare("SELECT COUNT(*) FROM propiedades WHERE tipo_operacion = 'Venta/Alquiler'");
$stmt_ambas->execute();
$cantidad_ambas = $stmt_ambas->fetchColumn();

// Obtener todas las propiedades para la exportación
$stmt_all = $pdo->query("SELECT nombre, codigo_unico, precio, ubicacion, tipo_operacion FROM propiedades");
$all_propiedades = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métricas de Propiedades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table {
            margin-top: 20px;
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
        <li><a href="admin_propiedades.php">Inicio</a></li>
        <li> <a href="metricas.php">Metricas</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
      </ul>
     </nav>

     </header>
<body>
    <div class="container mt-5">
    <h1 style="margin-top: 130px;"></h1>
        <canvas id="propiedadesChart" width="400" height="200"></canvas>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tipo de Operación</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Venta</td>
                    <td><?php echo $cantidad_venta; ?></td>
                </tr>
                <tr>
                    <td>Alquiler</td>
                    <td><?php echo $cantidad_alquiler; ?></td>
                </tr>
                <tr>
                    <td>Venta/Alquiler</td>
                    <td><?php echo $cantidad_ambas; ?></td>
                </tr>
            </tbody>
        </table>

        <button onclick="exportarCSV()" class="btn btn-success mt-3">
            <i class='bx bx-file me-2'></i> Exportar a CSV
        </button>
    </div>
    </br>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      const ctx = document.getElementById('propiedadesChart').getContext('2d');
      const propiedadesChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Venta', 'Alquiler', 'Venta/Alquiler'],
          datasets: [{
            label: 'Cantidad de Propiedades',
            data: [<?php echo $cantidad_venta; ?>, <?php echo $cantidad_alquiler; ?>, <?php echo $cantidad_ambas; ?>],
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)', // Rojo para Venta
              'rgba(54, 162, 235, 0.7)', // Azul para Alquiler
              'rgba(255, 206, 86, 0.7)'  // Amarillo para Venta/Alquiler
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              precision: 0
            }
          }
        }
      });

      function exportarCSV() {
          const propiedades = <?php echo json_encode($all_propiedades); ?>;

          if (propiedades.length === 0) {
              alert("No hay propiedades para exportar.");
              return;
          }

          let csvTexto = "Nombre,Código,Precio,Ubicación,Tipo Operación\n";

          propiedades.forEach(propiedad => {
              csvTexto += `"${propiedad.nombre}","${propiedad.codigo_unico}","${propiedad.precio}","${propiedad.ubicacion}","${propiedad.tipo_operacion}"\n`;
          });

          const nombreArchivo = 'metricas_propiedades.csv';
          const tipoArchivo = 'text/csv';

          const elementoDescarga = document.createElement('a');
          elementoDescarga.setAttribute('href', 'data:' + tipoArchivo + ';charset=utf-8,' + encodeURIComponent(csvTexto));
          elementoDescarga.setAttribute('download', nombreArchivo);

          document.body.appendChild(elementoDescarga);
          elementoDescarga.click();
          document.body.removeChild(elementoDescarga);
      }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>