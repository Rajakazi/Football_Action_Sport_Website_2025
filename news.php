<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>News Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
/* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body {
    background: #f4f6f9;
    color: #333;
    line-height: 1.6;
}

a { text-decoration: none; }

/* Header */
header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 50px;
    background: linear-gradient(135deg, #0b74de, #074a99);
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 1000;
}

header img {
    height: 50px;
    width: 50px;
    border-radius: 50%;
    object-fit: cover;
}

header nav a {
    color: #fff;
    font-weight: 500;
    margin-left: 20px;
    transition: color 0.3s;
}

header nav a:hover {
    color: #ffdd57;
}

/* Container */
.container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

/* News Detail Card */
.news-detail {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.news-detail:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.2);
}

.news-detail h2 {
    font-size: 2rem;
    color: #0b74de;
    margin-bottom: 20px;
}

.news-detail img {
    width: 100%;
    max-width: 700px;
    height: auto;
    border-radius: 12px;
    margin-bottom: 20px;
    object-fit: cover;
}

.news-detail p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
    text-align: justify;
}

.news-detail small {
    display: block;
    color: #777;
    margin-bottom: 25px;
}

/* Share Buttons */
.share h3 {
    margin-bottom: 10px;
    color: #0b74de;
}

.share a {
    display: inline-block;
    margin: 5px 10px;
    padding: 10px 16px;
    border-radius: 6px;
    color: #fff;
    font-weight: bold;
    transition: transform 0.2s, box-shadow 0.2s;
}

.share a:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

.share .fb { background: #1877f2; }
.share .tw { background: #1da1f2; }
.share .wa { background: #25d366; }

/* Responsive */
@media (max-width: 768px) {
    header {
        flex-direction: column;
        gap: 12px;
    }
    .news-detail h2 { font-size: 1.6rem; }
    .share a { padding: 8px 12px; margin: 5px 5px; font-size: 14px; }
}

@media (max-width: 480px) {
    header { padding: 12px 20px; }
    .news-detail { padding: 20px; }
    .news-detail h2 { font-size: 1.4rem; }
    .share a { padding: 6px 10px; font-size: 13px; }
}

    </style>
</head>
<body>
<header>
    <img src="assets/images/logo.png" alt="Football Action">
    <nav>
        <a href="index.php">Home</a>
    </nav>
</header>

<div class="container">
    <?php
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM news WHERE id=$id");
    if($row = $result->fetch_assoc()) {
        $url = "http://localhost/football_action/news.php?id=".$row['id'];
        echo "<div class='news-detail'>
                <h2>".$row['title']."</h2>
                <img src='uploads/".$row['image']."' alt=''>
                <p>".$row['content']."</p>
                <small>Published on ".$row['created_at']."</small>

                <div class='share'>
                    <h3>Share this news:</h3>
                    <a class='fb' href='https://www.facebook.com/sharer/sharer.php?u=$url' target='_blank'>Facebook</a>
                    <a class='tw' href='https://twitter.com/intent/tweet?url=$url&text=".$row['title']."' target='_blank'>Twitter</a>
                    <a class='wa' href='https://api.whatsapp.com/send?text=".$row['title']." $url' target='_blank'>WhatsApp</a>
                </div>
              </div>";
    }
    ?>
</div>

<footer class="footer">
    <div class="footer-container">

      <!-- Logo -->
<div class="footer-logo">
    <img src="img/509643969_122267074358024667_3310241970137801560_n (1).jpg" alt="Logo">
    <p>Your Website Tagline Here</p>
</div>
        <!-- Navigation -->
        <div class="footer-nav">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="services.php">Services</a></li>
            </ul>
        </div>

        <!-- Social Media -->
        <div class="footer-social">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="footer-newsletter">
            <h3>Subscribe</h3>
            <form method="post">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" name="subscribe">Subscribe</button>
            </form>
            <?php if (!empty($message)): ?>
                <p class="msg"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date("Y") ?> Football Action. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
