<?php
require_once "config.php";
$news_result = $conn->query("SELECT * FROM top_news ORDER BY created_at DESC LIMIT 10");
// get search keyword if any
$keyword = trim($_GET['search'] ?? '');
// Check if live_updates table exists
$conn->query("CREATE TABLE IF NOT EXISTS live_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    headline VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle Add Live Update
if(isset($_POST['add_live'])){
    $headline = $_POST['headline'];
    $stmt = $conn->prepare("INSERT INTO live_updates (headline) VALUES (?)");
    $stmt->bind_param("s",$headline);
    $stmt->execute();
}

// Handle Delete Live Update
if(isset($_GET['delete_live'])){
    $id = (int)$_GET['delete_live'];
    $conn->query("DELETE FROM live_updates WHERE id=$id");
    header("Location: live_admin.php");
    exit;
}

// Fetch live updates
$live_result = $conn->query("SELECT * FROM live_updates ORDER BY created_at DESC");

// Fetch events
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");

// Fetch news
$news = $conn->query("SELECT * FROM news ORDER BY news_date ASC LIMIT 5");
$news = $conn->query("SELECT * FROM news ORDER BY id DESC LIMIT 5");

?>

<link rel="stylesheet" href="assets/css/style.css">
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
  <a href="about.php">Contact</a>
  <a href="about.php">More</a>
</nav>

    <div class="nav-right">
      <form method="get" class="search-bar">
        <input type="text" name="search" placeholder="Search news..." value="<?=htmlspecialchars($keyword)?>">
      </form>
      <a href="login.php" class="login-btn">Login</a> 
       <a href="login.php" class="login-btn">Sign Up</a>
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
    <a href="#">FIFA</a>
    <a href="#">Line-Up</a>
    <a href="#">Point Table</a>
    <a href="#">Schedules</a>
    <a href="#">Players</a>
    <a href="#">Important News</a>
    <a href="#">Matches</a>
    <a href="#">Injury Update</a>
    <a href="#">Top News </a>
    <a href="#">Club</a>
    <a href="#">Transfers</a>
  </div>
  </div>
  <!-- About Us Heading -->
<div class="about-header">
    <h1>Event</h1>
</div>
<!-- Live Ticker -->
<div class="live-ticker" style="background:#0b74de;color:#fff;padding:10px;margin:20px 0;border-radius:6px;">
    <marquee behavior="scroll" direction="left" scrollamount="5">
        <?php
        $live = $conn->query("SELECT headline FROM live_updates ORDER BY created_at DESC");
        $headlines = [];
        while($row = $live->fetch_assoc()){
            $headlines[] = htmlspecialchars($row['headline']);
        }
        echo implode(" &nbsp; | &nbsp; ", $headlines);
        ?>
    </marquee>
</div>

<!-- Main Container -->
<div class="main-container" style="display:flex;gap:20px;">
    <div class="left-content" style="flex:3;">
        <?php while($row = $events->fetch_assoc()): ?>
        <div class="event-card" style="background:#fff;padding:15px;margin-bottom:20px;border-radius:10px;box-shadow:0 6px 20px rgba(0,0,0,0.1);">
            <?php if($row['image']): ?>
            <img src="uploads/<?=htmlspecialchars($row['image'])?>" style="width:100%;border-radius:10px;margin-bottom:10px;" alt="">
            <?php endif; ?>
            <h3><?=htmlspecialchars($row['title'])?></h3>
            <p><?=nl2br(htmlspecialchars($row['description']))?></p>
            <p>Date: <?=htmlspecialchars($row['event_date'])?> Time: <?=htmlspecialchars($row['event_time'])?></p>
            <?php if($row['live_link']): ?>
            <a href="<?=htmlspecialchars($row['live_link'])?>" target="_blank" style="color:#fff;background:#28a745;padding:5px 10px;border-radius:5px;text-decoration:none;">Watch Live</a>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="right-sidebar" style="flex:1;background:#f9f9f9;padding:15px;border-radius:10px;">
        <h3 style="margin-bottom:15px;color:#0b74de;">Upcoming News</h3>
        <?php while($row = $news->fetch_assoc()): ?>
        <div class="news-item" style="background:#fff;padding:10px;margin-bottom:10px;border-radius:8px;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
            <h4><?=htmlspecialchars($row['title'])?></h4>
            <p><?=htmlspecialchars($row['news_date'])?> <?=htmlspecialchars($row['news_time'])?></p>
        </div>
        <?php endwhile; ?>
    </div>
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
