<?php
session_start();
require_once __DIR__ . '/../config.php';

$errors = [];
$message = "";
$targetDir = __DIR__ . "/uploads/";
// Handle Add / Edit News
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = intval($_POST['id'] ?? 0);
    $headline = trim($_POST['headline'] ?? '');
    $type     = trim($_POST['type'] ?? '');
    $content  = trim($_POST['content'] ?? '');

    if($headline === '' || $type === '' || $content === ''){
        $errors[] = "All fields except images are required.";
    }

    // Handle image uploads
    $imageNames = [];
    for($i=1; $i<=5; $i++){
        if(isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] === UPLOAD_ERR_OK){
            $tmp = $_FILES["image$i"]['tmp_name'];
            $orig = $_FILES["image$i"]['name'];
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];

            if(in_array($ext,$allowed)){
                $newName = uniqid().'_'.$i.'.'.$ext;
                $uploadDir = __DIR__ . '/../uploads/';
                if(!is_dir($uploadDir)) mkdir($uploadDir,0777,true);

                if(move_uploaded_file($tmp, $uploadDir.$newName)){
                    $imageNames[] = $newName;
                } else {
                    $errors[] = "Failed to upload image $i";
                }
            } else {
                $errors[] = "Invalid type for image $i";
            }
        }
    }

    // If editing, preserve old images
    if($id && empty($imageNames)){
        $old = $conn->query("SELECT images FROM news WHERE id=$id")->fetch_assoc();
        $imageNames = json_decode($old['images'], true);
    }

    $imageJson = !empty($imageNames) ? json_encode($imageNames) : null;

    if(empty($errors)){
        if($id > 0){
            // Update
            $stmt = $conn->prepare("UPDATE news SET headline=?, type=?, content=?, images=? WHERE id=?");
            $stmt->bind_param("ssssi", $headline, $type, $content, $imageJson, $id);
            $stmt->execute();
            $message = "News updated successfully!";
            $stmt->close();
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO news (headline,type,content,images,created_at) VALUES (?,?,?,?,NOW())");
            $stmt->bind_param("ssss", $headline,$type,$content,$imageJson);
            if($stmt->execute()){
                $message = "News added successfully!";
            } else {
                $errors[] = "Insert failed: ".$stmt->error;
            }
            $stmt->close();
        }
    }
}

// Handle Delete
if(isset($_GET['delete'])){
    $del_id = intval($_GET['delete']);
    $row = $conn->query("SELECT images FROM news WHERE id=$del_id")->fetch_assoc();
    if($row && !empty($row['images'])){
        $imgs = json_decode($row['images'], true);
        foreach($imgs as $img) if(file_exists(__DIR__.'/../uploads/'.$img)) unlink(__DIR__.'/../uploads/'.$img);
    }
    $conn->query("DELETE FROM news WHERE id=$del_id");
    header("Location: add_news.php");
    exit;
}

// If editing, fetch single news
$editData = null;
if(isset($_GET['edit'])){
    $edit_id = intval($_GET['edit']);
    $editData = $conn->query("SELECT * FROM news WHERE id=$edit_id")->fetch_assoc();
}

// Fetch all news
$res = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin - News Management</title>
<style>
body{font-family:Arial,sans-serif;background:#f4f6f8;padding:20px;}
.box{max-width:900px;margin:20px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.08);}
input, textarea{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px;font-size:14px;}
button{background:#0b74de;color:#fff;padding:10px 16px;border:none;border-radius:6px;cursor:pointer;font-size:15px;}
.errors{background:#ffe6e6;border:1px solid #ffb3b3;padding:10px;margin-bottom:12px;border-radius:6px;color:#8a0505;}
.success{background:#e6ffea;border:1px solid #b3ffcf;padding:10px;margin-bottom:12px;border-radius:6px;color:#066a1a;}
table{width:100%;border-collapse:collapse;margin-top:20px;}
table, th, td{border:1px solid #ccc;}
th, td{padding:10px;text-align:left;}
th{background:#f0f0f0;}
a.button{background:#0b74de;color:#fff;padding:6px 12px;border-radius:6px;text-decoration:none;}
img.thumb{width:80px;height:auto;border-radius:6px;}
</style>
</head>
<body>
<div class="box">
<h2><?= $editData ? "Edit News" : "Add News" ?></h2>

<?php if(!empty($errors)): ?>
<div class="errors"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
<?php endif; ?>

<?php if($message): ?>
<div class="success"><?=htmlspecialchars($message)?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $editData['id'] ?? 0 ?>">

<label>Headline *</label>
<input type="text" name="headline" value="<?=htmlspecialchars($editData['headline'] ?? $_POST['headline'] ?? '')?>" required>

<label>Type *</label>
<input type="text" name="type" value="<?=htmlspecialchars($editData['type'] ?? $_POST['type'] ?? '')?>" required>

<label>Content *</label>
<textarea name="content" rows="6" required><?=htmlspecialchars($editData['content'] ?? $_POST['content'] ?? '')?></textarea>

<label>Upload Images (up to 5)</label>
<?php
$oldImages = $editData['images'] ?? '';
$oldImages = !empty($oldImages) ? json_decode($oldImages,true) : [];
for($i=1;$i<=5;$i++):
    $imgVal = $oldImages[$i-1] ?? '';
?>
<input type="file" name="image<?=$i?>" accept="image/*">
<?php if($imgVal): ?>
<img src="../uploads/<?=htmlspecialchars($imgVal)?>" class="thumb">
<?php endif; ?>
<?php endfor; ?>

<div style="margin-top:12px;">
<button type="submit"><?= $editData ? "Update News" : "Add News" ?></button>
&nbsp; <a href="add_news.php">Reset</a>
</div>
</form>
</div>

<div class="box">
<h2>All News</h2>
<table>
<tr>
<th>ID</th>
<th>Headline</th>
<th>Type</th>
<th>Content</th>
<th>Images</th>
<th>Actions</th>
</tr>
<?php while($row = $res->fetch_assoc()): 
    $imgs = !empty($row['images']) ? json_decode($row['images'], true) : [];
?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['headline']) ?></td>
<td><?= htmlspecialchars($row['type']) ?></td>
<td><?= htmlspecialchars(substr($row['content'],0,50)) ?>...</td>
<td>
<?php foreach($imgs as $img): ?>
<img src="../uploads/<?=htmlspecialchars($img)?>" class="thumb">
<?php endforeach; ?>
</td>
<td>
<a href="?edit=<?= $row['id'] ?>" class="button">Edit</a>
<a href="?delete=<?= $row['id'] ?>" class="button" onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>
</body>
</html>
