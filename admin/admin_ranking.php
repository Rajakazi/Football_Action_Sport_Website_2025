<?php
// admin_ranking.php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config.php'; // DB connection

$errors = [];
$message = "";

// upload directory: one level up from admin/ (../uploads/)
$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (isset($_POST['submit'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if ($title === '' || $category === '') {
        $errors[] = "Title and Category are required.";
    }

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['image']['tmp_name'];
        $orig = $_FILES['image']['name'];
        $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];

        if (!in_array($ext, $allowed, true)) {
            $errors[] = "Invalid image type. Allowed: jpg, jpeg, png, gif, webp.";
        } else {
            $imageName = uniqid('img_', true) . '.' . $ext;
            $target = $uploadDir . $imageName;

            if (!move_uploaded_file($tmp, $target)) {
                $errors[] = "Failed to move uploaded file.";
            }
        }
    } else {
        $errors[] = "Please select an image.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO ranking (title, description, image, category, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $title, $description, $imageName, $category);
        if ($stmt->execute()) {
            $message = "Ranking uploaded successfully.";
        } else {
            $errors[] = "DB error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare("SELECT image FROM ranking WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($img);
    if ($stmt->fetch()) {
        $stmt->close();
        if ($img && file_exists($uploadDir . $img)) {
            unlink($uploadDir . $img);
        }
        $del = $conn->prepare("DELETE FROM ranking WHERE id=?");
        $del->bind_param("i", $id);
        $del->execute();
        $del->close();
        header("Location: admin_ranking.php?deleted=1");
        exit;
    }
}

$res = $conn->query("SELECT * FROM ranking ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Ranking</title>
<style>
body{font-family:Arial;background:#f2f5f8;padding:20px;}
.container{max-width:900px;margin:auto;}
form{background:#fff;padding:15px;border-radius:8px;margin-bottom:20px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
input,textarea,select{width:100%;padding:10px;margin:6px 0;border:1px solid #ccc;border-radius:6px;}
button{padding:10px 15px;background:#007bff;color:#fff;border:none;border-radius:6px;cursor:pointer;}
img.thumb{max-width:120px;border-radius:6px;}
table{width:100%;border-collapse:collapse;background:#fff;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
th,td{padding:10px;border-bottom:1px solid #eee;}
.notice{padding:10px;margin:10px 0;border-radius:6px;}
.notice.error{background:#ffe0e0;color:#900;}
.notice.success{background:#e0ffe6;color:#060;}
</style>
</head>
<body>
<div class="container">
    <h1>Upload Ranking</h1>

    <?php if($errors): ?>
        <div class="notice error"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div>
    <?php endif; ?>
    <?php if($message): ?>
        <div class="notice success"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Title *</label>
        <input type="text" name="title" required>
        <label>Description</label>
        <textarea name="description"></textarea>
        <label>Category *</label>
        <select name="category" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="asia_male">Asia Male</option>
            <option value="asia_female">Asia Female</option>
            <option value="south_america">South America</option>
            <option value="other">Other</option>
        </select>
        <label>Image *</label>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="submit">Upload</button>
    </form>

    <h2>Existing Rankings</h2>
    <table>
        <tr><th>ID</th><th>Title</th><th>Category</th><th>Image</th><th>Created</th><th>Action</th></tr>
        <?php if($res && $res->num_rows>0): while($r=$res->fetch_assoc()): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['category']) ?></td>
            <td><?php if($r['image']): ?><img class="thumb" src="../uploads/<?= htmlspecialchars($r['image']) ?>"><?php endif; ?></td>
            <td><?= $r['created_at'] ?></td>
            <td><a href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this ranking?')">Delete</a></td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="6">No records found</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
