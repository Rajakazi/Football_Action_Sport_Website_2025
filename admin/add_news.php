<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$errors = [];
$message = "";

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $headline = trim($_POST['headline'] ?? '');
    $type     = trim($_POST['type'] ?? '');
    $summary  = trim($_POST['summary'] ?? '');
    $content  = trim($_POST['content'] ?? '');

    if($headline==='' || $type==='' || $summary==='' || $content===''){
        $errors[] = "All fields except image are required.";
    }

    // Handle image upload
    $imageName = null;
    if(isset($_FILES['image']) && $_FILES['image']['error']!==UPLOAD_ERR_NO_FILE){
        $tmp  = $_FILES['image']['tmp_name'];
        $orig = $_FILES['image']['name'];
        $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if(!in_array($ext,$allowed)){
            $errors[] = "Invalid image type.";
        } else {
            $imageName = uniqid() . '.' . $ext;
            $uploadDir = __DIR__ . '/../uploads/';
            if(!move_uploaded_file($tmp, $uploadDir.$imageName)){
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if(empty($errors)){
        $stmt = $conn->prepare("INSERT INTO news (headline, type, summary, content, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        if(!$stmt){
            $errors[] = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("sssss", $headline, $type, $summary, $content, $imageName);
            if($stmt->execute()){
                $message = "News added successfully!";
                $stmt->close();
            } else {
                $errors[] = "Insert failed: " . $stmt->error;
                $stmt->close();
            }
        }
    }
}

// Fetch existing news
$res = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add News - Admin</title>
<style>
body{font-family:Arial,sans-serif;background:#f4f6f8;padding:20px;}
.box{max-width:700px;margin:20px auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08);}
input, textarea {width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px;}
button{background:#0b74de;color:#fff;padding:10px 16px;border:none;border-radius:6px;cursor:pointer;}
.errors{background:#ffe6e6;border:1px solid #ffb3b3;padding:10px;margin-bottom:12px;border-radius:6px;color:#8a0505;}
.success{background:#e6ffea;border:1px solid #b3ffcf;padding:10px;margin-bottom:12px;border-radius:6px;color:#066a1a;}
.news-list{margin-top:30px;}
.news-item{padding:10px;border-bottom:1px solid #ddd;}
.news-item img{max-width:100px; vertical-align:middle; margin-right:10px;}
</style>
</head>
<body>
<div class="box">
<h2>Add Football News</h2>

<?php if(!empty($errors)): ?>
<div class="errors">
<ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
</div>
<?php endif; ?>

<?php if($message): ?>
<div class="success"><?=htmlspecialchars($message)?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
<label>Headline *</label>
<input type="text" name="headline" value="<?=htmlspecialchars($_POST['headline'] ?? '')?>" required>

<label>Type *</label>
<input type="text" name="type" value="<?=htmlspecialchars($_POST['type'] ?? '')?>" required>

<label>Summary *</label>
<textarea name="summary" rows="3" required><?=htmlspecialchars($_POST['summary'] ?? '')?></textarea>

<label>Content *</label>
<textarea name="content" rows="6" required><?=htmlspecialchars($_POST['content'] ?? '')?></textarea>

<label>Image (optional)</label>
<input type="file" name="image" accept="image/*">

<div style="margin-top:12px;">
<button type="submit">Add News</button>
&nbsp; <a href="dashboard.php">Back to Dashboard</a>
</div>
</form>

<div class="news-list">
<h3>Existing News</h3>
<?php if($res && $res->num_rows>0): ?>
<?php while($row=$res->fetch_assoc()): ?>
<div class="news-item">
<?php if(!empty($row['image'])): ?>
<img src="../uploads/<?=htmlspecialchars($row['image'])?>" alt="<?=htmlspecialchars($row['headline'])?>">
<?php endif; ?>
<?=htmlspecialchars($row['headline'])?> | <?=htmlspecialchars($row['type'])?> 
| <a href="edit_news.php?id=<?=$row['id']?>">Edit</a> 
| <a href="delete_news.php?id=<?=$row['id']?>" onclick="return confirm('Delete this news?')">Delete</a>
</div>
<?php endwhile; ?>
<?php else: ?>
<p>No news found.</p>
<?php endif; ?>
</div>
</div>
</body>
</html>
