    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <h1><img src="images/logo.png" alt="HasanStore Logo"></h1>
        </div>
        <ul class="nav-links">
            <li><a href="#header">Home</a></li>
            <li><a href="#koleksi">Koleksi Baru</a></li>
            <li><a href="#blog">Artikel</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="{{ route('login') }}" class="login-btn">Login</a></li>
        </ul>
        
        
    </nav>

        <!-- Header Section -->
        <header class="header-section" id="header">
            <div class="header-overlay"></div> <!-- Overlay untuk teks -->
            <h2>Hai, kami HasanStore</h2>
            <p>Hasan Store adalah brand fashion Indonesia yang menyediakan pakaian kasual berkualitas tinggi dan trendy dengan style fashion yang modern. Fokus pada anak muda dengan gaya hidup urban.</p>
            <a href="{{ route('register') }}" class="get-started-btn">Get Started</a> <!-- Button to register -->
        </header>