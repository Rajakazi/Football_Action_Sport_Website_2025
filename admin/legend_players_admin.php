<?php
require_once "../config.php";

// Handle add new player
if(isset($_POST['add_player'])){
    $name = $_POST['name'] ?? '';
    $history = $_POST['history'] ?? '';
    $image = '';

    if(!empty($_FILES['image']['name'])){
        $image = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);

        $stmt = $conn->prepare("INSERT INTO legend_players (name, image, history) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $image, $history);
        $stmt->execute();
    }
    header("Location: legend_players_admin.php");
    exit;
}

// Handle delete player
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM legend_players WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: legend_players_admin.php");
    exit;
}

// Fetch all players
$players = $conn->query("SELECT * FROM legend_players ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Legend Players</title>
    <style>
        body{ font-family: Arial, sans-serif; margin:40px; background:#f4f6f9; }
        h2{ color:#0b74de; }
        form{ background:#fff; padding:20px; border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.1); margin-bottom:30px; }
        input, textarea, button{ width:100%; padding:10px; margin:5px 0; border-radius:6px; border:1px solid #ccc; }
        button{ background:#0b74de; color:#fff; border:none; cursor:pointer; }
        button:hover{ background:#094a99; }
        .player-box{ background:#fff; padding:15px; border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.1); margin-bottom:15px; display:flex; align-items:center; }
        .player-box img{ max-width:100px; border-radius:10px; margin-right:15px; }
        .player-info{ flex:1; }
        .delete-btn{ background:#dc3545; margin-left:10px; }
        .delete-btn:hover{ background:#b02a37; }
    </style>
</head>
<body>

<h2>Add New Legend Player</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Player Name" required>
    <textarea name="history" placeholder="Player History" required></textarea>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add_player">Add Player</button>
</form>

<h2>Existing Players</h2>
<?php while($row = $players->fetch_assoc()): ?>
    <div class="player-box">
        <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <div class="player-info">
            <strong><?= htmlspecialchars($row['name']) ?></strong>
            <p><?= nl2br(htmlspecialchars($row['history'])) ?></p>
        </div>
        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this player?');"><button class="delete-btn">Delete</button></a>
    </div>
<?php endwhile; ?>
<a href="dashboard.php">Home</a>
</body>
</html>
