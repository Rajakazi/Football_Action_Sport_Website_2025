<?php
include "config.php";

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

<div class="container">
    <?php if ($res && $res->num_rows > 0): ?>
      <?php while ($row = $res->fetch_assoc()): ?>
        <div class="card">
          <?php if (!empty($row['image'])): ?>
            <img src="uploads/<?=htmlspecialchars($row['image'])?>" alt="<?=htmlspecialchars($row['headline'])?>">
          <?php endif; ?>
          <div class="card-body">
            <h3><?=htmlspecialchars($row['headline'])?></h3>
            <div class="follow">Follow More: <?=htmlspecialchars($row['type'])?></div>
            <a href="news.php?id=<?=$row['id']?>">Read More</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="no-results">No news found.</div>
    <?php endif; ?>
</div>

   <!-- Mobile all part here now so here full code -->
   <nav class="mobile-nav">
   <a href="football_news_front.php" class="nav-item">
  <i class="fas fa-trophy"></i>
  <span>Sports</span>
</a>
<!-- Football Icon -->
<a href="football_community.php" class="nav-item">
  <i class="fas fa-futbol"></i>
  <span>Football</span>
</a>
        <div class="mobile-nav-item mobile-center">
            <a href="index.php" class="home-btn">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <!-- Sports Icon -->
<a href="football_news_front.php" class="nav-item">
<i class="fa-solid fa-medal"></i>
  <span>Winn</span>
</a>

<a href="draw.php" class="nav-item">
<i class="fa-solid fa-truck-medical"></i> <!-- Profile icon -->
  <span>Injury</span>
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
