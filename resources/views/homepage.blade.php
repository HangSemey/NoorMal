<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoorMal - Islamic Inheritance Made Simple</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10B981;
            --primary-dark: #059669;
            --secondary-color: #064E3B;
            --bg-color: #FAFAFA;
            --text-color: #1F2937;
            --nav-text: #374151;
            --button-bg: #D1FAE5;
            --button-text: #065F46;
            --rounded-xl: 32px;
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-card: 0 25px 50px -12px rgba(16, 185, 129, 0.25);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp 0.8s ease-out forwards;
            opacity: 0;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }

        /* Header */
        header {
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: rgba(250, 250, 250, 0.8);
            backdrop-filter: blur(10px);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 4rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            font-style: italic;
            color: #111;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
            padding: 0;
            margin: 0;
            align-items: center;
        }

        nav a {
            text-decoration: none;
            color: var(--nav-text);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 99px;
            transition: all 0.3s ease;
        }

        nav a.active {
            background-color: #E5E7EB;
            color: #111;
            font-weight: 600;
        }

        nav a:hover:not(.active) {
            color: var(--primary-color);
            background-color: rgba(0,0,0,0.03);
        }

        /* Hero Section */
        .hero {
            padding: 2rem 4rem 4rem 4rem;
            max-width: 1400px;
            margin: 0 auto;
            perspective: 1000px;
        }

        .hero-card {
            display: flex;
            background: linear-gradient(135deg, #34D399 0%, #10B981 100%);
            border-radius: var(--rounded-xl);
            overflow: hidden;
            width: 100%;
            min-height: 580px;
            box-shadow: var(--shadow-card);
            position: relative;
            transform-style: preserve-3d;
        }

        /* Pattern overlay for texture */
        .hero-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 120%, rgba(255,255,255,0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 10%, rgba(255,255,255,0.1) 0%, transparent 30%);
            z-index: 1;
            pointer-events: none;
        }

        .hero-content {
            flex: 1;
            padding: 5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            font-size: 3.75rem;
            line-height: 1.1;
            margin: 0 0 1.5rem 0;
            font-weight: 700;
            color: #022c22;
            letter-spacing: -1.5px;
        }

        .hero-content p {
            font-size: 1.25rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            max-width: 480px;
            color: #064E3B; /* Deep green text */
            font-weight: 500;
        }

        .cta-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background-color: #ECFDF5;
            color: #047857;
            text-decoration: none;
            padding: 1rem 2.5rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 1.1rem;
            width: fit-content;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.15);
            background-color: #fff;
        }

        .hero-image {
            flex: 1.1;
            position: relative;
            z-index: 1;
            /* Clip path to make it blend interestingly */
            clip-path: polygon(10% 0, 100% 0, 100% 100%, 0% 100%);
            margin-left: -40px; /* Slight overlap */
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            transition: transform 0.7s ease;
        }
        
        /* Subtle zoom on hover of the whole card */
        .hero-card:hover .hero-image img {
            transform: scale(1.03);
        }

        /* Features Section */
        .features {
            padding: 4rem 4rem;
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
        }

        .feature-item {
            padding: 2rem;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #f3f3f3;
            transition: transform 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateY(-5px);
            border-color: #D1FAE5;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: #ECFDF5;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: #059669;
        }

        .feature-item h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.75rem 0;
            color: #111;
        }

        .feature-item p {
            color: #6B7280;
            line-height: 1.6;
            margin: 0;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 3rem;
            color: #6B7280;
            font-size: 0.9rem;
            border-top: 1px solid #eee;
            margin-top: 2rem;
        }

        /* Background Animation */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            background: #FAFAFA;
        }

        .blob {
            position: absolute;
            width: 600px;
            height: 600px;
            filter: blur(100px);
            opacity: 0.4;
            border-radius: 50%;
            animation: move 20s infinite alternate;
        }

        .blob-1 {
            top: -100px;
            left: -100px;
            background: #d1fae5;
            animation-duration: 25s;
        }

        .blob-2 {
            bottom: -150px;
            right: -100px;
            background: #a7f3d0;
            animation-duration: 30s;
            animation-direction: alternate-reverse;
        }

        .blob-3 {
            top: 40%;
            left: 50%;
            width: 400px;
            height: 400px;
            background: #fdf2f8; /* Subtle warm tone for contrast */
            transform: translate(-50%, -50%);
            animation-name: pulse;
            animation-duration: 15s;
        }

        @keyframes move {
            from {
                transform: translate(0, 0) rotate(0deg);
            }
            to {
                transform: translate(50px, 100px) rotate(20deg);
            }
        }

        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.1); }
            100% { transform: translate(-50%, -50%) scale(1); }
        }

        /* Infinite Marquee */
        .marquee-container {
            background-color: var(--secondary-color);
            color: white;
            padding: 1rem 0;
            overflow: hidden;
            position: relative;
            margin-top: -2rem; /* Overlap slightly or adjust as needed */
            margin-bottom: 4rem;
            transform: rotate(-1deg) scale(1.05); /* Slight tilt for dynamism */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .marquee-content {
            display: flex;
            gap: 4rem;
            width: max-content;
            animation: scroll 20s linear infinite;
        }

        .marquee-item {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .marquee-item::after {
            content: "•";
            opacity: 0.5;
        }

        @keyframes scroll {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        /* Mobile Responsiveness */
        @media (max-width: 1024px) {
            .header-container, .hero, .features {
                padding: 2rem;
            }
            .hero-content h1 {
                font-size: 3rem;
            }
            .hero-image {
                clip-path: none;
                margin-left: 0;
                height: 400px; /* Fixed height for video container */
            }
            .background-video {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .marquee-container {
                transform: rotate(0);
                margin-top: 2rem;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                padding: 1.5rem;
            }
            nav ul {
                margin-top: 1.5rem;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.75rem;
            }
            .hero-card {
                flex-direction: column;
                min-height: auto;
            }
            .hero-content {
                padding: 3rem 2rem;
            }
            .hero-image {
                height: 300px;
                clip-path: none;
                margin: 0;
            }
            .hero-content h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Moving Background -->
    <div class="background">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <header class="animate-fade-up">
        <div class="header-container">
            <a href="{{ url('/') }}" class="logo">NoorMal.</a>
            <nav>
                <ul>
                    <li><a href="{{ url('/') }}" class="active">Home</a></li>
                    <li><a href="#">About Faraid</a></li>
                    <li><a href="#">Heirs Information</a></li>
                    <li><a href="#">Assets</a></li>
                    <li><a href="#">Results</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero animate-fade-up delay-100">
        <div class="hero-card">
            <div class="hero-content">
                <h1 class="animate-fade-up delay-200">Islamic Inheritance<br>Made Simple</h1>
                <p class="animate-fade-up delay-300">Fast, accurate, and completely compliant with Islamic Shariah law. Calculate inheritance shares with peace of mind using our trusted Faraid calculator.</p>
                <div class="animate-fade-up delay-300">
                    <a href="#" class="cta-button">Start Calculate</a>
                </div>
            </div>
            <div class="hero-image">
                <!-- Video Background with Image Fallback -->
                <video autoplay muted loop playsinline poster="{{ asset('images/hero.png') }}" style="width: 100%; height: 100%; object-fit: cover;">
                    <source src="{{ asset('videos/signing.mp4') }}" type="video/mp4">
                    <!-- Fallback to image if video fails or not supported -->
                    <img src="{{ asset('images/hero.png') }}" alt="Signing Legal Document" style="width: 100%; height: 100%; object-fit: cover;">
                </video>
            </div>
        </div>
    </section>

    <!-- Infinite Scrolling Marquee -->
    <div class="marquee-container animate-fade-up delay-300">
        <div class="marquee-content">
            <!-- Duplicated content for seamless loop -->
            <div class="marquee-item">Quranic Principles</div>
            <div class="marquee-item">Authentic Faraid</div>
            <div class="marquee-item">Shariah Compliant</div>
            <div class="marquee-item">Instant Calculation</div>
            <div class="marquee-item">Detailed Reports</div>
            <div class="marquee-item">Quranic Principles</div>
            <div class="marquee-item">Authentic Faraid</div>
            <div class="marquee-item">Shariah Compliant</div>
            <div class="marquee-item">Instant Calculation</div>
            <div class="marquee-item">Detailed Reports</div>
            <div class="marquee-item">Quranic Principles</div>
            <div class="marquee-item">Authentic Faraid</div>
            <div class="marquee-item">Shariah Compliant</div>
            <div class="marquee-item">Instant Calculation</div>
            <div class="marquee-item">Detailed Reports</div>
        </div>
    </div>

    <section class="features animate-fade-up delay-300">
        <div class="feature-item">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h3>Shariah Compliant</h3>
            <p>Our algorithms are rigorously tested and verified to ensure 100% adherence to authentic Faraid rules.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <h3>Instant Calculation</h3>
            <p>Get results in seconds. Simply input the heirs and assets, and let our system handle the complex mathematics.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <h3>Detailed Reports</h3>
            <p>Download comprehensive PDF reports breaking down the share of each heir with clear explanations.</p>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} NoorMal. All rights reserved.</p>
    </footer>

</body>
</html>

