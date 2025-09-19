<?php
require_once "config.php";
$news_result = $conn->query("SELECT * FROM top_news ORDER BY created_at DESC LIMIT 10");
$slider_result = $conn->query("SELECT * FROM slider ORDER BY id DESC");
$adv_result = $conn->query("SELECT * FROM adv_slider ORDER BY id DESC");
$mysqli = new mysqli("localhost", "root", "Milan@1234", "football_action");
$points = $mysqli->query("SELECT * FROM uploads WHERE type='points' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
$fixture = $mysqli->query("SELECT * FROM uploads WHERE type='fixture' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();

// detect DB variable
$db = isset($conn) ? $conn : (isset($mysqli) ? $mysqli : null);
if (!$db) die("DB connection missing.");

// get search keyword if any
$keyword = trim($_GET['search'] ?? '');

// base query
$sql = "SELECT id, title, summary, image, created_at FROM news";

// if keyword provided, add WHERE condition
if ($keyword !== '') {
    $safe = $db->real_escape_string($keyword);
    $sql .= " WHERE title LIKE '%$safe%' OR summary LIKE '%$safe%' OR content LIKE '%$safe%'";
}

$sql .= " ORDER BY created_at DESC";
$result = $db->query($sql);

// Handle Newsletter Subscription
if (isset($_POST['subscribe'])) {
  $email = trim($_POST['email'] ?? '');

  if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $stmt = $conn->prepare("INSERT INTO newsletter (email) VALUES (?)");
      $stmt->bind_param("s", $email);
      if ($stmt->execute()) {
          $message = "✅ Thank you for subscribing!";
      } else {
          $message = "❌ Something went wrong!";
      }
  } else {
      $message = "⚠️ Please enter a valid email.";
  }
}
?>
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
<header>
  <div class="nav-container">
    <div  class="logo">
      <img src="img/509643969_122267074358024667_3310241970137801560_n (1).jpg" alt="Logo">
    </div>
    <nav class="navbar">
  <a href="index.php">Home</a>
  <a href="legend_players.php">About Us</a>
  <a href="football_community.php">Football & Community</a>
  <a href="event.php">Event</span></a>
  <a href="about.php">Media</a>
  <a href="gallery.php">Gallery Football</a>
  <a href="contact.php">Contact</a>
  <a href="about.php">More</a>
</nav>

    <div class="nav-right">
      <form method="get" class="search-bar">
        <input type="text" name="search" placeholder="Search news..." value="<?=htmlspecialchars($keyword)?>">
      </form>
      <a href="login.php" class="login-btn">Login</a> 
    </div>
  </div>
</header>

<!-- News Ticker -->
<div class="news-ticker">
  <span class="label">Top News:</span>
  <marquee behavior="scroll" direction="left" scrollamount="6">
    <?php if($news_result && $news_result->num_rows > 0): ?>
      <?php while($row = $news_result->fetch_assoc()): ?>
        <?php echo htmlspecialchars($row['headline']); ?> &nbsp; | &nbsp;
      <?php endwhile; ?>
    <?php else: ?>
      No news available yet.
    <?php endif; ?>
  </marquee>
</div>

<!-- Secondary Navbar -->
<div class="sub-navbar">
    <div  class="logo">
      <img src="img/509643969_122267074358024667_3310241970137801560_n (1).jpg" alt="Logo">
    </div>

    <div  class="logo-main">
      <img src="img/Purple Blue Simple Professional Marketing Professional LinkedIn Article Cover Image.png" alt="Logo">
    </div>


  <div class="sub-container">
  <a href="live.php">Live</a>
  <div class="dropdown">
    <a href="#">FIFA</a>
    <div class="dropdown-content">
    <a href="fifa_rankiing.php">FIFA Ranking</a>
    <a href="club_ranking.php">Club Ranking</a>
    <a href="fifa_calender.php">Calender</a>
  </div>
  </div>

 <div class="dropdown">
    <a href="#">Line-Up</a>
    <div class="dropdown-content">
      <a href="lineup.php">National XI</a>
      <a href="lineup.php">English-PL</a>
      <a href="lineup.php">La-Liga</a>
  </div>
  </div>
  <div class="dropdown">
    <a href="#">Point Table</a>
    <div class="dropdown-content">
      <a href="#">Bundesliga</a>
      <a href="#">English-PL</a>
      <a href="#">Serie A</a>
      <a href="#">Ligue 1</a>
      <a href="#">Brasileirão</a>
      <a href="#">La-Liga</a>
  </div>
  </div>

  <div class="dropdown">
    <a href="#">Schedules</a>
    <div class="dropdown-content">
      <a href="fixture.php">Fixture</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>
  <div class="dropdown">
    <a href="#">Players</a>
    <div class="dropdown-content">
      <a href="bio.php">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>

  <div class="dropdown">
    <a href="#">Important News</a>
    <div class="dropdown-content">
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>

  <div class="dropdown">
    <a href="#">Matches</a>
    <div class="dropdown-content">
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>

  <div class="dropdown">
    <a href="#">Injury Update</a>
    <div class="dropdown-content">
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>

  <div class="dropdown">
    <a href="#">Top News </a>
    <div class="dropdown-content">
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>

  <div class="dropdown">
    <a href="#">Club</a>
    <div class="dropdown-content">
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>

  <div class="dropdown">
    <a href="#">Transfers</a>
    <div class="dropdown-content">
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>

  </div>
  </div>

  <div class="slider-container">
    <div class="slider-wrapper" id="sliderWrapper">
        <?php while($row = $slider_result->fetch_assoc()): ?>
        <div class="slide">
            <img src="uploads/<?=htmlspecialchars($row['image'])?>" alt="Slider Image">
        </div>
        <?php endwhile; ?>
    </div>
    <div class="dots" id="dotsContainer"></div>
</div>

<div class="latest-news-box">
    <div class="latest-news-title">
        <span>Latest News</span>
    </div>
</div>

  <div class="container">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <?php if (!empty($row['image'])): ?>
            <img src="uploads/<?=htmlspecialchars($row['image'])?>" alt="<?=htmlspecialchars($row['title'])?>">
          <?php endif; ?>
          <div class="card-body">
            <h3><?=htmlspecialchars($row['title'])?></h3>
            <p><?=htmlspecialchars($row['summary'])?></p>
            <a href="news.php?id=<?=$row['id']?>">Read More</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="no-results">No news found.</div>
    <?php endif; ?>
  </div>
  
  <div class="adv-title">
        <span>La-Liga Fixture</span>
    </div>

    <div class="top-images">
  <?php 
    // Fetch all fixture images
    $fixture_result = $conn->query("SELECT * FROM uploads WHERE type='fixture' ORDER BY id DESC");
    while($row = $fixture_result->fetch_assoc()): 
  ?>
    <img src="uploads/<?= htmlspecialchars($row['image']); ?>" alt="Fixture">
  <?php endwhile; ?>
</div>

<br>
  <div class="adv-title">
        <span>Primer League Point Table</span>
      
    </div>
    <br>
    <div class="top-images">
  <?php 

    // Fetch all points images
    $points_result = $conn->query("SELECT * FROM uploads WHERE type='points' ORDER BY id DESC");
    while($row = $points_result->fetch_assoc()): 
  ?>
    <img src="uploads/<?= htmlspecialchars($row['image']); ?>" alt="Football Points">
  <?php endwhile; ?>
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
