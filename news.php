<?php
include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>News Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>

.news-detail h2 { 
     font-size: 15px;
    color:rgb(40, 255, 20); 
    text-align: center; 
}

.news-detail img { 
    width: 800px;
    height: 500px; 
    border-radius: 12px; 
    display: block; 
    margin: 15px auto; 
}

.news-detail p { 
    font-size: 1rem; 
    color: white; 
    margin-bottom: 15px; 
    align-items: stretch;  
    text-align: justify;
  line-height: 1.7;
  margin: 0;
}

.news-detail p { 
    font-size: 1rem; 
    color: white; 
    line-height: 1.7;
    margin-bottom: 15px; 
    text-align: justify;
}

/* Desktop / Laptop only: constrain width and center */
@media (min-width: 992px) {
  .news-detail p {
      max-width: 800px;   /* text line width */
      margin: 0 auto 15px auto; /* center horizontally */
  }
}

.news-detail small { 
    display: block; 
    color: white; 
    margin-top: 25px; 
    text-align: center; 
}

.share { text-align: center; margin-top: 20px; }
.share h3 { margin-bottom: 10px; color: #0b74de; }
.share a { display: inline-block; margin: 5px 10px; padding: 8px 12px; border-radius: 6px; color: #fff; text-decoration: none; font-weight: bold; }
.share .fb { background: #1877f2; }
.share .tw { background: #1da1f2; }
.share .wa { background: #25d366; }

@media (max-width: 768px) {
    .news-detail h2 { font-size: 1.6rem; }
    .share a { padding: 6px 10px; font-size: 14px; margin: 5px 5px; }
}

@media (max-width: 480px) {
    .news-detail { padding: 15px; }
    .news-detail h2 { font-size: 1.4rem; }
    .share a { padding: 5px 8px; justify-content: center; font-size: 13px; }
    .news-detail img { 
    width: 100%;
    height: auto; 
    border-radius: 12px; 
    display: block; 
    margin: 15px auto; 
}
}
    </style>
    <?php include 'header.php'; ?>
</head>
<body>
<div class="mobile-line-row">
  <div class="line-top-text">Today News [ Football Action ]</div>
  <div class="mobile-line"></div>
</div
<div class="container">
<?php
$id = intval($_GET['id']);

// Increment views
$conn->query("UPDATE news SET views = views + 1 WHERE id=$id");

// Fetch news
$result = $conn->query("SELECT * FROM news WHERE id=$id");

if($row = $result->fetch_assoc()) {

    // Decode images JSON
    $images = !empty($row['images']) ? json_decode($row['images'], true) : [];

    echo "<div class='news-detail'>";

    // Headline first
    echo "<h2>".htmlspecialchars($row['headline'])."</h2>";

    // Views count
    echo "<p><strong>".((int)($row['views'] ?? 0) + 1)." views</strong></p>";

    // First image after headline
    if(!empty($images[0])){
        echo "<img src='uploads/".htmlspecialchars($images[0])."' alt='Featured Image'>";
    }

    // Summary (optional)
    if(!empty($row['summary'])){
        echo "<p><strong>".htmlspecialchars($row['summary'])."</strong></p>";
    }

    // Content paragraphs with interleaved images
    $content = nl2br(htmlspecialchars($row['content']));
    $paragraphs = explode("<br />", $content);

    $imgIndex = 1; // Start from second image
    foreach($paragraphs as $p){
        if(trim($p) !== ''){
            echo "<p>$p</p>";
        }
        if(isset($images[$imgIndex])){
            echo "<img src='uploads/".htmlspecialchars($images[$imgIndex])."' alt='News Image'>";
            $imgIndex++;
        }
    }

    // Publication date & share buttons
    echo "<small>Published on ".htmlspecialchars($row['created_at'])."</small>";
    $url = "http://localhost/football_action/news.php?id=".$row['id'];
    echo "<div class='share'>
            <h3>Share this news:</h3>
            <a class='fb' href='https://www.facebook.com/sharer/sharer.php?u=$url' target='_blank'>Facebook</a>
            <a class='tw' href='https://twitter.com/intent/tweet?url=$url&text=".htmlspecialchars($row['headline'])."' target='_blank'>Twitter</a>
            <a class='wa' href='https://api.whatsapp.com/send?text=".htmlspecialchars($row['headline'])." $url' target='_blank'>WhatsApp</a>
          </div>";

    echo "</div>";
} else {
    echo "<p style='text-align:center;'>News not found.</p>";
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
<a href="bio.php" class="nav-item">
  <i class="fas fa-user"></i> <!-- Profile icon -->
  <span>Player</span>
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
