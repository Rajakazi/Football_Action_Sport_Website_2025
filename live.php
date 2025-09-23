<?php
include "config.php";
$now = date("Y-m-d H:i:s");
// get search keyword if any
$keyword = trim($_GET['search'] ?? '');
// Fetch all matches
$res = $conn->query("SELECT * FROM matches ORDER BY match_time ASC");
$news_result = $conn->query("SELECT * FROM top_news ORDER BY created_at DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Football Action - News</title>
<link rel="stylesheet" href="assets/css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      <a href="#">National XI</a>
      <a href="#">English-PL</a>
      <a href="#">La-Liga</a>
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
  <!-- ==================== MOBILE ONLY SLIDER ==================== -->
<div class="mobile-slider">
    <div class="mobile-slider-wrapper" id="mobileSliderWrapper">

        <div class="mobile-slide">
            <img src="img/img/imag/Blue Photo Collage Travel Facebook Cover.png" alt="Slide 1">
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
    height: auto;
}

.mobile-slide img {
    width: 100%;
    height: auto;
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
    background: rgba(0,0,0,0.3);
    border-radius: 8px;
    pointer-events: none;
}

/* Show only on mobile */
@media screen and (max-width: 768px) {
    .mobile-slider { display: block; }
}
</style>

<br>
  <div class="mobile-line-row-center">
  <div class="line-top-text-center">Football Live Here</div>
  <div class="mobile-line-center"></div>
</div>

<?php if($res && $res->num_rows > 0): ?>
    <?php while($row = $res->fetch_assoc()): ?>
        <?php 
            $links = json_decode($row['links'], true);
            $status = ($now >= $row['match_time']) ? "LIVE" : "UPCOMING";
        ?>
        <div class="match-card">
          <div class="match-left">
            <?= htmlspecialchars($row['match_name']) ?>
          </div>
          <div class="match-center">
            <?php if($status=="UPCOMING"): ?>
              <span class="countdown" data-time="<?= $row['match_time'] ?>"></span>
            <?php endif; ?>
            <span class="status <?= $status ?>"><?= $status ?></span>
          </div>
          <div class="match-right">
            <?php if(is_array($links) && count($links) > 1): ?>
              <div class="dropdown-main">
                <button class="dropdown-main-btn">Watch</button>
                <div class="dropdown-main-content">
                  <?php foreach($links as $link): ?>
                    <button onclick="showLive('<?= $link ?>')">Link</button>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php elseif(is_array($links) && count($links) == 1): ?>
              <button class="watch-btn" onclick="showLive('<?= $links[0] ?>')">Watch</button>
            <?php endif; ?>
          </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No matches available.</p>
<?php endif; ?>

<script>
  function showLive(link){
    const iframe = document.getElementById('liveFrame');
    iframe.src = link;
    iframe.style.display = 'block';
    iframe.scrollIntoView({behavior:'smooth'});
}

function updateMatches(){
    const now = new Date().getTime();
    document.querySelectorAll('.match-card').forEach(card=>{
        const countdownEl = card.querySelector('.countdown');
        const statusEl = card.querySelector('.status');
        if(!statusEl) return;

        const matchTime = countdownEl ? new Date(countdownEl.dataset.time).getTime() : 0;
        const matchEndTime = countdownEl ? matchTime + 2*60*60*1000 : 0; // 2 hours live duration

        if(countdownEl){
            const distance = matchTime - now;

            if(distance > 0){
                // Upcoming
                const days = Math.floor(distance / (1000*60*60*24));
                const hours = Math.floor((distance % (1000*60*60*24))/(1000*60*60));
                const minutes = Math.floor((distance % (1000*60*60))/(1000*60));
                const seconds = Math.floor((distance % (1000*60))/1000);
                countdownEl.innerText = days+'d '+hours+'h '+minutes+'m '+seconds+'s';
                statusEl.classList.remove('LIVE','END');
                statusEl.classList.add('UPCOMING');
                statusEl.innerText = "UPCOMING";
            } else if(now >= matchTime && now < matchEndTime){
                // LIVE
                countdownEl.style.display = 'none';
                statusEl.classList.remove('UPCOMING','END');
                statusEl.classList.add('LIVE','animating');
                statusEl.innerText = "LIVE";
            } else {
                // END
                countdownEl.style.display = 'none';
                statusEl.classList.remove('UPCOMING','LIVE','animating');
                statusEl.classList.add('END');
                statusEl.innerText = "END";
            }
        }

        // Show card after JS processed
        card.classList.add('ready');
    });
}

// Run every second
setInterval(updateMatches,1000);
updateMatches();

</script>

<iframe id="liveFrame" src="" style="display:none;"></iframe>
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
        <a href="history.php" class="nav-item">
    <i class="fas fa-history"></i>
    <span>History</span>
</a>

<a href="live.php" class="nav-item">
        <i class="fas fa-broadcast-tower"></i>
        <span>National</span>
    </a>
        <div class="mobile-nav-item mobile-center">
            <a href="index.php" class="home-btn">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <a href="live.php" class="nav-item">
        <i class="fas fa-broadcast-tower"></i>
        <span>Club</span>
    </a>
<a href="contact.php" class="nav-item">
    <i class="fas fa-user"></i>
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
    <a href="#">Transfers</a>
</div>

<script src="js/scrip.js"></script>

</body>
</html>