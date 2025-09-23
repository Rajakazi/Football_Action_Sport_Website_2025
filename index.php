<?php
require_once "config.php";
$result = $conn->query("SELECT * FROM top_news_lets ORDER BY created_at DESC");
if($result->num_rows == 0) {
    echo "No news found!";
}
$result = $conn->query("SELECT * FROM football_news ORDER BY created_at DESC");
$news = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }
}
// Load messages from DB
$stmt = $conn->prepare("SELECT accept_message, reject_message FROM consent_settings ORDER BY id DESC LIMIT 1");
$stmt->execute();
$stmt->bind_result($accept_message, $reject_message);
$stmt->fetch();
$stmt->close();

$consent = isset($_COOKIE['site_consent']) ? $_COOKIE['site_consent'] : null;
$message = "";

if ($consent === "accepted") {
    $message = $accept_message;
} elseif ($consent === "rejected") {
    $message = $reject_message;
}

// Fetch top news for ticker
$news_result = $conn->query("SELECT * FROM top_news ORDER BY created_at DESC LIMIT 10");

// Fetch sliders
$slider_result = $conn->query("SELECT * FROM slider ORDER BY id DESC");
$adv_result = $conn->query("SELECT * FROM adv_slider ORDER BY id DESC");

// Fetch news for main section
$keyword = trim($_GET['search'] ?? '');
$sql = "SELECT id, headline, summary, images, created_at FROM news";
if ($keyword !== '') {
    $safe = $conn->real_escape_string($keyword);
    $sql .= " WHERE headline LIKE '%$safe%' OR summary LIKE '%$safe%' OR content LIKE '%$safe%'";
}
$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);

// Fetch latest points & fixture images
$points_result = $conn->query("SELECT * FROM uploads WHERE type='points' ORDER BY created_at DESC");
$fixture_result = $conn->query("SELECT * FROM uploads WHERE type='fixture' ORDER BY created_at DESC");
$laliga_result = $conn->query("SELECT * FROM uploads WHERE type='laliga' ORDER BY created_at DESC");

