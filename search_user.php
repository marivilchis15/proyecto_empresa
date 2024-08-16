<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "clientes170";
$dbname = "bd_clientes";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $delete_user = $_GET['delete_user'];
    $sql_delete = "DELETE FROM tb_user WHERE Usuario = ?";
    $stmt_delete = $conn->prepare($sql_delete);

    if ($stmt_delete === false) {
        die("Error preparing delete statement: " . $conn->error);
    }

    $stmt_delete->bind_param("s", $delete_user);

    if ($stmt_delete->execute()) {
        $alert_message = "success: Usuario eliminado exitosamente.";
    } else {
        $alert_message = "error: Error al eliminar el usuario: " . $stmt_delete->error;
    }

    $stmt_delete->close();
}

// Handle search query
$searchQuery = '';
$users = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchQuery = $_POST['search'];

    $sql_users = "SELECT * FROM tb_user WHERE Usuario LIKE ? OR Nombre LIKE ? OR ApellidoPaterno LIKE ? OR ApellidoMaterno LIKE ?";
    $stmt_users = $conn->prepare($sql_users);

    if ($stmt_users === false) {
        die("Error preparing search statement: " . $conn->error);
    }

    $searchWildcard = "%$searchQuery%";
    $stmt_users->bind_param("ssss", $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();

    while ($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }

    $stmt_users->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar-brand {
            color: white;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: white !important;
        }
        .container {
            width: 80%;
            max-width: 900px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">
        <img src="logo.png" alt="Tricxa Logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="inicio.html">Inicio</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDirectivo" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Directivo
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownDirectivo">
                    <a class="dropdown-item" href="#">REPORTE DE VISITAS </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownEjecutivos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Ejecutivos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownEjecutivos">
                    <a class="dropdown-item" href="#">CREDENCIAL-CARTAS </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReporte" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Reporte - Herramientas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownReporte">
                    <a class="dropdown-item" href="#"><i class="fas fa-list"></i> Panel de Control de Accesos</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-mobile-alt"></i> Accesos Movil</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-bell"></i> Avisos</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-shopping-cart"></i> Control de Cadenas</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-th"></i> Control de Frecuencias</a>
                    <a class="dropdown-item" href="index.php"><i class="fas fa-users"></i> Control de Promotores</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-store"></i> Control de Tiendas</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-map-marker-alt"></i> Excepciones Geo-Cerca</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-qrcode"></i> Promoción QR</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-pen"></i> Registro de Visitas</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-book"></i> Reporte de Visitas</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-chart-bar"></i> Reporte General</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-book"></i> Reporte Pagos</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSistema" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sistema
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownSistema">
                    <a class="dropdown-item" href="#">CONTROL DE CLIENTES</a>
                    <a class="dropdown-item" href="#">USUARIOS-SISTEMAS</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>Buscar Usuario</h1>
    <form method="POST" action="">
        <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Buscar por nombre o usuario" required>
        <button type="submit">Buscar</button>
    </form>

    <!-- Tabla de usuarios -->
    <?php if (!empty($users)): ?>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Correo Electrónico</th>
                    <th>Nivel de Usuario</th>
                    <th>Ejecutivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['Usuario']); ?></td>
                        <td><?php echo htmlspecialchars($user['Nombre']); ?></td>
                        <td><?php echo htmlspecialchars($user['ApellidoPaterno']); ?></td>
                        <td><?php echo htmlspecialchars($user['ApellidoMaterno']); ?></td>
                        <td><?php echo htmlspecialchars($user['CorreoElectronico']); ?></td>
                        <td><?php echo htmlspecialchars($user['NivelDeUsuario']); ?></td>
                        <td><?php echo htmlspecialchars($user['Ejecutivo']); ?></td>
                        <td>
                            <a href="edit_user.php?edit_user=<?php echo htmlspecialchars($user['Usuario']); ?>">Editar</a> |
                            <a href="?delete_user=<?php echo htmlspecialchars($user['Usuario']); ?>" onclick="return confirm('<?php echo htmlspecialchars($user['Usuario']); ?>')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron usuarios.</p>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($alert_message)): ?>
            <?php if (strpos($alert_message, 'error:') !== false): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo str_replace("error: ", "", $alert_message); ?>',
                });
            <?php elseif (strpos($alert_message, 'success:') !== false): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: '<?php echo str_replace("success: ", "", $alert_message); ?>',
                });
            <?php endif; ?>
        <?php endif; ?>
    });

    function confirm() {
        return Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás recuperar este usuario!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "?search_user=" + user;
            }
        });
        return false; // Prevent default link behavior
    }
</script>

</body>
</html>

