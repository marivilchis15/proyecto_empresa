

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tricxa by Narro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px; /* Ajusta el espacio superior según lo necesario */
        }
        .btn-azul {
            background-color: #007bff;
            color: white;
            border: none;
            margin-bottom: 10px; /* Espacio entre botones */
        }
        .btn-azul:hover {
            background-color: #0056b3;
            color: white;
        }
        .btn-verde {
            background-color: #28a745;
            color: white;
            border: none;
            margin-bottom: 10px; /* Espacio entre botones */
        }
        .btn-verde:hover {
            background-color: #218838;
            color: white;
            align-items: center;
        }
        .form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
   
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
    <h1>PROMOTORES</h1>
    <div class="form-container">
        <button type="button" class="btn-verde" onclick="window.location.href='create_user.php'">CREAR</button>
        <button type="button" class="btn-azul" onclick="window.location.href='search_user.php'">BUSCAR</button>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

