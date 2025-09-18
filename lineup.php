<?php
include 'config.php';

// Fetch latest club info
$club = $conn->query("SELECT * FROM club_info ORDER BY id DESC LIMIT 1")->fetch_assoc();

// Fetch lineup
$lineup = $conn->query("SELECT * FROM lineup ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
<title><?=$club['club_name']?> - Line-Up</title>
<style>
body {
    font-family: Poppins, sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 0;
}

/* Header */
header {
    background: linear-gradient(135deg,#0b74de,#074a99);
    padding: 15px 30px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

header .logo img {
    height: 50px;
    width: 50px;
    border-radius: 50%;
    object-fit: cover;
}

/* Navbar */
.navbar {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.navbar a {
    color: #fff;
    text-decoration: none;
    padding: 8px 15px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.navbar a:hover {
    background: #ffdd57;
    color: #074a99;
}

/* Club Info */
.club-info {
    text-align: center;
    background: #fff;
    margin: 20px auto;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.club-info h2 { color:#0b74de; margin-bottom:8px; font-size:2rem; }
.club-info p { color:#555; margin:3px 0; font-size:1.1rem; }

/* Cards Grid */
.container {
    max-width: 1100px;
    margin: 30px auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px,1fr));
    gap: 25px;
    padding: 0 15px;
}

/* Individual Card */
.card {
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    text-align:center;
    box-shadow:0 6px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    position: relative;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.2);
}

/* Image & Overlay */
.card img {
    width:100%;
    height:200px;
    object-fit:cover;
}

.overlay-top {
    position:absolute;
    top:0;
    left:0;
    width:100%;
    background: rgba(11,116,222,0.85);
    color: #fff;
    padding:10px 5px;
    font-weight:600;
    text-align:center;
    border-bottom-left-radius:16px;
    border-bottom-right-radius:16px;
}

.overlay-top p { margin:3px 0; font-size:0.9rem; }

/* Bottom Coach Box */
.card-bottom {
    background:#074a99;
    color:#fff;
    padding:8px 0;
    font-weight:500;
    border-radius:0 0 16px 16px;
    font-size:0.95rem;
}

/* Share Buttons */
.share-buttons {
    display:flex;
    justify-content:center;
    gap:8px;
    padding:8px 0;
}

.share-buttons a {
    text-decoration:none;
    color:#fff;
    font-size:12px;
    padding:5px 8px;
    border-radius:6px;
    transition:0.3s;
}

.share-facebook { background:#3b5998; }
.share-facebook:hover { background:#2d4373; }

.share-twitter { background:#1da1f2; }
.share-twitter:hover { background:#0d95e8; }

.share-whatsapp { background:#25d366; }
.share-whatsapp:hover { background:#1ebe57; }

/* Responsive */
@media(max-width:1024px){ .card img { height:180px; } }
@media(max-width:768px){ .container { grid-template-columns: repeat(auto-fit, minmax(140px,1fr)); } .card img { height:160px; } }
@media(max-width:480px){ .container { grid-template-columns:1fr; gap:15px; } .card img { height:200px; } }

</style>
</head>
<body>

<header>
    <div class="logo"><img src="img/logo.png" alt="Logo"></div>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="lineup.php">Line-Up</a>
        <a href="news.php">News</a>
        <a href="contact.php">Contact</a>
    </div>
</header>

<div class="club-info">
    <h2><?=$club['club_name']?></h2>
    <p>Coach: <?=$club['coach_name']?></p>
    <p>Captain: <?=$club['captain_name']?></p>
</div>

<div class="container">
<?php while($row=$lineup->fetch_assoc()): ?>
    <div class="card" onclick="window.location='player.php?id=<?=$row['id']?>'">
        <?php if($row['image']!=''): ?>
            <img src="uploads/<?=htmlspecialchars($row['image'])?>" alt="">
        <?php endif; ?>
        <div class="overlay-top">
            <p><?=$club['club_name']?></p>
            <p>Captain: <?=$club['captain_name']?></p>
        </div>
        <div class="card-bottom">Coach: <?=$club['coach_name']?></div>
        <div class="share-buttons">
            <a class="share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode('http://yourwebsite.com/player.php?id='.$row['id'])?>" target="_blank">FB</a>
            <a class="share-twitter" href="https://twitter.com/intent/tweet?url=<?=urlencode('http://yourwebsite.com/player.php?id='.$row['id'])?>" target="_blank">TW</a>
            <a class="share-whatsapp" href="https://api.whatsapp.com/send?text=<?=urlencode('Check this player: http://yourwebsite.com/player.php?id='.$row['id'])?>" target="_blank">WA</a>
        </div>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
