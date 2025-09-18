<?php
include '../config.php';

// Add new advertisement image
if(isset($_POST['add_adv'])){
    $image = '';
    if(!empty($_FILES['image']['name'])){
        $image = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);
        $stmt = $conn->prepare("INSERT INTO adv_slider (image) VALUES (?)");
        $stmt->bind_param("s", $image);
        $stmt->execute();
        header("Location: adv_slider_admin.php");
        exit;
    } else {
        echo "<p style='color:red'>Please upload an image!</p>";
    }
}

// Delete advertisement image
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM adv_slider WHERE id=$id");
    header("Location: adv_slider_admin.php");
    exit;
}

// Fetch all advertisement images
$adv_result = $conn->query("SELECT * FROM adv_slider ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Advertisement Slider</title>
    <style>
        body{font-family:Poppins,sans-serif; padding:20px; background:#f4f6f9;}
        h2{color:#0b74de;}
        form{background:#fff; padding:15px; border-radius:10px; margin-bottom:20px; box-shadow:0 6px 20px rgba(0,0,0,0.1);}
        input, button{padding:8px 12px; margin:5px 0; width:100%; border-radius:6px; border:1px solid #ccc;}
        button{background:#0b74de; color:#fff; border:none; cursor:pointer;}
        button:hover{background:#094a99;}
        img{max-width:200px; margin-top:10px; border-radius:8px;}
        .adv-box{margin-bottom:15px;}
        .adv-box a{color:red; text-decoration:none; font-weight:bold;}
    </style>
</head>
<body>

<h2>Add Advertisement</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add_adv">Add Advertisement</button>
</form>

<h2>Existing Advertisement Images</h2>
<?php while($row=$adv_result->fetch_assoc()): ?>
    <div class="adv-box">
        <img src="../uploads/<?=htmlspecialchars($row['image'])?>" alt="">
        <br>
        <a href="adv_slider_admin.php?delete=<?=$row['id']?>">Delete</a>
    </div>
<?php endwhile; ?>

</body>
</html>
