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

// Initialize $editUser and $alert_message
$editUser = null;
$alert_message = "";

// Handle user update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario'])) {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo_electronico = $_POST['correo_electronico'];
    $nivel_usuario = $_POST['nivel_usuario'];
    $ejecutivo = $_POST['ejecutivo'];

    $sql_update = "UPDATE tb_user SET Nombre = ?, ApellidoPaterno = ?, ApellidoMaterno = ?, CorreoElectronico = ?, NivelDeUsuario = ?, Ejecutivo = ? WHERE Usuario = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update === false) {
        die("Error preparing update statement: " . $conn->error);
    }

    $stmt_update->bind_param("sssssss", $nombre, $apellido_paterno, $apellido_materno, $correo_electronico, $nivel_usuario, $ejecutivo, $usuario);

    if ($stmt_update->execute()) {
        $alert_message = "Usuario actualizado exitosamente.";
    } else {
        $alert_message = "Error al actualizar el usuario: " . $stmt_update->error;
    }

    $stmt_update->close();
}

// Handle user retrieval for editing
if (isset($_GET['edit_user'])) {
    $usuario = $_GET['edit_user'];
    
    $sql_edit = "SELECT * FROM tb_user WHERE Usuario = ?";
    $stmt_edit = $conn->prepare($sql_edit);

    if ($stmt_edit === false) {
        die("Error preparing edit statement: " . $conn->error);
    }

    $stmt_edit->bind_param("s", $usuario);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();

    if ($result_edit->num_rows > 0) {
        $editUser = $result_edit->fetch_assoc();
    }

    $stmt_edit->close();
}

// Fetch executives
$ejecutivos = [];
$sql_ejecutivos = "SELECT nombre FROM ejecutivos"; // Ajusta el nombre de la tabla y la columna según tu estructura
$result_ejecutivos = $conn->query($sql_ejecutivos);

if ($result_ejecutivos === false) {
    die("Error executing query: " . $conn->error);
}

if ($result_ejecutivos->num_rows > 0) {
    while ($row = $result_ejecutivos->fetch_assoc()) {
        $ejecutivos[] = $row['nombre']; // Ajusta el nombre de la columna según tu estructura
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }
        .navbar-brand img {
            height: 40px;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .container h1 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: #007bff;
        }
        .container .btn-group {
            display: flex;
        }
        .container .btn-group .btn {
            margin-left: 10px;
        }
        label {
            margin: 10px 0 5px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        select {
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
        .btn-rojo:hover {
            background-color: red;
            color: white;
            align-items: center;
        }
        .btn-verde:hover {
            background-color: green;
            color: white;
            align-items: center;
        }
    </style>
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
                        <a class="dropdown-item" href=""><i class="fas fa-th"></i> Control de Frecuencias</a>
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
    <h1>Editar Usuario</h1>
    <?php if ($editUser): ?>
        <form method="POST" action="">
            <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($editUser['Usuario']); ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($editUser['Nombre']); ?>" required>
            <label for="apellido_paterno">Apellido Paterno:</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?php echo htmlspecialchars($editUser['ApellidoPaterno']); ?>" required>
            <label for="apellido_materno">Apellido Materno:</label>
            <input type="text" id="apellido_materno" name="apellido_materno" value="<?php echo htmlspecialchars($editUser['ApellidoMaterno']); ?>" required>
            <label for="correo_electronico">Correo Electrónico:</label>
            <input type="text" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($editUser['CorreoElectronico']); ?>" required>
            <label for="nivel_usuario">Nivel de Usuario:</label>
            <select id="nivel_usuario" name="nivel_usuario" required>
                <option value="promotor" <?php echo $editUser['NivelDeUsuario'] === 'promotor' ? 'selected' : ''; ?>>Promotor</option>
                <option value="promotor_pc21" <?php echo $editUser['NivelDeUsuario'] === 'promotor_pc21' ? 'selected' : ''; ?>>Promotor PC21</option>
                <!-- Agregar más opciones según sea necesario -->
            </select>
            <label for="ejecutivo">Ejecutivo:</label>
            <select id="ejecutivo" name="ejecutivo" required>
                <option value="">Seleccione el Ejecutivo</option>
                <?php foreach ($ejecutivos as $ejecutivo): ?>
                    <option value="<?php echo htmlspecialchars($ejecutivo); ?>" <?php echo $editUser['Ejecutivo'] === $ejecutivo ? 'selected' : ''; ?>><?php echo htmlspecialchars($ejecutivo); ?></option>
                <?php endforeach; ?>
            </select>
        
            <button type="submit" class="btn_verde">Actualizar Usuario</button>
            <button type="button" class="btn-rojo" onclick="window.location.href='search_user.php'">CERRAR</button>
        </form>
    <?php else: ?>
        <p>No se encontró el usuario para editar.</p>
    <?php endif; ?>
</div>

<?php if (!empty($alert_message)): ?>
<script>
    Swal.fire({
        icon: '<?php echo strpos($alert_message, "Error") !== false ? "error" : "success"; ?>',
        title: '<?php echo $alert_message; ?>',
        showConfirmButton: true,
        confirmButtonText: 'Aceptar'
    });
</script>
<?php endif; ?>

</body>
</html>
