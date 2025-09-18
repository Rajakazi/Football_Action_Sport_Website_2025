<?php
require_once "config.php";
$news_result = $conn->query("SELECT * FROM top_news ORDER BY created_at DESC LIMIT 10");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $target = "uploads/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $conn->query("INSERT INTO contact_messages (name,email,message,image) 
                  VALUES ('$name','$email','$message','$imageName')");
    echo "<script>alert('Message Sent Successfully!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Football Sports</title>
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
  <a href="about.php">Contact</a>
  <a href="about.php">More</a>
</nav>

    <div class="nav-right">
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
<br>
<br>
  <div class="adv-title">
        <span>Contact Us</span>
    </div>
  <div class="container-main">
    <!-- LEFT SLIDER -->
    <div class="slider-main">
      <div class="slides-main">
        <img src="https://images.pexels.com/photos/46798/the-ball-stadion-football-the-pitch-46798.jpeg" alt="">
        <img src="https://images.pexels.com/photos/399187/pexels-photo-399187.jpeg" alt="">
        <img src="https://images.pexels.com/photos/274506/pexels-photo-274506.jpeg" alt="">
        <img src="https://images.pexels.com/photos/114296/pexels-photo-114296.jpeg" alt="">
        <img src="https://images.pexels.com/photos/274422/pexels-photo-274422.jpeg" alt="">
      </div>
      <div class="overlay">
        <h1>Welcome to Football World</h1>
        <p>Join the action. Stay connected with your team!</p>
        <a href="#contact-form">Join Us</a>
      </div>
    </div>

    <!-- RIGHT FORM -->
    <div class="form-box" id="contact-form">
      <form method="POST" enctype="multipart/form-data">
        <h2>📩 Contact Us</h2>
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Message</label>
        <textarea name="message" required></textarea>

        <label>Upload Image (optional)</label>
        <input type="file" name="image">

        <button type="submit">Send Message</button>

        <div class="social">
          <p>Follow us:</p>
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </form>
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
<a href="#" class="nav-item">
    <i class="fas fa-history"></i>
    <span>History</span>
</a>

<a href="#" class="nav-item">
        <i class="fas fa-broadcast-tower"></i>
        <span>Live</span>
    </a>
        <div class="mobile-nav-item mobile-center">
            <a href="index.php" class="home-btn">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <a href="#" class="nav-item">
    <i class="fas fa-calendar-alt"></i>
    <span>Event</span>
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
        <h3>Menu</h3>
        <div class="close-btn" onclick="toggleMobileMenu()">×</div>
    </div>
    <a href="#">Home</a>
    <a href="#">Live</a>
    <a href="#">Event</a>
    <a href="#">History</a>
    <a href="#">Contact</a>
    <a href="#">Players</a>
    <a href="#">Club</a>
</div>

<script src="js/scrip.js"></script>
</body>
</html>
