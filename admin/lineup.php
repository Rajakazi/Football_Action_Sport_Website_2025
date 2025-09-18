<?php
include '../config.php';

// Add Club Info
if(isset($_POST['add_club'])){
    $club = $_POST['club_name'];
    $coach = $_POST['coach_name'];
    $captain = $_POST['captain_name'];

    $stmt = $conn->prepare("INSERT INTO club_info (club_name, coach_name, captain_name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $club, $coach, $captain);
    $stmt->execute();
    header('Location: lineup.php');
    exit;
}

// Add Lineup Image Only
if(isset($_POST['add_lineup'])){
    if(!empty($_FILES['image']['name'])){
        $image = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);

        // Insert only image; other fields NULL or default
        $stmt = $conn->prepare("INSERT INTO lineup (player_name, position, number, image) VALUES (?, ?, ?, ?)");
        $default_name = NULL;
        $default_position = NULL;
        $default_number = NULL;
        $stmt->bind_param("ssis", $default_name, $default_position, $default_number, $image);
        $stmt->execute();
        header("Location: lineup.php");
        exit;
    } else {
        echo "<p style='color:red'>Please upload an image!</p>";
    }
}

// Fetch Lineups
$club_result = $conn->query("SELECT * FROM club_info ORDER BY id DESC LIMIT 1");
$lineup_result = $conn->query("SELECT * FROM lineup ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Line-Up</title>
    <style>
        body{font-family:Poppins,sans-serif; background:#f4f6f9; margin:0; padding:20px;}
        h2{color:#0b74de; margin-bottom:10px;}
        form{background:#fff; padding:15px; border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.1); margin-bottom:20px;}
        input, textarea, button{padding:8px 12px; margin:5px 0; border-radius:6px; border:1px solid #ccc; width:100%;}
        button{background:#0b74de; color:#fff; cursor:pointer; border:none;}
        button:hover{background:#094a99;}
        table{width:100%; border-collapse:collapse; margin-top:20px;}
        table, th, td{border:1px solid #ccc;}
        th, td{padding:10px; text-align:left;}
        img{max-width:200px; border-radius:6px; margin-bottom:10px;}
        .lineup-grid{display:flex; gap:20px; flex-wrap:wrap;}
    </style>
</head>
<body>

<h2>Club Info</h2>
<form method="post">
    <input type="text" name="club_name" placeholder="Club Name" required>
    <input type="text" name="coach_name" placeholder="Coach Name" required>
    <input type="text" name="captain_name" placeholder="Captain Name" required>
    <button type="submit" name="add_club">Save Club Info</button>
</form>

<h2>Add Lineup Image</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add_lineup">Upload</button>
</form>

<h2>Lineup Images</h2>
<div class="lineup-grid">
    <?php while($row = $lineup_result->fetch_assoc()): ?>
        <div>
            <img src="../uploads/<?php echo $row['image']; ?>" alt="Lineup Image">
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
