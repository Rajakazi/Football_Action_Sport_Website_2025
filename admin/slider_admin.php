<?php
include '../config.php';

// Add Slider Image
if(isset($_POST['add_slider'])){
    if(!empty($_FILES['image']['name'])){
        $image = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);

        $stmt = $conn->prepare("INSERT INTO slider (image) VALUES (?)");
        $stmt->bind_param("s", $image);
        $stmt->execute();
        header("Location: slider_admin.php");
        exit;
    }
}

// Delete Slider
if(isset($_GET['delete_id'])){
    $id = (int)$_GET['delete_id'];
    $img = $conn->query("SELECT image FROM slider WHERE id=$id")->fetch_assoc()['image'];
    if($img && file_exists("../uploads/$img")) unlink("../uploads/$img");
    $conn->query("DELETE FROM slider WHERE id=$id");
    header("Location: slider_admin.php");
    exit;
}

// Fetch existing sliders
$slider_result = $conn->query("SELECT * FROM slider ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin - Slider Images</title>
<style>
body{font-family:Poppins,sans-serif; background:#f4f6f9; padding:20px;}
h2{color:#0b74de;}
form{background:#fff; padding:15px; border-radius:10px; margin-bottom:20px; box-shadow:0 6px 20px rgba(0,0,0,0.1);}
input, button{padding:8px 12px; margin:5px 0; width:100%; border-radius:6px; border:1px solid #ccc;}
button{background:#0b74de; color:#fff; border:none; cursor:pointer;}
button:hover{background:#094a99;}
img{max-width:150px; margin-top:10px; border-radius:6px;}
.slider-card{background:#fff; padding:15px; border-radius:10px; margin-bottom:15px; box-shadow:0 6px 20px rgba(0,0,0,0.1);}
a.delete{color:red; text-decoration:none; margin-top:10px; display:inline-block;}
a.delete:hover{text-decoration:underline;}
</style>
</head>
<body>

<h2>Upload Slider Image</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add_slider">Upload Image</button>
</form>

<h2>Existing Slider Images</h2>
<?php while($row = $slider_result->fetch_assoc()): ?>
    <div class="slider-card">
        <img src="../uploads/<?=htmlspecialchars($row['image'])?>" alt="Slider Image">
        <a href="?delete_id=<?=$row['id']?>" class="delete" onclick="return confirm('Are you sure to delete?')">Delete</a>
    </div>
<?php endwhile; ?>

</body>
</html>
