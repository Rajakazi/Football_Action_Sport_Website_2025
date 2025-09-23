<?php
include "config.php";

$news_result = $conn->query("SELECT * FROM top_news ORDER BY created_at DESC LIMIT 10");
$keyword = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT * FROM news WHERE headline LIKE '%$keyword%' OR type LIKE '%$keyword%' ORDER BY created_at DESC";
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<title>Football News</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<br><br>

<!-- News Ticker -->
<div class="news-ticker">
  <span class="label">Top News:</span>
  <marquee behavior="scroll" direction="left" scrollamount="6">
    <?php if($news_result && $news_result->num_rows > 0): ?>
      <?php while($row = $news_result->fetch_assoc()): ?>
        <?=htmlspecialchars($row['headline']);?> &nbsp; | &nbsp;
      <?php endwhile; ?>
    <?php else: ?>
      No news available yet.
    <?php endif; ?>
  </marquee>
</div>

<br>
<!-- ==================== MOBILE ONLY SLIDER ==================== -->
<div class="mobile-slider">
    <div class="mobile-slider-wrapper" id="mobileSliderWrapper">
        <div class="mobile-slide">
            <img src="img/472470a8418b939503272772605c9cde.jpg" alt="Slide 2">
            <div class="mobile-overlay"></div>
        </div>

        <div class="mobile-slide">
            <img src="img/img/12d38c4501fa1645f56635d7d7f5d7e4.jpg" alt="Slide 3">
            <div class="mobile-overlay"></div>
        </div>

        <!-- Add more slides as needed -->
    </div>
</div>

<style>
/* ===== Mobile Slider Only ===== */
.mobile-slider {
    display: none; /* hidden by default */
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    margin: 20px auto;
    max-width: 100%;
    z-index: 1;
}

.mobile-slider-wrapper {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.mobile-slide {
    min-width: 100%;
    position: relative;
    height: 200px;
}

.mobile-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 8px;
}

.mobile-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.47);
    border-radius: 8px;
    pointer-events: none;
}

/* Show only on mobile */
@media screen and (max-width: 768px) {
    .mobile-slider { display: block; }
}
</style>
<div class="mobile-line-row">
  <div class="line-top-text">Sports News [Football Action] </div>
  <div class="mobile-line"></div>
</div>

<div class="container">
<?php if ($res && $res->num_rows > 0): ?>
  <?php while ($row = $res->fetch_assoc()): ?>
    <?php
      // Decode images JSON
      $images = !empty($row['images']) ? json_decode($row['images'], true) : [];
      $firstImage = $images[0] ?? $row['image'] ?? ''; // fallback to single image
    ?>
    <div class="card">
      <?php if (!empty($firstImage)): ?>
        <img src="uploads/<?=htmlspecialchars($firstImage)?>" alt="<?=htmlspecialchars($row['headline'])?>">
      <?php endif; ?>
      <div class="card-body">
        <h3><?=htmlspecialchars($row['headline'])?></h3>
        <a href="news.php?id=<?=$row['id']?>">Read More</a>
      </div>
    </div>

    <div class="mobile-line-row">
      <div class="line-top-text">Today Top News</div>
      <div class="mobile-line"></div>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <div class="no-results">No news found.</div>
<?php endif; ?>
</div>

<!-- Mobile Nav / Sidebar omitted for brevity, keep your existing code -->
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
