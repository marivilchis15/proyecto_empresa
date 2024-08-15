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

// Function to check if two strings are similar
function areStringsSimilar($str1, $str2) {
    return levenshtein($str1, $str2) <= 1;
}

// Handle form submission for creating a new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_user'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $correo = $_POST['correo'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $nivel_usuario = $_POST['nivel_usuario'];
    $ejecutivo = $_POST['ejecutivo'];

    // Check if the names or last names are similar
    $sql_check_similar_names = "SELECT * FROM tb_user WHERE ApellidoPaterno = ? OR ApellidoMaterno = ? OR Nombre = ?";
    $stmt_check_names = $conn->prepare($sql_check_similar_names);

    if ($stmt_check_names === false) {
        die("Error preparing check names statement: " . $conn->error);
    }

    $stmt_check_names->bind_param("sss", $apellido_paterno, $apellido_materno, $nombre);
    $stmt_check_names->execute();
    $result_check_names = $stmt_check_names->get_result();

    $similarNames = false;
    $similarUsers = [];

    while ($user = $result_check_names->fetch_assoc()) {
        if (
            areStringsSimilar($apellido_paterno, $user['ApellidoPaterno']) || 
            areStringsSimilar($apellido_materno, $user['ApellidoMaterno']) || 
            areStringsSimilar($nombre, $user['Nombre'])
        ) {
            $similarNames = true;
            $similarUsers[] = "Usuario: {$user['Usuario']}, Nombre: {$user['Nombre']}, Apellido Paterno: {$user['ApellidoPaterno']}, Apellido Materno: {$user['ApellidoMaterno']}";
        }
    }

    if ($similarNames) {
        $similarUsersList = implode("\n", $similarUsers);
        $alert_message = "confirm: Se encontraron usuarios con nombres o apellidos similares:\n" . $similarUsersList . "\n¿Deseas continuar?";
    } else {
        // Insert new user into the database
        $sql_insert_user = "INSERT INTO tb_user (Usuario, Contrasena, CorreoElectronico, Nombre, ApellidoPaterno, ApellidoMaterno, NivelDeUsuario, Ejecutivo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);

        if ($stmt_insert_user === false) {
            die("Error preparing insert user statement: " . $conn->error);
        }

        $stmt_insert_user->bind_param("ssssssss", $usuario, $contrasena, $correo, $nombre, $apellido_paterno, $apellido_materno, $nivel_usuario, $ejecutivo);

        if ($stmt_insert_user->execute()) {
            $alert_message = "success: Usuario creado exitosamente.";
        } else {
            $alert_message = "error: Error al crear el usuario: " . $stmt_insert_user->error;
        }

        $stmt_insert_user->close();
    }

    $stmt_check_names->close();
}

// Fetch ejecutivos for the dropdown
$sql_ejecutivos = "SELECT nombre FROM ejecutivos";
$result_ejecutivos = $conn->query($sql_ejecutivos);

$ejecutivos = [];
if ($result_ejecutivos) {
    while ($row = $result_ejecutivos->fetch_assoc()) {
        $ejecutivos[] = $row['nombre'];
    }
} else {
    die("Error fetching ejecutivos: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tricxa by Narro - Crear Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Custom styles for the page */
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
        .content {
            padding: 20px;
            background-color: #f0f4fb;
            text-align: center;
            min-height: 300px;
        }
        .content h1 {
            color: #007bff;
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

    <div class="content">
        <h1>Crear Usuario</h1>
        <div class="container">
            <form method="post" action="">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <label for="correo">Correo Electrónico:</label>
                <input type="text" id="correo" name="correo" required>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="apellido_paterno">Apellido Paterno:</label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" required>

                <label for="apellido_materno">Apellido Materno:</label>
                <input type="text" id="apellido_materno" name="apellido_materno" required>

                <label for="nivel_usuario">Nivel de Usuario:</label>
                <select id="nivel_usuario" name="nivel_usuario" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>

                <label for="ejecutivo">Ejecutivo:</label>
                <select id="ejecutivo" name="ejecutivo" required>
                    <?php foreach ($ejecutivos as $ejecutivo) { ?>
                        <option value="<?php echo $ejecutivo; ?>"><?php echo $ejecutivo; ?></option>
                    <?php } ?>
                </select>

                <button type="submit" name="create_user">Crear Usuario</button>
                <button type="button" class="btn-rojo" onclick="window.location.href='index.php'">CERRAR</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if (!empty($alert_message)): ?>
        Swal.fire({
            icon: '<?php echo strpos($alert_message, "error") !== false ? "error" : (strpos($alert_message, "success") !== false ? "success" : "warning"); ?>',
            title: '<?php echo ucfirst(explode(": ", $alert_message)[0]); ?>',
            text: '<?php echo explode(": ", $alert_message)[1]; ?>',
        });
        <?php endif; ?>
    </script>
</body>
</html>
