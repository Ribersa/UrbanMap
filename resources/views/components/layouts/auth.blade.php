<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication' }} — Urban Legend Map</title>
    <meta name="description" content="Masuk atau daftar ke Urban Legend Map - Peta Interaktif Legenda Urban Indonesia">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617;
            min-height: 100vh;
        }
        .font-orbitron { font-family: 'Orbitron', monospace; }
        
        /* Animated cyberpunk grid background */
        .cyber-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image: 
                linear-gradient(rgba(6, 182, 212, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(6, 182, 212, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }
        @keyframes gridMove {
            0% { transform: perspective(500px) rotateX(0deg); }
            100% { transform: perspective(500px) rotateX(2deg); }
        }

        /* Glowing neon card */
        .neon-card {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(6, 182, 212, 0.3);
            box-shadow: 
                0 0 15px rgba(6, 182, 212, 0.1),
                0 0 30px rgba(6, 182, 212, 0.05),
                inset 0 1px 0 rgba(6, 182, 212, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
        }
        .neon-card:hover {
            border-color: rgba(6, 182, 212, 0.5);
            box-shadow: 
                0 0 20px rgba(6, 182, 212, 0.15),
                0 0 40px rgba(6, 182, 212, 0.08),
                inset 0 1px 0 rgba(6, 182, 212, 0.15);
        }

        /* Neon input styling */
        .neon-input {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(100, 116, 139, 0.3);
            color: #e2e8f0;
            transition: all 0.3s ease;
        }
        .neon-input:focus {
            outline: none;
            border-color: rgba(6, 182, 212, 0.7);
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15), 0 0 15px rgba(6, 182, 212, 0.1);
        }
        .neon-input::placeholder {
            color: rgba(148, 163, 184, 0.5);
        }

        /* Neon button */
        .neon-btn {
            background: linear-gradient(135deg, #06b6d4, #8b5cf6);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .neon-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        .neon-btn:hover::before {
            transform: translateX(100%);
        }
        .neon-btn:hover {
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4), 0 0 40px rgba(139, 92, 246, 0.2);
            transform: translateY(-1px);
        }

        /* Floating particles */
        .particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .particle-1 { width: 3px; height: 3px; background: rgba(6, 182, 212, 0.4); top: 20%; left: 10%; animation: float 6s ease-in-out infinite; }
        .particle-2 { width: 2px; height: 2px; background: rgba(139, 92, 246, 0.4); top: 40%; right: 15%; animation: float 8s ease-in-out infinite 1s; }
        .particle-3 { width: 4px; height: 4px; background: rgba(6, 182, 212, 0.3); bottom: 30%; left: 20%; animation: float 7s ease-in-out infinite 2s; }
        .particle-4 { width: 2px; height: 2px; background: rgba(139, 92, 246, 0.3); top: 60%; right: 25%; animation: float 9s ease-in-out infinite 0.5s; }
        .particle-5 { width: 3px; height: 3px; background: rgba(6, 182, 212, 0.2); bottom: 20%; right: 10%; animation: float 6.5s ease-in-out infinite 1.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.4; }
            25% { transform: translateY(-20px) translateX(10px); opacity: 0.8; }
            50% { transform: translateY(-10px) translateX(-5px); opacity: 0.6; }
            75% { transform: translateY(-25px) translateX(8px); opacity: 0.9; }
        }

        /* Scanline effect */
        .scanline {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(6, 182, 212, 0.01) 2px,
                rgba(6, 182, 212, 0.01) 4px
            );
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased">
    <div class="cyber-grid"></div>
    <div class="scanline"></div>
    <div class="particle particle-1"></div>
    <div class="particle particle-2"></div>
    <div class="particle particle-3"></div>
    <div class="particle particle-4"></div>
    <div class="particle particle-5"></div>

    <main class="relative z-10 flex min-h-screen items-center justify-center p-4">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
