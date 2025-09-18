<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../config.php';
?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
<h2>Welcome, <?php echo $_SESSION['admin']; ?> | <a href="logout.php">Logout</a></h2>
<a href="add_news.php">➕ Add News</a>
<a href="lineup.php">➕ Add Lineup</a>
<a href="top_news.php">Top News</a>
<a href="slider_admin.php">Slider add</a>
<a href="adv_slider_admin.php">Advat.</a>
<a href="points_admin.php">Point</a>
<a href="legend_players_admin.php">About us</a>
<a href="football_community.php">Community</a>
<a href="event.php">add Event</a>
<a href="gallery_upload.php">Image Add</a>
<a href="messages.php">Contact</a>
<a href="live_admin.php">live link add</a>
<a href="football_news.php">Football news add</a>
<a href="add_calender.php">fifa calendar add</a>
<a href="admin_ranking.php">add Ranking</a>
<a href="admin_club_ranking.php">add club ranking</a>
<a href="admin_lineup.php">National Line-Up</a>
<a href="admin_bio.php">add player bio</a>



<hr>
<?php
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
while($row = $result->fetch_assoc()) {
    echo "<div>
            <h3>".$row['title']."</h3>
            <a href='edit_news.php?id=".$row['id']."'>✏️ Edit</a> |
            <a href='delete_news.php?id=".$row['id']."' onclick='return confirm(\"Delete?\")'>🗑 Delete</a>
          </div><hr>";
}
?>
</body>
</html>
