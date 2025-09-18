<?php
require_once "../config.php";

// detect DB variable
$db = isset($conn) ? $conn : (isset($mysqli) ? $mysqli : null);
if (!$db) die("DB connection missing.");

// get news id
$id = (int)($_GET['id'] ?? 0);

// fetch existing news
$stmt = $db->prepare("SELECT * FROM news WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();
$stmt->close();

if (!$news) {
    die("News not found.");
}

// handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $content = $_POST['content'] ?? '';

    // handle image upload
    $image = $news['image']; // default existing image
    if (isset($_FILES['image']) && $_FILES['image']['name'] !== '') {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = uniqid() . "." . $ext;
        $uploadDir = "../uploads/";

        // create uploads folder if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName)) {
            $image = $newName;
        } else {
            echo "<p style='color:red;'>Image upload failed.</p>";
        }
    }

    // update news using prepared statement
    $stmt = $db->prepare("UPDATE news SET title=?, summary=?, content=?, image=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $summary, $content, $image, $id);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>News updated successfully.</p>";
        $news = ['title'=>$title,'summary'=>$summary,'content'=>$content,'image'=>$image]; // update form values
    } else {
        echo "<p style='color:red;'>Update failed: ".$stmt->error."</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit News</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f6f9; color:#333; padding:20px; }
form { max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
form input[type="text"], form textarea { width:100%; padding:10px; margin:10px 0; border-radius:6px; border:1px solid #ccc; }
form button { padding:10px 18px; background:#0b74de; color:#fff; border:none; border-radius:6px; cursor:pointer; transition:0.3s;}
form button:hover { background:#094a99; }
img { max-width:100%; margin-top:10px; border-radius:6px; }
label { font-weight:bold; }
</style>
</head>
<body>
<h2>Edit News</h2>
<form method="post" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" value="<?=htmlspecialchars($news['title'])?>" required>

    <label>Summary</label>
    <textarea name="summary" rows="3" required><?=htmlspecialchars($news['summary'])?></textarea>

    <label>Content</label>
    <textarea name="content" rows="6" required><?=htmlspecialchars($news['content'])?></textarea>

    <label>Image</label><br>
    <?php if(!empty($news['image'])): ?>
        <img src="../uploads/<?=htmlspecialchars($news['image'])?>" alt="Current Image">
    <?php endif; ?>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Update News</button>
    <div style="margin-top:12px;">
        &nbsp; <a href="dashboard.php">Back to Dashboard</a>
      </div>
</form>
</body>
</html>
