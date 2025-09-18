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
  <br><br>
     <!-- Title with lines -->
     <div class="adv-title">
        <span>Live Streeming</span>
    </div>
    <br>
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
            <?= htmlspecialchars($row['match_type']) ?> | 
            <?php if($status=="UPCOMING"): ?>
              <span class="countdown" data-time="<?= $row['match_time'] ?>"></span>
            <?php endif; ?>
            <span class="status <?= $status ?>"><?= $status ?></span>
          </div>
          <div class="match-right">
            <?php if(is_array($links) && count($links) > 1): ?>
              <div class="dropdown">
                <button class="dropdown-btn">Watch</button>
                <div class="dropdown-content">
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

<iframe id="liveFrame" src="" style="display:none;"></iframe>

<script>
function showLive(link){
    const iframe = document.getElementById('liveFrame');
    iframe.src = link;
    iframe.style.display = 'block';
    iframe.scrollIntoView({behavior:'smooth'});
}

function countdown(){
    const elements = document.querySelectorAll('.countdown');
    const now = new Date().getTime();
    elements.forEach(el=>{
        const matchTime = new Date(el.dataset.time).getTime();
        const distance = matchTime - now;

        if(distance > 0){
            const days = Math.floor(distance / (1000*60*60*24));
            const hours = Math.floor((distance % (1000*60*60*24))/(1000*60*60));
            const minutes = Math.floor((distance % (1000*60*60))/(1000*60));
            const seconds = Math.floor((distance % (1000*60))/1000);
            el.innerText = days+'d '+hours+'h '+minutes+'m '+seconds+'s';
        } else {
            el.innerText = "LIVE";
            el.classList.remove('UPCOMING');
            el.classList.add('LIVE');
        }
    });
}

setInterval(countdown, 1000);
countdown();


// Add this to your existing JavaScript file or in a new script tag

function startLiveAnimation() {
    // Find all elements with the 'status LIVE' classes
    const liveElements = document.querySelectorAll('.status.LIVE');
    
    // Check if any live elements were found
    if (liveElements.length > 0) {
        // Iterate through each live element
        liveElements.forEach(element => {
            // Apply a class that triggers the CSS animation
            // The class 'animating' is a good practice to separate concerns
            element.classList.add('animating');
        });
    }
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', startLiveAnimation);
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