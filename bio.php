<?php
include 'config.php';
$player_bios = $conn->query("SELECT * FROM player_bios ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Bios</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <?php while ($row = $player_bios->fetch_assoc()): ?>
    <div class="bio-card">
        <div class="card-header">
            <h2><?= htmlspecialchars($row['full_name']) ?></h2>
        </div>
        <div class="card-body">
            <div class="bio-image-container">
                <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="bio-image" alt="<?= htmlspecialchars($row['full_name']) ?>">
                <?php endif; ?>
            </div>
            
            <div class="bio-details">
                <div class="form-group">
                    <label>Age:</label>
                    <p><?= htmlspecialchars($row['age']) ?></p>
                </div>
                <div class="form-group">
                    <label>Country:</label>
                    <p><?= htmlspecialchars($row['country']) ?></p>
                </div>
                <div class="form-group">
                    <label>Current Club:</label>
                    <p><?= htmlspecialchars($row['current_club']) ?></p>
                </div>
                <div class="form-group">
                    <label>Past Clubs:</label>
                    <p><?= nl2br(htmlspecialchars($row['past_clubs'])) ?></p>
                </div>
              
            </div>
        </div>
    </div>
    <?php endwhile; ?>
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