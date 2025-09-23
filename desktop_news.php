<?php
include "config.php";
$result = $conn->query("SELECT * FROM top_news_lets ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Football News</title>
<style>
body { font-family: Arial; margin: 20px; background: #f9f9f9; }

/* Desktop news container */
.news-container { display: flex; flex-wrap: wrap; gap: 20px; }

/* News card styling */
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

/* ================= Mobile: hide all news ================= */
@media (max-width: 768px) {
  .news-container {
    display: none; /* hide news on mobile */
  }
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

</body>
</html>
