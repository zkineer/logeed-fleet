<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogeedFleet</title>
    <link rel="icon" href="/fleet/img/ICON.png">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">


    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        /* Barra */
        .nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 8px 15px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            gap: 15px;
        }

        /* Logo */
        .logo {
            height: 40px;
            width: auto;
            margin-left: 10px;
        }

        /* Menú personalizado */
        .dp {
            position: relative;
            flex: 1;
            max-width: 300px;
        }

        .dp-btn {
            position: relative;
            width: 100%;
            background: #f0f0f0;
            border-radius: 20px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            color: #000;
            border: none;
            outline: none;
        }

        .dp-btn i {
            position: absolute;
            right: 12px; /* flecha siempre a la derecha */
            font-size: 18px;
            color: #555;
        }


        .dp-content {
            display: none;
            position: absolute;
            top: 110%;
            left: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            width: 100%;
            overflow: hidden;
            z-index: 100;
        }

        .dp-content a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .dp-content a:hover {
            background: #f5f5f5;
        }

        .nav-right {
            display: flex;
            align-items: center;
        }

        .username {
            font-weight: bold;
            color: #333;
            display: flex;
            align-items: center;
            margin-right: 10px; /* pequeño espacio solo antes del primer icono */
        }

        .btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #444;
            transition: color 0.2s ease, transform 0.2s ease;
            padding: 5px; /* quitamos relleno interno */
            margin-left: 4px; /* espacio mínimo entre iconos */
        }

        .btn-icon:hover {
            color: #007BFF;
            transform: scale(1.1);
        }


        /* Responsivo */
        @media (max-width: 768px) {
            .nav-bar {
                max-width: 100%;
                margin: 10px;
                padding: 6px 10px;
                gap: 8px;
            }

            .logo {
                height: 32px;
                margin-left: 5px;
            }

            .dp {
                max-width: 200px;
            }

            .dp-btn {
                font-size: 13px;
                padding: 6px 10px;
            }

            .username {
                font-size: 13px;
                margin-right: 5px;
            }

            .btn-icon {
                font-size: 18px;
                margin-left: 2px;
            }
        }

        @media (max-width: 480px) {
            .nav-bar {
                flex-wrap: wrap;
                justify-content: center;
            }

            .logo {
                height: 28px;
                margin: 0 auto;
            }

            .dp {
                order: 3;
                width: 100%;
                max-width: none;
                margin-top: 5px;
            }

            .dp-btn {
                width: 100%;
                justify-content: center;
            }

            .nav-right {
                order: 2;
                gap: 4px;
            }

            .username {
                display: none; /* ocultar texto en pantallas muy pequeñas */
            }
        }

    </style>
</head>
<body>

<header class="nav-bar">
    <!-- Logo -->
    <img src="/fleet/img/LOGO.png" alt="Logo" class="logo">

    <!-- Menú -->
    <div class="dp">
        <button class="dp-btn">
            <span>Menú</span>
            <i class='bx bx-chevron-down'></i>
        </button>
        <div class="dp-content">
            <a href="#"><i class='bx bx-home'></i> Inicio</a>
            <a href="#"><i class='bx bx-receipt'></i> Cotizaciones</a>
        </div>
    </div>

    <!-- Usuario -->
    <div class="nav-right">
        <span class="username" id="username">Usuario</span>
        <button class="btn-icon" title="Configuración">
            <i class='bx bx-cog'></i>
        </button>
        <button class="btn-icon" title="Cerrar sesión" id="btnLogout">
            <i class='bx bx-log-out'></i>
        </button>
    </div>
</header>

<script>
    const userLogged = "Angel";
    document.getElementById("username").textContent = `${userLogged}`;

    const dpBtn = document.querySelector('.dp-btn');
    const dpContent = document.querySelector('.dp-content');

    dpBtn.addEventListener('click', () => {
        dpContent.style.display =
            dpContent.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dp')) {
            dpContent.style.display = 'none';
        }
    });

    document.getElementById('btnLogout').addEventListener('click', () => {
        window.location.href = '/fleet/logout';
    });

</script>

</body>
</html>
