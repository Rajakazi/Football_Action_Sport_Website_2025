<?php
session_start();

// DEV: show errors (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// load DB config (admin folder -> parent config)
require_once __DIR__ . '/../config.php';

// session check (match your login code; earlier we used $_SESSION['admin'])
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// detect DB connection variable (support both $conn and $mysqli)
$db = null;
if (isset($conn) && $conn instanceof mysqli) {
    $db = $conn;
} elseif (isset($mysqli) && $mysqli instanceof mysqli) {
    $db = $mysqli;
} else {
    // Fatal helpful message
    die("Database connection not found. Make sure config.php creates \$conn or \$mysqli (mysqli).");
}

$errors = [];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // basic validation
    if ($title === '' || $summary === '' || $content === '') {
        $errors[] = "Please fill Title, Summary and Content.";
    }

    // Ensure uploads directory exists (admin/../uploads)
    $uploadDir = __DIR__ . '/../uploads';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $errors[] = "Failed to create uploads directory: $uploadDir. Check permissions.";
        }
    }

    // Handle image upload (optional)
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $fileErr = $_FILES['image']['error'];
        if ($fileErr !== UPLOAD_ERR_OK) {
            $errors[] = "File upload error (code $fileErr).";
        } else {
            $tmp  = $_FILES['image']['tmp_name'];
            $orig = $_FILES['image']['name'];
            $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];

            if (!in_array($ext, $allowed)) {
                $errors[] = "Invalid image type. Allowed: " . implode(", ", $allowed);
            } else {
                // unique filename
                try {
                    $imageName = bin2hex(random_bytes(8)) . '.' . $ext;
                } catch (Exception $e) {
                    $imageName = uniqid() . '.' . $ext;
                }
                $target = $uploadDir . DIRECTORY_SEPARATOR . $imageName;

                if (!move_uploaded_file($tmp, $target)) {
                    $errors[] = "Unable to move uploaded file to $target. Check folder writable.";
                }
            }
        }
    }

    // If no errors, insert into DB using prepared statement
    if (empty($errors)) {
        // Prepare insert. If 'summary' column missing DB will return error which we'll display.
        $stmt = $db->prepare("INSERT INTO news (title, summary, content, image, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt === false) {
            $errors[] = "DB prepare failed: " . $db->error;
        } else {
            $stmt->bind_param("ssss", $title, $summary, $content, $imageName);
            if ($stmt->execute()) {
                $stmt->close();
                // success - redirect to dashboard or show message
                header("Location: dashboard.php?msg=added");
                exit;
            } else {
                $errors[] = "DB execute failed: " . $stmt->error;
                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add News - Admin</title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;background:#f4f6f8;padding:20px;}
  .box{max-width:700px;margin:20px auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08);}
  input, textarea {width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px;}
  button{background:#0b74de;color:#fff;padding:10px 16px;border:none;border-radius:6px;cursor:pointer;}
  .errors{background:#ffe6e6;border:1px solid #ffb3b3;padding:10px;margin-bottom:12px;border-radius:6px;color:#8a0505;}
  .success{background:#e6ffea;border:1px solid #b3ffcf;padding:10px;margin-bottom:12px;border-radius:6px;color:#066a1a;}
</style>
</head>
<body>
  <div class="box">
    <h2>Add Football News</h2>

    <?php if (!empty($errors)): ?>
      <div class="errors"><strong>Errors:</strong>
        <ul><?php foreach($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
      </div>
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="success"><?=htmlspecialchars($message)?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <label>Title *</label>
      <input type="text" name="title" value="<?=htmlspecialchars($_POST['title'] ?? '')?>" required>

      <label>Summary * (short text shown on cards)</label>
      <textarea name="summary" rows="3" required><?=htmlspecialchars($_POST['summary'] ?? '')?></textarea>

      <label>Content *</label>
      <textarea name="content" rows="8" required><?=htmlspecialchars($_POST['content'] ?? '')?></textarea>

      <label>Image (optional)</label>
      <input type="file" name="image" accept="image/*">

      <div style="margin-top:12px;">
        <button type="submit">Add News</button>
        &nbsp; <a href="dashboard.php">Back to Dashboard</a>
      </div>
    </form>
  </div>
</body>
</html>
