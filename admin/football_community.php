<?php
require_once "../config.php";  // database connection

// Handle Add Entry
if(isset($_POST['add_entry'])){
    $title = $_POST['title'] ?? '';
    $note = $_POST['note'] ?? '';
    $image = '';

    if(!empty($_FILES['image']['name'])){
        $image = time().'_'.basename($_FILES['image']['name']);
        $uploadDir = "../uploads/";
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir.$image);
    }

    $stmt = $conn->prepare("INSERT INTO football_community (title, note, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $note, $image);
    $stmt->execute();
}

// Handle Delete
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    // Get image file to delete
    $res = $conn->query("SELECT image FROM football_community WHERE id=$id");
    $row = $res->fetch_assoc();
    if($row['image'] && file_exists("../uploads/".$row['image'])){
        unlink("../uploads/".$row['image']);
    }
    $conn->query("DELETE FROM football_community WHERE id=$id");
    header("Location: football_community_admin.php");
    exit;
}

// Handle Edit
if(isset($_POST['edit_entry'])){
    $id = (int)$_POST['id'];
    $title = $_POST['title'] ?? '';
    $note = $_POST['note'] ?? '';

    if(!empty($_FILES['image']['name'])){
        $image = time().'_'.basename($_FILES['image']['name']);
        $uploadDir = "../uploads/";
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir.$image);

        // Delete old image
        $res = $conn->query("SELECT image FROM football_community WHERE id=$id");
        $row = $res->fetch_assoc();
        if($row['image'] && file_exists("../uploads/".$row['image'])){
            unlink("../uploads/".$row['image']);
        }

        $stmt = $conn->prepare("UPDATE football_community SET title=?, note=?, image=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $note, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE football_community SET title=?, note=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $note, $id);
    }
    $stmt->execute();
    header("Location: football_community_admin.php");
    exit;
}

// Fetch entries
$entries = $conn->query("SELECT * FROM football_community ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin - Football Community</title>
<style>
body{font-family:Poppins,sans-serif; background:#f4f6f9; padding:20px;}
h2{color:#0b74de;}
form{background:#fff; padding:20px; border-radius:10px; margin-bottom:30px; box-shadow:0 6px 20px rgba(0,0,0,0.1);}
input, textarea, button{padding:10px; margin:5px 0; width:100%; border-radius:6px; border:1px solid #ccc;}
textarea{height:100px;}
button{background:#0b74de;color:#fff;border:none;cursor:pointer;}
button:hover{background:#094a99;}
img{max-width:100px;margin-top:10px;border-radius:6px;}
.entry-box{background:#fff;padding:15px;margin-bottom:20px;border-radius:10px;box-shadow:0 6px 20px rgba(0,0,0,0.1);}
.entry-box img{max-width:150px; display:block; margin-bottom:10px;}
.actions { margin-top:10px; }
.actions a { margin-right:10px; color:#fff; background:#ff5722; padding:5px 10px; border-radius:5px; text-decoration:none;}
.actions a.delete { background:#dc3545;}
.actions a:hover { opacity:0.8; }
</style>
</head>
<body>

<h2>Add Community Entry</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="note" placeholder="Note" required></textarea>
    <input type="file" name="image" accept="image/*">
    <button type="submit" name="add_entry">Add Entry</button>
</form>

<h2>Existing Entries</h2>
<?php while($row = $entries->fetch_assoc()): ?>
<div class="entry-box">
    <?php if($row['image']): ?>
        <img src="../uploads/<?=htmlspecialchars($row['image'])?>" alt="">
    <?php endif; ?>
    <h3><?=htmlspecialchars($row['title'])?></h3>
    <p><?=nl2br(htmlspecialchars($row['note']))?></p>
    <small>Date: <?=htmlspecialchars($row['created_at'])?></small>
    <div class="actions">
        <a href="?delete=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
        <a href="football_community_edit.php?id=<?= $row['id'] ?>">Edit</a>
    </div>
</div>
<?php endwhile; ?>

</body>
</html>
