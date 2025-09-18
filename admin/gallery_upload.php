<?php
require_once "../config.php";

// Handle Upload
if(isset($_POST['upload'])){
    $category = $_POST['category'];
    $images = $_FILES['images'];

    $uploadDir = "../uploads/gallery/";
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    foreach($images['name'] as $key=>$name){
        if($images['error'][$key] === 0){
            $fileName = time().'_'.$name;
            move_uploaded_file($images['tmp_name'][$key], $uploadDir.$fileName);

            $stmt = $conn->prepare("INSERT INTO gallery (category,image) VALUES (?,?)");
            $stmt->bind_param("ss",$category,$fileName);
            $stmt->execute();
        }
    }
}

// Handle Delete
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $res = $conn->query("SELECT image FROM gallery WHERE id=$id");
    $row = $res->fetch_assoc();
    if($row && file_exists("../uploads/gallery/".$row['image'])){
        unlink("../uploads/gallery/".$row['image']);
    }
    $conn->query("DELETE FROM gallery WHERE id=$id");
    header("Location: upload.php");
    exit;
}

// Fetch Images (with search)
$search = $_GET['search'] ?? '';
if($search){
    $stmt = $conn->prepare("SELECT * FROM gallery WHERE category LIKE ? ORDER BY uploaded_at DESC");
    $like = "%$search%";
    $stmt->bind_param("s",$like);
    $stmt->execute();
    $gallery = $stmt->get_result();
} else {
    $gallery = $conn->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Gallery - Admin</title>
<style>
body{font-family:Arial;background:#f4f6f9;padding:20px;}
form{background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 20px rgba(0,0,0,0.1);margin-bottom:20px;}
input,select,button{padding:10px;margin:5px 0;border-radius:6px;border:1px solid #ccc;}
button{background:#28a745;color:#fff;border:none;cursor:pointer;}
button:hover{background:#218838;}
.table{width:100%;border-collapse:collapse;margin-top:20px;}
.table th,.table td{border:1px solid #ddd;padding:10px;text-align:center;}
.table img{max-width:100px;border-radius:6px;}
.delete-btn{background:#dc3545;color:#fff;padding:6px 12px;text-decoration:none;border-radius:5px;}
.delete-btn:hover{background:#b02a37;}
.search-box{margin-bottom:20px;}
</style>
</head>
<body>

<h2>Upload Football Images</h2>
<form method="post" enctype="multipart/form-data">
    <select name="category" required>
        <option value="">-- Select Category --</option>
        <option value="Players">Leo Messi Nation And Club</option>
        <option value="Celebrations">Win Celebrations</option>
        <option value="Training">Club Image</option>
        <option value="neymar image 4k image">Neymar</option>
        <option value="Others">Ronaldo image 4k 2k</option>
        <option value="Others">Raphihna image</option>
        <option value="Others">Lamine Yamal image</option>
        <option value="Others">Nation Image image</option>
        <option value="Others">Champion Luague image</option>
        <option value="Others">Lewandow image</option>
    </select>
    <input type="file" name="images[]" multiple required>
    <button type="submit" name="upload">Upload</button>
</form>

<div class="search-box">
    <form method="get">
        <input type="text" name="search" placeholder="Search by category..." value="<?=htmlspecialchars($search)?>">
        <button type="submit">Search</button>
    </form>
</div>

<h2>Existing Images</h2>
<table class="table">
<tr><th>ID</th><th>Category</th><th>Image</th><th>Action</th></tr>
<?php while($row=$gallery->fetch_assoc()): ?>
<tr>
    <td><?= $row['id']?></td>
    <td><?= htmlspecialchars($row['category'])?></td>
    <td><img src="../uploads/gallery/<?=htmlspecialchars($row['image'])?>" alt=""></td>
    <td><a class="delete-btn" href="?delete=<?=$row['id']?>" onclick="return confirm('Delete this image?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
