<?php
include "../config.php";

$message = "";
$errors = [];

if(isset($_POST['add_calendar'])){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    
    // Image Upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if(!in_array($ext, $allowed)){
            $errors[] = "Invalid image type!";
        } else {
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$imageName");
        }
    } else {
        $errors[] = "Image is required!";
    }

    if(empty($errors)){
        $stmt = $conn->prepare("INSERT INTO calendar (title,image,description) VALUES (?,?,?)");
        $stmt->bind_param("sss",$title,$imageName,$description);
        if($stmt->execute()){
            $message = "Calendar event added successfully!";
        } else {
            $errors[] = "Database error: ".$stmt->error;
        }
    }
}

// Delete system
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    // Remove image first
    $res = $conn->query("SELECT image FROM calendar WHERE id=$id");
    if($res && $row=$res->fetch_assoc()){
        @unlink("uploads/".$row['image']);
    }
    $conn->query("DELETE FROM calendar WHERE id=$id");
    header("Location: add_calendar.php");
}

$res = $conn->query("SELECT * FROM calendar ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Calendar</title>
<style>
body{font-family:Arial,sans-serif; background:#f4f4f4; padding:20px;}
.box{max-width:800px;margin:0 auto;background:#fff;padding:20px;border-radius:8px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
input,textarea {width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:5px;}
button{padding:10px 15px;background:#007bff;color:#fff;border:none;border-radius:5px;cursor:pointer;}
a.delete{color:red;text-decoration:none;margin-left:10px;}
img{max-width:100px;border-radius:5px;}
</style>
</head>
<body>
<div class="box">
<h2>Add Calendar Event</h2>
<?php if($message) echo "<p style='color:green;'>$message</p>"; ?>
<?php if($errors) echo "<ul style='color:red;'><li>".implode("</li><li>",$errors)."</li></ul>"; ?>
<form method="post" enctype="multipart/form-data">
<label>Title *</label>
<input type="text" name="title" required>
<label>Description</label>
<textarea name="description" rows="3"></textarea>
<label>Image *</label>
<input type="file" name="image" required>
<button type="submit" name="add_calendar">Add Event</button>
</form>

<hr>
<h3>Existing Events</h3>
<table border="1" cellpadding="5" cellspacing="0">
<tr><th>ID</th><th>Title</th><th>Image</th><th>Description</th><th>Action</th></tr>
<?php while($row=$res->fetch_assoc()): ?>
<tr>
<td><?=$row['id']?></td>
<td><?=htmlspecialchars($row['title'])?></td>
<td><img src="uploads/<?=$row['image']?>" alt=""></td>
<td><?=htmlspecialchars($row['description'])?></td>
<td><a href="?delete=<?=$row['id']?>" class="delete" onclick="return confirm('Delete this event?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>
</div>
<a href="dashboard.php">Home</a>
</body>
</html>
