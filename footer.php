<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Football Action - News</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<style>
    /* Basic Footer Styling */
.site-footer {
    background-color: #1a1a1a;
    color: #f0f0f0;
    padding: 60px 20px 20px;
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 40px;
}

.footer-logo {
    flex: 1 1 100%;
    text-align: center;
    margin-bottom: 40px;
}

.footer-logo img {
    max-width: 180px;
    height: auto;
}

.footer-links {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 40px;
    flex: 1 1 auto;
    text-align: left;
}

.link-column h3 {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 15px;
    font-weight: bold;
}

.link-column ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.link-column li {
    margin-bottom: 10px;
}

.link-column a {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s ease;
}

.link-column a:hover {
    color: #00aaff;
}

.footer-social {
    display: flex;
    gap: 20px;
    flex: 1 1 100%;
    justify-content: center;
    margin-top: 40px;
}

.footer-social a {
    color: #f0f0f0;
    font-size: 1.5rem;
    transition: transform 0.3s ease, color 0.3s ease;
}

/* Animation on hover */
.footer-social a:hover {
    transform: translateY(-5px) scale(1.1);
    color: #00aaff;
}

/* Bottom Copyright Section */
.footer-bottom {
    border-top: 1px solid #333;
    padding-top: 20px;
    text-align: center;
}

.footer-bottom p {
    margin: 0;
    font-size: 0.9rem;
    color: #999;
}

/* Responsive Design */
@media (min-width: 768px) {
    .footer-container {
        justify-content: space-between;
        align-items: flex-start;
    }

    .footer-logo {
        flex: 0 0 auto;
        margin-bottom: 0;
    }

    .footer-social {
        flex: 0 0 auto;
        margin-top: 0;
    }
}
</style>
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-logo">
            <a href="/">
                <img src="img/509643969_122267074358024667_3310241970137801560_n (1).jpg" alt="Your Company Logo">
            </a>
        </div>
        <div class="footer-links">
            <div class="link-column">
                <h3>Products</h3>
                <ul>
                    <li><a href="#">Product One</a></li>
                    <li><a href="#">Product Two</a></li>
                    <li><a href="#">Product Three</a></li>
                </ul>
            </div>
            <div class="link-column">
                <h3>Company</h3>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="link-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-social">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Your Company. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>