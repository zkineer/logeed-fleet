<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>LogeedFleet | APP</title>
    <link rel="stylesheet" href="/fleet/css/Login.css">
    <link rel="icon" href="/fleet/img/ICON.png">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-left">
            <div class="truck-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500">
                    <!-- Fondo transparente -->
                    <rect width="100%" height="100%" fill="none"/>

                    <!-- Sombra inferior del camión -->
                    <ellipse cx="250" cy="480" rx="180" ry="12" fill="rgba(255,255,255,0.1)"/>

                    <!-- Bumper -->
                    <rect x="80" y="380" width="340" height="50" rx="8" fill="none" stroke="white" stroke-width="4"/>
                    <circle cx="150" cy="405" r="7" fill="white"/>
                    <circle cx="350" cy="405" r="7" fill="white"/>

                    <!-- Llantas -->
                    <rect x="50" y="420" width="60" height="60" rx="10" fill="none" stroke="white" stroke-width="3"/>
                    <rect x="390" y="420" width="60" height="60" rx="10" fill="none" stroke="white" stroke-width="3"/>

                    <!-- Faros con brillo -->
                    <rect x="95" y="300" width="80" height="60" rx="12" fill="none" stroke="white" stroke-width="3"/>
                    <circle cx="120" cy="330" r="10" fill="white"/>
                    <circle cx="150" cy="330" r="10" fill="white"/>
                    <circle cx="120" cy="330" r="6" fill="rgba(255,255,255,0.4)"/>
                    <circle cx="150" cy="330" r="6" fill="rgba(255,255,255,0.4)"/>

                    <rect x="325" y="300" width="80" height="60" rx="12" fill="none" stroke="white" stroke-width="3"/>
                    <circle cx="350" cy="330" r="10" fill="white"/>
                    <circle cx="380" cy="330" r="10" fill="white"/>
                    <circle cx="350" cy="330" r="6" fill="rgba(255,255,255,0.4)"/>
                    <circle cx="380" cy="330" r="6" fill="rgba(255,255,255,0.4)"/>

                    <!-- Parrilla -->
                    <rect x="175" y="200" width="150" height="180" rx="12" fill="none" stroke="white" stroke-width="4"/>
                    <ellipse cx="250" cy="215" rx="25" ry="10" fill="none" stroke="white" stroke-width="2"/>

                    <!-- Barras parrilla con grosor variable -->
                    <g stroke="white">
                        <line x1="185" y1="200" x2="185" y2="380" stroke-width="1.5"/>
                        <line x1="195" y1="200" x2="195" y2="380" stroke-width="2"/>
                        <line x1="205" y1="200" x2="205" y2="380" stroke-width="1.5"/>
                        <line x1="215" y1="200" x2="215" y2="380" stroke-width="2"/>
                        <line x1="225" y1="200" x2="225" y2="380" stroke-width="1.5"/>
                        <line x1="235" y1="200" x2="235" y2="380" stroke-width="2"/>
                        <line x1="245" y1="200" x2="245" y2="380" stroke-width="1.5"/>
                        <line x1="255" y1="200" x2="255" y2="380" stroke-width="2"/>
                        <line x1="265" y1="200" x2="265" y2="380" stroke-width="1.5"/>
                        <line x1="275" y1="200" x2="275" y2="380" stroke-width="2"/>
                        <line x1="285" y1="200" x2="285" y2="380" stroke-width="1.5"/>
                        <line x1="295" y1="200" x2="295" y2="380" stroke-width="2"/>
                        <line x1="305" y1="200" x2="305" y2="380" stroke-width="1.5"/>
                        <line x1="315" y1="200" x2="315" y2="380" stroke-width="2"/>
                    </g>

                    <!-- Cabina -->
                    <rect x="140" y="150" width="220" height="50" rx="8" fill="none" stroke="white" stroke-width="3"/>

                    <!-- Luces del techo con glow -->
                    <g>
                        <circle cx="170" cy="140" r="5" fill="white"/>
                        <circle cx="200" cy="140" r="5" fill="white"/>
                        <circle cx="230" cy="140" r="5" fill="white"/>
                        <circle cx="270" cy="140" r="5" fill="white"/>
                        <circle cx="300" cy="140" r="5" fill="white"/>
                        <circle cx="330" cy="140" r="5" fill="white"/>
                    </g>

                    <!-- Bocinas -->
                    <ellipse cx="190" cy="125" rx="8" ry="4" fill="none" stroke="white" stroke-width="2"/>
                    <ellipse cx="310" cy="125" rx="8" ry="4" fill="none" stroke="white" stroke-width="2"/>

                    <!-- Espejos -->
                    <rect x="85" y="180" width="12" height="80" rx="4" fill="none" stroke="white" stroke-width="2"/>
                    <rect x="403" y="180" width="12" height="80" rx="4" fill="none" stroke="white" stroke-width="2"/>

                    <!-- Escapes con detalle -->
                    <path d="M130 80 v60 q0 10 10 10 h10" fill="none" stroke="white" stroke-width="3"/>
                    <path d="M360 80 v60 q0 10 -10 10 h-10" fill="none" stroke="white" stroke-width="3"/>
                    <rect x="120" y="80" width="15" height="40" rx="5" fill="none" stroke="white" stroke-width="2"/>
                    <rect x="365" y="80" width="15" height="40" rx="5" fill="none" stroke="white" stroke-width="2"/>
                </svg>

            </div>

            <!-- Título -->
            <h2>INICIAR SESIÓN</h2>
            <p class="subtitle">Gestión inteligente de flotas</p>

            <!-- Formulario -->
            <form method="POST" action="/fleet/login">
                <input type="text" name="username" placeholder="ID" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Entrar</button>
            </form>
        </div>

        <!-- Lado derecho -->
        <div class="login-right">
            <div class="logo-container">
                <img src="/fleet/img/LOGEED.png" alt="LOGEED Fleet" class="logo">
                <p class="site-url">WWW.LOGEED.COM</p>
            </div>
        </div>

    </div>
</div>
</body>
</html>