// Newsletter subscription
$message = '';
if (isset($_POST['subscribe'])) {
    $email = trim($_POST['email'] ?? '');
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT INTO newsletter (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        $message = $stmt->execute() ? "✅ Thank you for subscribing!" : "❌ Something went wrong!";
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


<!-- ==================== MOBILE ONLY SLIDER ==================== -->
<div class="mobile-slider">
    <div class="mobile-slider-wrapper" id="mobileSliderWrapper">

        <div class="mobile-slide">
            <img src="img/img/imag/Black and Blue Artificial Intelligence Facebook Cover (4).png" alt="Slide 1">
            <div class="mobile-overlay"></div>
        </div>

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
    background: rgba(0,0,0,0.3);
    border-radius: 8px;
    pointer-events: none;
}

/* Show only on mobile */
@media screen and (max-width: 768px) {
    .mobile-slider { display: block; }
}
</style>
<div class="mobile-line-row">
  <div class="line-top-text">Today News [ Football Action ]</div>
  <div class="mobile-line"></div>
</div>
<div class="news-section">
  <?php if (!empty($news)): ?>
    <?php foreach ($news as $index => $row): ?>
      <div class="news-item">
      <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <?php if (!empty($row['image']) && file_exists(__DIR__ . "/images/" . $row['image'])): ?>
          <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="News Image">
        <?php endif; ?>
        <div class="news-content">
          <?php 
            $summary = $row['summary'] ?? '';
            $truncated = (strlen($summary) > 250) ? mb_substr($summary, 0, 250).'...' : $summary;
          ?>
          <p id="summary-<?php echo $index; ?>" data-full="<?php echo htmlspecialchars($summary, ENT_QUOTES); ?>">
            <?php echo htmlspecialchars($truncated); ?>
          </p>
          <?php if (strlen($summary) > 250): ?>
            <span class="read-more-btn" onclick="toggleSummary(<?php echo $index; ?>)">See More</span>
          <?php endif; ?>
        </div>
      </div>
      <!-- Horizontal row line -->
<div class="row-line"></div>

    <?php endforeach; ?>
  <?php else: ?>
    <p style="text-align:center;">No news available.</p>
  <?php endif; ?>
</div>

<script>
function toggleSummary(id) {
    const p = document.getElementById('summary-' + id);
    const fullText = p.dataset.full;
    if (p.dataset.expanded === "true") {
        p.textContent = fullText.substring(0, 250) + '...';
        p.dataset.expanded = "false";
    } else {
        p.textContent = fullText;
        p.dataset.expanded = "true";
    }
}
</script>

<?php
// Fetch main news
$keyword = trim($_GET['search'] ?? '');
$sql = "SELECT id, headline, summary, images, views, created_at FROM news";
if($keyword !== ''){
    $safe = $conn->real_escape_string($keyword);
    $sql .= " WHERE headline LIKE '%$safe%' OR summary LIKE '%$safe%' OR content LIKE '%$safe%'";
}
$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        $images = !empty($row['images']) ? json_decode($row['images'], true) : [];
?>

    <!-- Mobile row card -->
    <div class="mobile-card">
        <?php if(!empty($images[0]) && file_exists("uploads/".$images[0])): ?>
            <div class="mobile-card-img">
                <img src="uploads/<?=htmlspecialchars($images[0])?>" alt="<?=htmlspecialchars($row['headline'])?>">
            </div>
        <?php endif; ?>
        <div class="mobile-card-body">
            <h3><?=htmlspecialchars($row['headline'])?></h3>
            <span id="views-mobile-<?=$row['id']?>" class="" ><?= (int)($row['views'] ?? 0) ?> views</span>
            <a href="news.php?id=<?=$row['id']?>" class="read-more" data-id="<?=$row['id']?>">Read More</a>
        </div>
    </div>

<?php
    endwhile;
else:
?>
    <div class="no-results">No news found.</div>
<?php endif; ?>
</div>



<?php if ($message): ?>
  <div class="top-message <?php echo ($consent === 'rejected') ? 'reject' : ''; ?>">
    <?php echo htmlspecialchars($message); ?>
  </div>
<?php endif; ?>

<?php if (!$consent): ?>
  <div class="consent-card" id="consentBox">
    <h3>We value your privacy</h3>
    <p>Please accept or reject our policy to continue.</p>
    <button class="btn accept" onclick="setConsent('accepted')">Accept</button>
    <button class="btn reject" onclick="setConsent('rejected')">Reject</button>
  </div>
<?php endif; ?>

<script>
function setConsent(choice){
    document.cookie = "site_consent=" + choice + "; path=/; max-age=" + (60*60*24*30);
    location.reload();
}

window.onload = function(){
  let msg = document.querySelector('.top-message');
  if(msg){
    msg.style.display = 'block';
    setTimeout(()=>{ msg.style.display='none'; }, 4000);
  }
}
</script>
<style>

/* Default: hide all news */
.news-container { display: none; }

/* Only show on desktop/laptop */
@media (min-width: 1024px) {
  .news-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  .news-card {
    background: white;
    border-radius: 8px;
    width: 300px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    overflow: hidden;
  }

  .news-card img { width: 100%; height: 180px; object-fit: cover; }
  .news-card .content { padding: 15px; }
  .news-card .content h2 { font-size: 20px; margin: 0 0 10px; }
  .news-card .content p { font-size: 14px; color: #555; }
}
</style>
</head>
<body>
<h1>Latest Football News</h1>

<div class="news-container">
<?php while($row = $result->fetch_assoc()): ?>
  <div class="news-card">
    <?php if($row['image']): ?>
      <img src="uploads/<?= $row['image'] ?>" alt="news">
    <?php endif; ?>
    <div class="content">
      <h2><?= htmlspecialchars($row['title']) ?></h2>
      <p><?= substr(htmlspecialchars($row['content']),0,100) ?>...</p>
    </div>
  </div>
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
<a href="fifa_rankiing.php" class="nav-item">
<i class="fa-solid fa-futbol"></i>
    <span>FIFA</span>
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
