<?php
require_once "config.php";
// get search keyword if any
$keyword = trim($_GET['search'] ?? '');
$players = $conn->query("SELECT * FROM legend_players ORDER BY created_at DESC");
$news_result = $conn->query("SELECT * FROM top_news ORDER BY created_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>About Us - Legend Players</title>
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
    <h1>About Us</h1>
</div>

<!-- Legend Players Section -->
<div class="players-container">
<?php while($row = $players->fetch_assoc()): ?>
    <div class="player-card">
        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <div class="player-content">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p><?= nl2br(substr(htmlspecialchars($row['history']),0,100)) ?>...</p>
            <button class="read-more-btn" onclick="openModal('<?= $row['id'] ?>')">Read More</button>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal-<?= $row['id'] ?>" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('<?= $row['id'] ?>')">&times;</span>
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <h2><?= htmlspecialchars($row['name']) ?></h2>
            <p><?= nl2br(htmlspecialchars($row['history'])) ?></p>
        </div>
    </div>
<?php endwhile; ?>
</div>

<script>
// Open Modal
function openModal(id) {
    document.getElementById('modal-' + id).style.display = 'block';
}
// Close Modal
function closeModal(id) {
    document.getElementById('modal-' + id).style.display = 'none';
}
// Close modal on outside click
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
}
</script>
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
