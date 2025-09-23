<?php
// admin/topclassnews.php

// ================== DATABASE CONNECTION ==================
$host = "localhost";
$user = "root";
$pass = "Milan@1234";
$dbname = "football_action";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

// ================== HANDLE ADD NEWS ==================
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']=='add') {
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    if ($title==='') $errors[] = "Title is required.";
    if ($summary==='') $errors[] = "Summary is required.";

    $uploaded_filename = null;
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $allowed = ['image/jpeg','image/jpg','image/png','image/webp'];
        if ($file['error']!==UPLOAD_ERR_OK) $errors[] = "Image upload error.";
        elseif (!in_array(mime_content_type($file['tmp_name']), $allowed)) $errors[] = "Only JPG, PNG, WEBP allowed.";
        elseif ($file['size'] > 2*1024*1024) $errors[] = "Image <= 2MB.";
        else {
            $targetDir = realpath(__DIR__ . "/../") . "/images/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $uploaded_filename = uniqid('news_',true).".".$ext;
            $dest = $targetDir.$uploaded_filename;
            if (!move_uploaded_file($file['tmp_name'],$dest)) $errors[]="Failed to save file.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO football_news (title, summary, image) VALUES (?,?,?)");
        $stmt->bind_param("sss",$title,$summary,$uploaded_filename);
        if ($stmt->execute()) $success="News added successfully."; 
        else $errors[]="DB error: ".$stmt->error;
        $stmt->close();
    }
}

// ================== HANDLE DELETE ==================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // get image filename
    $res = $conn->query("SELECT image FROM football_news WHERE id=$id");
    $row = $res->fetch_assoc();
    if ($row && $row['image']) {
        $imgPath = realpath(__DIR__ . "/../images/")."/".$row['image'];
        if(file_exists($imgPath)) unlink($imgPath);
    }
    $conn->query("DELETE FROM football_news WHERE id=$id");
    header("Location: topclassnews.php");
    exit;
}

// ================== HANDLE EDIT ==================
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action']) && $_POST['action']=='edit') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $uploaded_filename = null;

    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $allowed = ['image/jpeg','image/jpg','image/png','image/webp'];
        if ($file['error']===UPLOAD_ERR_OK && in_array(mime_content_type($file['tmp_name']),$allowed) && $file['size']<=2*1024*1024) {
            $targetDir = realpath(__DIR__ . "/../") . "/images/";
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $uploaded_filename = uniqid('news_',true).".".$ext;
            $dest = $targetDir.$uploaded_filename;
            if(move_uploaded_file($file['tmp_name'],$dest)){
                // delete old image
                $res = $conn->query("SELECT image FROM football_news WHERE id=$id");
                $row = $res->fetch_assoc();
                if($row && $row['image']){
                    $oldImg = $targetDir.$row['image'];
                    if(file_exists($oldImg)) unlink($oldImg);
                }
            }
        }
    }

    if ($uploaded_filename) {
        $stmt = $conn->prepare("UPDATE football_news SET title=?, summary=?, image=? WHERE id=?");
        $stmt->bind_param("sssi",$title,$summary,$uploaded_filename,$id);
    } else {
        $stmt = $conn->prepare("UPDATE football_news SET title=?, summary=? WHERE id=?");
        $stmt->bind_param("ssi",$title,$summary,$id);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: topclassnews.php");
    exit;
}

// ================== FETCH ALL NEWS ==================
$news = [];
$res = $conn->query("SELECT * FROM football_news ORDER BY created_at DESC");
if ($res) while($row = $res->fetch_assoc()) $news[]=$row;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Football News</title>
    <style>
        body{font-family:Arial; margin:20px;}
        .news-item{border:1px solid #ccc; margin-bottom:10px; padding:10px; border-radius:6px;}
        .news-item img{width:150px; height:100px; object-fit:cover; display:block; margin-bottom:5px;}
        .btn{padding:4px 8px; border:none; border-radius:4px; cursor:pointer;}
        .btn-edit{background:#0b74de; color:#fff;}
        .btn-delete{background:#e74c3c; color:#fff;}
    </style>
</head>
<body>

<h1>Add News</h1>
<?php if(!empty($errors)) foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<?php if(!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <label>Title:</label><br>
    <input type="text" name="title"><br><br>
    <label>Summary:</label><br>
    <textarea name="summary"></textarea><br><br>
    <label>Image:</label><br>
    <input type="file" name="image"><br><br>
    <button type="submit">Add News</button>
</form>

<hr>

<h2>All News</h2>
<?php foreach($news as $n): ?>
    <div class="news-item">
        <?php if($n['image'] && file_exists(__DIR__."/../images/".$n['image'])): ?>
            <img src="../images/<?php echo htmlspecialchars($n['image']); ?>" alt="News Image">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($n['title']); ?></h3>
        <p><?php echo htmlspecialchars($n['summary']); ?></p>

        <!-- Edit / Delete buttons -->
        <form method="POST" style="display:inline-block;">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
            <button class="btn btn-edit" type="submit">Edit</button>
        </form>
        <a class="btn btn-delete" href="?delete=<?php echo $n['id']; ?>" onclick="return confirm('Are you sure to delete?');">Delete</a>
    </div>
<?php endforeach; ?>
</body>
</html>
