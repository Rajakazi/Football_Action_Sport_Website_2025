<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <img src="assets/images/logo.png" alt="Football Action">
    <nav>
        <a href="index.php">Home</a>
    </nav>
</header>

<div class="container">
    <h2>Search Results</h2>
    <?php
    $q = $_GET['q'];
    $result = $conn->query("SELECT * FROM news WHERE title LIKE '%$q%' OR content LIKE '%$q%'");
    while($row = $result->fetch_assoc()) {
        echo "<div class='news-card'>
                <img src='uploads/".$row['image']."' alt=''>
                <h3>".$row['title']."</h3>
                <a href='news.php?id=".$row['id']."'>Read More</a>
              </div>";
    }
    ?>
</div>
</body>
</html>
