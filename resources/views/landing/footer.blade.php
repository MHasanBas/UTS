<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-about">
            <h4>Tentang HasanStore</h4>
            <p>HasanStore adalah brand fashion Indonesia yang menyediakan pakaian kasual berkualitas tinggi dengan desain modern, cocok untuk gaya hidup urban anak muda.</p>
        </div>

        <div class="footer-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#home">Beranda</a></li>
                <li><a href="#koleksi">Koleksi Baru</a></li>
                <li><a href="#blog">Artikel</a></li>
                <li><a href="#about">Tentang Kami</a></li>
                <li><a href="#footer">Kontak</a></li>
            </ul>
        </div>

        <div class="footer-social " id="footer">
            <h4>Ikuti Kami</h4>
            <div class="social-icons">
                <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 HasanStore. All Rights Reserved.</p>
        <p>Designed by HasanStore IT & Digital Marketing Team</p>
    </div>
</footer>

<!-- Style for Footer -->
<style>
    .footer {
        background-color: #2a3b47;
        color: white;
        padding: 3rem 0;
        text-align: left;
    }

    .footer-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 0 10%;
    }

    .footer-about, .footer-links, .footer-social {
        flex: 1;
        margin-bottom: 1rem;
        max-width: 300px;
    }

    .footer-about h4, .footer-links h4, .footer-social h4 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #ff7f50;
    }

    .footer-about p {
        color: #ddd;
        line-height: 1.6;
        font-size: 1rem;
    }

    .footer-links ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links ul li {
        margin-bottom: 0.5rem;
    }

    .footer-links ul li a {
        color: #fff;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-links ul li a:hover {
        color: #ff7f50;
    }

    .footer-social .social-icons {
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .social-icons a {
        color: white;
        font-size: 1.5rem;
        transition: transform 0.3s, color 0.3s;
    }

    .social-icons a:hover {
        color: #ff7f50;
        transform: scale(1.2); /* Zoom in saat hover */
    }

    .footer-bottom {
        text-align: center;
        margin-top: 2rem;
        border-top: 1px solid #fff;
        padding-top: 1rem;
    }

    .footer-bottom p {
        margin: 0.2rem 0;
        color: #ddd;
    }

    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            text-align: center;
        }

        .footer-about, .footer-links, .footer-social {
            margin-bottom: 1.5rem;
        }

        .footer-social {
            margin-top: 1.5rem;
        }
    }
</style>


    <script>
        const koleksiItems = document.querySelectorAll('.koleksi-item');
        const descriptionText = document.getElementById('description');
    
        koleksiItems.forEach((item) => {
            item.addEventListener('mouseover', function () {
                // Dapatkan deskripsi dari atribut data-desc
                const desc = item.getAttribute('data-desc');
                descriptionText.textContent = desc;
            });
    
            item.addEventListener('mouseout', function () {
                descriptionText.textContent = "Hover over the images to see their descriptions.";
            });
        });
    </script>