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

<script>
// Slider JS
const wrapper = document.getElementById('sliderWrapper');
const slides = document.querySelectorAll('.slide');
const dotsContainer = document.getElementById('dotsContainer');
let index = 0;

// Create dots
slides.forEach((_, i) => {
    const dot = document.createElement('span');
    dot.classList.add('dot');
    if(i === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(i));
    dotsContainer.appendChild(dot);
});

function updateDots() {
    document.querySelectorAll('.dot').forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}

function goToSlide(i) {
    index = i;
    wrapper.style.transform = `translateX(-${index * 100}%)`;
    updateDots();
}

// Auto slide
setInterval(() => {
    index = (index + 1) % slides.length;
    wrapper.style.transform = `translateX(-${index * 100}%)`;
    updateDots();
}, 4000);

</script>


<div class="adv-section">
    <!-- Title with lines -->
    <div class="adv-title">
        <span>Advertisement Here</span>
    </div>

    <!-- Advertisement Slider -->
    <div class="adv-slider-container">
        <div class="adv-slider-wrapper" id="advSlider">
            <?php while($row = $adv_result->fetch_assoc()): ?>
            <div class="adv-slide">
                <img src="uploads/<?=htmlspecialchars($row['image'])?>" alt="Advertisement">
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>



<div class="adv-slider-container">
    <div class="adv-slider-wrapper" id="advSlider">
        <?php while($row = $adv_result->fetch_assoc()): ?>
        <div class="adv-slide">
            <img src="uploads/<?=htmlspecialchars($row['image'])?>" alt="Advertisement">
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
const slider = document.getElementById('advSlider');
let scrollPos = 0;

// Duplicate slides for seamless loop
slider.innerHTML += slider.innerHTML;

function autoScroll(){
    scrollPos += 1; // Scroll speed
    if(scrollPos >= slider.scrollWidth / 2){ // reset after half width
        scrollPos = 0;
    }
    slider.style.transform = `translateX(-${scrollPos}px)`;
    requestAnimationFrame(autoScroll);
}

autoScroll();
</script>

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


  <div class="adv-title">
        <span>Primer League Point Table</span>
    </div>
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
</body>
</html>
