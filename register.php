<?php
$servername = "localhost";
$username = "root";
$password = "clientes170";
$dbname = "bd_clientes";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$usuario = $_POST['usuario'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
$correo = $_POST['correo'];
$nombre = $_POST['nombre'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'];
$nivel_usuario = $_POST['nivel_usuario'];
$ejecutivo = $_POST['ejecutivo'];

// Validar correo electrónico (sin @)
if (strpos($correo, '@') !== false) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El correo electrónico no debe contener @',
            confirmButtonText: 'Aceptar'
        }).then(function() {
            window.location = 'index.php';
        });
    </script>";
    exit();
}

// Verificar si ya existe un usuario con el mismo nombre y apellidos
$sql = "SELECT * FROM tb_user WHERE `Nombre`='$nombre' AND `Apellido Paterno`='$apellido_paterno' AND `Apellido Materno`='$apellido_materno'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $existingUser = $result->fetch_assoc();
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El usuario con nombre \"{$existingUser['Nombre']} {$existingUser['Apellido Paterno']} {$existingUser['Apellido Materno']}\" ya cuenta con un usuario y contraseña.',
            confirmButtonText: 'Aceptar'
        }).then(function() {
            window.location = 'index.php';
        });
    </script>";
    exit();
}

// Verificación detallada con posible error ortográfico utilizando SOUNDEX o LIKE
$sql = "SELECT * FROM tb_user WHERE SOUNDEX(`Nombre`) = SOUNDEX('$nombre') AND SOUNDEX(`Apellido Paterno`) = SOUNDEX('$apellido_paterno') AND SOUNDEX(`Apellido Materno`) = SOUNDEX('$apellido_materno')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'Existen usuarios con nombres y apellidos similares. Por favor, verifique la ortografía.',
            confirmButtonText: 'Aceptar'
        }).then(function() {
            window.location = 'index.php';
        });
    </script>";
    exit();
}

// Insertar usuario en la base de datos
$sql = "INSERT INTO tb_user (`Usuario`, `Contraseña`, `Correo Electrónico`, `Nombre`, `Apellido Paterno`, `Apellido Materno`, `Nivel de Usuario`, `Ejecutivo`)
        VALUES ('$usuario', '$contrasena', '$correo', '$nombre', '$apellido_paterno', '$apellido_materno', '$nivel_usuario', '$ejecutivo')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Registrado',
            text: 'Usuario registrado exitosamente',
            confirmButtonText: 'Aceptar'
        }).then(function() {
            window.location = 'index.php';
        });
    </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
