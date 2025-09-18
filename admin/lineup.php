<?php
include '../config.php';

// Handle Club Info Update
if (isset($_POST['update_club'])) {
    $club = $_POST['club_name'];
    $coach = $_POST['coach_name'];
    $captain = $_POST['captain_name'];

    $check_stmt = $conn->query("SELECT id FROM club_info LIMIT 1");
    if ($check_stmt->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE club_info SET club_name = ?, coach_name = ?, captain_name = ? WHERE id = (SELECT id FROM (SELECT id FROM club_info ORDER BY id DESC LIMIT 1) as t)");
    } else {
        $stmt = $conn->prepare("INSERT INTO club_info (club_name, coach_name, captain_name) VALUES (?, ?, ?)");
    }
    $stmt->bind_param("sss", $club, $coach, $captain);
    if ($stmt->execute()) {
        header('Location: lineup.php');
        exit;
    } else {
        echo "<p style='color:red'>Error updating club info: " . $stmt->error . "</p>";
    }
}

// Handle Add Player to Lineup
if (isset($_POST['add_player'])) {
    $image = time() . '_' . basename($_FILES['image']['name']);
    $target_dir = "../uploads/";
    $target_file = $target_dir . $image;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO lineup (image) VALUES (?)");
        $stmt->bind_param("s", $image);
        if ($stmt->execute()) {
            header("Location: lineup.php");
            exit;
        } else {
            echo "<p style='color:red'>Error: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color:red'>Error uploading file.</p>";
    }
}

// Handle Delete Player
if (isset($_GET['delete_player'])) {
    $id = $_GET['delete_player'];

    $stmt = $conn->prepare("SELECT image FROM lineup WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $image_path = "../uploads/" . $row['image'];

    $stmt = $conn->prepare("DELETE FROM lineup WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        header("Location: lineup.php");
        exit;
    } else {
        echo "<p style='color:red'>Error: " . $stmt->error . "</p>";
    }
}

// Fetch current club and lineup info
$club_info = $conn->query("SELECT * FROM club_info ORDER BY id DESC LIMIT 1")->fetch_assoc();
$lineup_result = $conn->query("SELECT * FROM lineup ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: Poppins, sans-serif; background: #f4f6f9; padding: 20px; }
        h2 { color: #0b74de; margin-bottom: 10px; }
        form { background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        .form-section { max-width: 500px; margin: 0 auto 30px; }
        input[type="text"], input[type="file"], button { padding: 8px 12px; margin: 5px 0; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box; }
        button { background: #0b74de; color: #fff; cursor: pointer; border: none; }
        button:hover { background: #094a99; }
        .lineup-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-top: 20px; }
        .lineup-card { background: #fff; padding: 10px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); text-align: center; }
        .lineup-card img { max-width: 100%; height: auto; border-radius: 6px; margin-bottom: 10px; }
        .lineup-card .delete-btn { background: #dc3545; color: white; text-decoration: none; padding: 8px 12px; border-radius: 6px; display: block; margin-top: 10px; }
        .delete-btn:hover { background: #c82333; }
        .card-image-wrapper { position: relative; }
        .card-image-wrapper:before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); border-radius: 6px; opacity: 0; transition: opacity 0.3s; }
        .card-image-wrapper:hover:before { opacity: 1; }
    </style>
</head>
<body>

<div class="form-section">
    <h2>Update Club Details</h2>
    <form method="post">
        <input type="text" name="club_name" placeholder="Club Name" value="<?= htmlspecialchars($club_info['club_name'] ?? '') ?>" required>
        <input type="text" name="coach_name" placeholder="Coach Name" value="<?= htmlspecialchars($club_info['coach_name'] ?? '') ?>" required>
        <input type="text" name="captain_name" placeholder="Captain Name" value="<?= htmlspecialchars($club_info['captain_name'] ?? '') ?>" required>
        <button type="submit" name="update_club">Save/Update Club Info</button>
    </form>
</div>

<div class="form-section">
    <h2>Add Player Image to Lineup</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Player Image:</label>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add_player">Add Image</button>
    </form>
</div>

<h2>Current Lineup Images</h2>
<div class="lineup-grid">
    <?php while ($row = $lineup_result->fetch_assoc()): ?>
        <div class="lineup-card">
            <div class="card-image-wrapper">
                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="Lineup Image">
            </div>
            <a href="?delete_player=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this image?');">Delete</a>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>