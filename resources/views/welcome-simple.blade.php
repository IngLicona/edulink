<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sistema de Gestión Escolar') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            margin: 1rem;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: #667eea;
            border-radius: 50%;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2.2rem;
        }
        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .feature {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #555;
        }
        @media (max-width: 480px) {
            .container {
                padding: 2rem;
            }
            h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            📚
        </div>
        <h1>Sistema de Gestión Escolar</h1>
        <p>Plataforma integral para la administración educativa, gestión de estudiantes, docentes y procesos académicos.</p>
        
        <div class="features">
            <div class="feature">
                👥 Estudiantes
            </div>
            <div class="feature">
                🎓 Docentes
            </div>
            <div class="feature">
                📊 Matriculaciones
            </div>
            <div class="feature">
                💰 Pagos
            </div>
        </div>

        @guest
            <a href="{{ route('login') }}" class="btn">Iniciar Sesión</a>
        @else
            <a href="{{ route('home') }}" class="btn">Ir al Dashboard</a>
        @endguest
    </div>
</body>
</html>
