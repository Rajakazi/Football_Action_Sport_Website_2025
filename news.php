<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>News Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
/* Reset & Base */


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

/* News Detail Card */
.news-detail {
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}


.news-detail h2 {
    font-size: 2rem;
    color:rgb(12, 255, 4);
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
    color: white;
    margin-bottom: 20px;
    text-align: justify;
}

.news-detail small {
    display: block;
    color: yellow;
    margin-bottom: 25px;
}

/* Share Buttons */
.share h3 {
    margin-bottom: 10px;
    color:rgb(4, 255, 25);
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
    header { padding: 2px 10px; }
    .news-detail { padding: 10px; }
    .news-detail h2 { font-size: 1.4rem; }
    .share a { padding: 6px 10px; font-size: 13px; }
}

    </style>
    <?php include 'header.php'; ?>
</head>
<body>

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


        <!-- Mobile all part here now so here full code -->
        <nav class="mobile-nav">
<a href="football_news_front.php" class="nav-item">
  <i class="fas fa-newspaper"></i>
  <span>News</span>
</a>
<a href="live.php" class="nav-item">
        <i class="fas fa-broadcast-tower"></i>
        <span>Live</span>
    </a>
        <div class="mobile-nav-item mobile-center">
            <a href="index.php" class="home-btn">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <a href="event.php" class="nav-item">
    <i class="fas fa-calendar-alt"></i>
    <span>Event</span>
</a>
<a href="draw.php" class="nav-item">
  <i class="fas fa-user"></i> <!-- Profile icon -->
  <span>Contact</span>
</a>


<!-- Mobile Top Navbar -->
<div class="mobile-top-nav">
    <!-- Left: Logo -->
    <div class="mobile-logo">
        <img src="img/509643969_122267074358024667_3310241970137801560_n (1).jpg" alt="Logo">
    </div>
    <div  class="logo-main">
      <img src="img/Purple Blue Simple Professional Marketing Professional LinkedIn Article Cover Image.png" alt="Logo">
    </div>


    <!-- Right: Hamburger -->
    <div class="mobile-right">
        <div class="hamburger" onclick="toggleMobileMenu()">&#9776;</div>
    </div>
</div>

<!-- Mobile Sidebar -->
<div class="mobile-sidebar" id="mobileSidebar">
    <div class="sidebar-header">
        <h3>Football Action</h3>
        <div class="close-btn" onclick="toggleMobileMenu()">×</div>
    </div>
    <a href="#">FIFA</a>
    <a href="#">Line-Up</a>
    <a href="#">Point Table</a>
    <a href="#">Schedules</a>
    <a href="#">Players</a>
    <a href="#">Important News</a>
    <a href="#">Matches</a>
    <a href="#">Injury Update</a>
    <a href="#">Top News</a>
    <a href="#">Club</a>
    <a href="football_news_front.php">Transfers</a>
</div>

<script src="js/scrip.js"></script>

</body>
</html>
