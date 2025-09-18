<?php
include "config.php";
$res = $conn->query("SELECT * FROM calendar ORDER BY created_at DESC");
// get search keyword if any
$keyword = trim($_GET['search'] ?? '');
$news_result = $conn->query("SELECT * FROM top_news ORDER BY created_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Football Calendar</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
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
    <a href="#">FIFA Ranking</a>
    <a href="#">Club Ranking</a>
    <a href="fifa_calender.php">Calender</a>
  </div>
  </div>

 <div class="dropdown">
    <a href="#">Line-Up</a>
    <div class="dropdown-content">
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
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
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
  </div>
  </div>
  <div class="dropdown">
    <a href="#">Players</a>
    <div class="dropdown-content">
      <a href="#">National XI</a>
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

  <div class="adv-title">
        <span>Advertisement Here</span>
    </div>
<div class="calendar-container">
<?php while($row=$res->fetch_assoc()): ?>
<article class="event-card" onclick="openModal('uploads/<?= htmlspecialchars($row['image']) ?>')">
    <figure>
        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
        <figcaption class="event-card-body">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= htmlspecialchars($row['description']) ?></p>
        </figcaption>
    </figure>
</article>
<?php endwhile; ?>
</div>

<!-- Modal -->
<div id="imageModal" class="modal-main">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <div class="modal-main-content">
        <img id="modalImg" src="" alt="">
    </div>
</div>

<script>
// Open modal
function openModal(src){
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImg');
    modal.style.display = 'flex';
    modalImg.src = src;
    document.body.style.overflow = 'hidden'; // prevent scrolling
}

// Close modal
function closeModal(){
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto'; // restore scrolling
}
</script>

<footer class="site-footer">
        <div class="footer-container">
            <div class="footer-section footer-logo">
                <img src="img/509643969_122267074358024667_3310241970137801560_n (1).jpg" alt="Company Logo" class="footer-logo-img">
                <p>A beautiful website for a beautiful cause. Connect with us on social media!</p>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-section footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Portfolio</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <div class="footer-section footer-contact">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Main Street, City, Country</p>
                <p><i class="fas fa-phone"></i> +977-9876543210</p>
                <p><i class="fas fa-envelope"></i> info@yourcompany.com</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Your Company. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
