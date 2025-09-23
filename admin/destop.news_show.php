<?php
include "../config.php";

// Initialize variables
$edit_id = 0;
$title = "";
$content = "";
$imageName = "";

// Handle edit request
if(isset($_GET['edit'])){
    $edit_id = intval($_GET['edit']);
    $row = $conn->query("SELECT * FROM top_news_lets WHERE id=$edit_id")->fetch_assoc();
    $title = $row['title'];
    $content = $row['content'];
    $imageName = $row['image'];
}

// Handle delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM top_news_lets WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === "POST"){
    $title = $_POST['title'];
    $content = $_POST['content'];

    if(isset($_FILES['image']) && $_FILES['image']['name'] != ''){
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$imageName");
    }

    if($_POST['id'] > 0){
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE top_news_lets SET title=?, content=?, image=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $content, $imageName, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO top_news_lets (title, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $imageName);
        $stmt->execute();
    }
    header("Location: destop.news_show.php");
    exit;
}

// Fetch all news
$result = $conn->query("SELECT * FROM top_news_lets ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin - Top News</title>
<style>
body { font-family: Arial; margin: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background: #f4f4f4; }
img { max-width: 100px; }
a { text-decoration: none; margin-right: 5px; }
button { padding: 5px 10px; }
form { margin-bottom: 30px; }
</style>
</head>
<body>

<h1>Admin - Add / Edit Top News</h1>

<form method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $edit_id ?>">
<label>Title:</label><br>
<input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required><br><br>

<label>Content:</label><br>
<textarea name="content" rows="5" cols="50" required><?= htmlspecialchars($content) ?></textarea><br><br>

<label>Image:</label><br>
<input type="file" name="image"><br>
<?php if($imageName): ?>
<img src="../uploads/<?= $imageName ?>" alt="news" style="max-width:150px;"><br>
<?php endif; ?>
<br>
<button type="submit"><?= $edit_id ? "Update News" : "Add News" ?></button>
</form>

<h2>All News</h2>
<table>
<tr>
<th>ID</th>
<th>Title</th>
<th>Image</th>
<th>Actions</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td>
<?php if($row['image']): ?>
<img src="../uploads/<?= $row['image'] ?>" alt="news">
<?php endif; ?>
</td>
<td>
<a href="admin.php?edit=<?= $row['id'] ?>">Edit</a>
<a href="admin.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
