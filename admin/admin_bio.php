<?php
include '../config.php';

// Handle Add Player Bio
if (isset($_POST['add_player_bio'])) {
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $current_club = $_POST['current_club'];
    $past_clubs = $_POST['past_clubs'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $image;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "<p style='color:red'>Error uploading file.</p>";
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO player_bios (full_name, age, address, country, current_club, past_clubs, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $full_name, $age, $address, $country, $current_club, $past_clubs, $image);
    if ($stmt->execute()) {
        header("Location: admin_bio.php");
        exit;
    } else {
        echo "<p style='color:red'>Error: " . $stmt->error . "</p>";
    }
}

// Handle Delete Player Bio
if (isset($_GET['delete_bio'])) {
    $id = $_GET['delete_bio'];
    $stmt = $conn->prepare("SELECT image FROM player_bios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $image_path = "../uploads/" . $row['image'];

    $stmt = $conn->prepare("DELETE FROM player_bios WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        header("Location: admin_bio.php");
        exit;
    } else {
        echo "<p style='color:red'>Error: " . $stmt->error . "</p>";
    }
}

// Handle Update Player Bio
if (isset($_POST['update_player_bio'])) {
    $id = $_POST['bio_id'];
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $current_club = $_POST['current_club'];
    $past_clubs = $_POST['past_clubs'];
    $current_image = $_POST['current_image'];
    $new_image = $current_image;

    if (!empty($_FILES['new_image']['name'])) {
        $new_image = time() . '_' . basename($_FILES['new_image']['name']);
        $target_dir = "../uploads/";
        $target_file = $target_dir . $new_image;
        
        if (move_uploaded_file($_FILES['new_image']['tmp_name'], $target_file)) {
            if (!empty($current_image) && file_exists($target_dir . $current_image)) {
                unlink($target_dir . $current_image);
            }
        } else {
            echo "<p style='color:red'>Error uploading new image.</p>";
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE player_bios SET full_name=?, age=?, address=?, country=?, current_club=?, past_clubs=?, image=? WHERE id=?");
    $stmt->bind_param("sisssssi", $full_name, $age, $address, $country, $current_club, $past_clubs, $new_image, $id);
    if ($stmt->execute()) {
        header("Location: admin_bio.php");
        exit;
    } else {
        echo "<p style='color:red'>Error updating player: " . $stmt->error . "</p>";
    }
}

$player_bios_result = $conn->query("SELECT * FROM player_bios ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Player Bios</title>
    <style>
        body { font-family: Poppins, sans-serif; background: #f4f6f9; padding: 20px; color: #333; }
        h2 { color: #0b74de; margin-bottom: 10px; border-bottom: 2px solid #0b74de; padding-bottom: 5px; }
        form { background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        input[type="text"], input[type="number"], textarea, input[type="file"], button { padding: 10px 12px; margin: 8px 0; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box; }
        button { background: #0b74de; color: #fff; cursor: pointer; border: none; font-weight: 600; transition: background 0.3s ease; }
        button:hover { background: #094a99; }
        .bio-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 20px; }
        .bio-card { background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); text-align: center; }
        .bio-card img { width: 100px; height: 130px; object-fit: cover; border-radius: 6px; border: 2px solid #0b74de; margin-bottom: 10px; }
        .bio-card p { margin: 5px 0; color: #555; }
        .bio-card .player-name { font-weight: bold; color: #0b74de; font-size: 1.2em; }
        .bio-actions { display: flex; justify-content: space-around; margin-top: 10px; }
        .bio-actions button, .bio-actions a { padding: 8px 12px; border-radius: 6px; text-decoration: none; font-weight: 500; }
        .delete-btn { background: #dc3545; color: white; border: none; }
        .delete-btn:hover { background: #c82333; }
        .edit-btn { background: #ffc107; color: #333; border: none; }
        .edit-btn:hover { background: #e0a800; }
        #edit-form-container { display: none; margin-top: 20px; border-top: 2px solid #ccc; padding-top: 20px; }
    </style>
</head>
<body>

<h2>Add New Player Biography</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="number" name="age" placeholder="Age" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="text" name="country" placeholder="Country" required>
    <input type="text" name="current_club" placeholder="Current Club" required>
    <textarea name="past_clubs" placeholder="Past Clubs (e.g., Club A, Club B)" rows="3"></textarea>
    <label>Player Image:</label>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add_player_bio">Add Player Bio</button>
</form>

<div id="edit-form-container">
    <h2>Edit Player Biography</h2>
    <form id="edit-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="update_player_bio">
        <input type="hidden" name="bio_id" id="edit-id">
        <input type="text" name="full_name" id="edit-full-name" placeholder="Full Name" required>
        <input type="number" name="age" id="edit-age" placeholder="Age" required>
        <input type="text" name="address" id="edit-address" placeholder="Address" required>
        <input type="text" name="country" id="edit-country" placeholder="Country" required>
        <input type="text" name="current_club" id="edit-current-club" placeholder="Current Club" required>
        <textarea name="past_clubs" id="edit-past-clubs" placeholder="Past Clubs" rows="3"></textarea>
        <input type="hidden" name="current_image" id="edit-current-image">
        <label>New Player Image (optional):</label>
        <input type="file" name="new_image" accept="image/*">
        <button type="submit">Update Player Bio</button>
    </form>
</div>

<h2>Existing Player Bios</h2>
<div class="bio-grid">
    <?php while ($row = $player_bios_result->fetch_assoc()): ?>
        <div class="bio-card">
            <?php if ($row['image'] != ''): ?>
                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="Player Image">
            <?php endif; ?>
            <p class="player-name"><?= htmlspecialchars($row['full_name']) ?></p>
            <p><?= htmlspecialchars($row['current_club']) ?></p>
            <div class="bio-actions">
                <button class="edit-btn" onclick="editBio(<?= $row['id'] ?>, '<?= htmlspecialchars($row['full_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['age'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['address'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['country'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['current_club'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['past_clubs'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['image'], ENT_QUOTES) ?>')">Edit</button>
                <a href="?delete_bio=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this player bio?');">Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
    function editBio(id, fullName, age, address, country, currentClub, pastClubs, image) {
        document.getElementById('edit-form-container').style.display = 'block';
        window.scrollTo({
            top: document.getElementById('edit-form-container').offsetTop - 20,
            behavior: 'smooth'
        });
        
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-full-name').value = fullName;
        document.getElementById('edit-age').value = age;
        document.getElementById('edit-address').value = address;
        document.getElementById('edit-country').value = country;
        document.getElementById('edit-current-club').value = currentClub;
        document.getElementById('edit-past-clubs').value = pastClubs;
        document.getElementById('edit-current-image').value = image;
    }
</script>

</body>
</html>