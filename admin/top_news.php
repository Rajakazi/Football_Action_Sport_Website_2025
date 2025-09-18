<?php
// admin/top_news.php
require_once '../config.php';

// support both $conn and $mysqli from different configs
$db = isset($conn) ? $conn : (isset($mysqli) ? $mysqli : null);
if (!$db) {
    // friendly error — change this message if you want
    die("DB connection missing. Check config.php and ensure it defines \$conn or \$mysqli.");
}

// HANDLE INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['headline'])) {
    $headline = trim($_POST['headline']);
    if ($headline !== '') {
        $stmt = $db->prepare("INSERT INTO top_news (headline) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $headline);
            $stmt->execute();
            $stmt->close();
        } else {
            // debug-friendly message (remove in production)
            error_log("Prepare failed: " . $db->error);
        }
    }
    header("Location: top_news.php");
    exit;
}

// HANDLE DELETE (optional)
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id > 0) {
        $stmt = $db->prepare("DELETE FROM top_news WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        } else {
            error_log("Prepare failed: " . $db->error);
        }
    }
    header("Location: top_news.php");
    exit;
}

// FETCH latest headlines (limit 10)
$news_result = $db->query("SELECT id, headline, created_at FROM top_news ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Admin - Top News</title>
  <style>
    body{font-family: Poppins, sans-serif; background:#f4f6f9; margin:20px;}
    h1{color:#0b74de;}
    form {background:#fff; padding:12px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); max-width:700px;}
    input[type="text"]{width:100%; padding:8px 10px; border:1px solid #ccc; border-radius:6px; margin-bottom:10px;}
    button{background:#0b74de; color:#fff; padding:8px 12px; border:none; border-radius:6px; cursor:pointer;}
    table{width:100%; border-collapse:collapse; margin-top:16px; max-width:900px;}
    th,td{padding:10px; border-bottom:1px solid #e9e9e9; text-align:left;}
    .small{font-size:13px; color:#666;}
    .actions a{color:#d9534f; text-decoration:none; margin-left:8px;}
    .actions a:hover{ text-decoration:underline; }
    .note{margin-top:12px; color:#555;}
  </style>
</head>
<body>

  <h1>Top News — Admin</h1>

  <form method="post" action="top_news.php">
    <label for="headline"><strong>Add Top Headline</strong></label>
    <input id="headline" type="text" name="headline" placeholder="Type headline (e.g. Ronaldo scores...)" required maxlength="255" />
    <div>
      <button type="submit">Add Headline</button>
    </div>
    <p class="note">Headlines added here will appear in the site's top news ticker.</p>
  </form>

  <h2>Latest Headlines</h2>
  <table>
    <thead>
      <tr><th>Headline</th><th class="small">Added</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if ($news_result && $news_result->num_rows > 0): ?>
        <?php while($r = $news_result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($r['headline']); ?></td>
            <td class="small"><?php echo htmlspecialchars($r['created_at']); ?></td>
            <td class="actions">
              <a href="top_news.php?delete=<?php echo (int)$r['id']; ?>" onclick="return confirm('Delete this headline?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="3">No headlines yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  <a href="dashboard.php">Home</a>
</body>
</html>
