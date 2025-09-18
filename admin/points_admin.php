<?php
require_once "../config.php";  // database connection
$mysqli = new mysqli("localhost", "root", "Milan@1234", "football_action");

// Check connection
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $type = $_POST['type']; // points or fixture
    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $uploadDir = __DIR__ . "/../uploads/";
    $target = $uploadDir . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
        $stmt = $mysqli->prepare("INSERT INTO uploads (type, image) VALUES (?, ?)");
        $stmt->bind_param("ss", $type, $fileName);
        $stmt->execute();
        echo "<p style='color:green;'>Image uploaded successfully!</p>";
    } else {
        echo "<p style='color:red;'>Upload failed!</p>";
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $res = $mysqli->query("SELECT image FROM uploads WHERE id=$id");
    $row = $res->fetch_assoc();
    if ($row && file_exists(__DIR__ . "/../uploads/" . $row['image'])) {
        unlink(__DIR__ . "/../uploads/" . $row['image']); // delete image file
    }
    $mysqli->query("DELETE FROM uploads WHERE id=$id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Fetch existing uploads
$result = $mysqli->query("SELECT * FROM uploads ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Upload</title>
  <style>
    body { font-family: Arial, sans-serif; margin:40px; }
    form { margin-bottom:20px; padding:20px; border:1px solid #ccc; border-radius:10px; width:300px; }
    label { display:block; margin-bottom:8px; font-weight:bold; }
    input[type="file"], select { margin-bottom:12px; width:100%; }
    button { padding:10px 20px; background:#28a745; color:#fff; border:none; border-radius:5px; cursor:pointer; }
    button:hover { background:#218838; }
    .uploads { margin-top:30px; }
    .upload-item { background:#f9f9f9; padding:10px; margin-bottom:10px; border-radius:6px; display:flex; align-items:center; gap:15px; }
    .upload-item img { max-width:100px; border-radius:6px; }
    .delete-btn { padding:6px 12px; background:#dc3545; color:#fff; text-decoration:none; border-radius:5px; }
    .delete-btn:hover { background:#c82333; }
  </style>
</head>
<body>
  <h2>Upload Images</h2>
  <form method="POST" enctype="multipart/form-data">
    <label>Select Type:</label>
    <select name="type" required>
      <option value="points">Football Points</option>
      <option value="fixture">Fixture</option>
    </select>
    <label>Choose Image:</label>
    <input type="file" name="image" required>
    <button type="submit">Upload</button>
  </form>

  <div class="uploads">
    <h2>Existing Uploads</h2>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="upload-item">
        <img src="../uploads/<?=htmlspecialchars($row['image'])?>" alt="Image">
        <span><?=htmlspecialchars($row['type'])?></span>
        <a href="?delete_id=<?=$row['id']?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
      </div>
    <?php endwhile; ?>
  </div>
  <a href="dashboard.php">Home</a>
</body>
</html>
