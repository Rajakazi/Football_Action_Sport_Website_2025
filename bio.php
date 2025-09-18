<?php
include 'config.php';
$player_bios = $conn->query("SELECT * FROM player_bios ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Player Bios</title>
    <style>
        body { font-family: Poppins, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        header { background: linear-gradient(135deg, #0b74de, #074a99); padding: 15px 30px; color: #fff; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        header .logo img { height: 50px; width: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; }
        .navbar { display: flex; gap: 15px; flex-wrap: wrap; }
        .navbar a { color: #fff; text-decoration: none; padding: 8px 15px; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; }
        .navbar a:hover { background: #ffdd57; color: #074a99; }
        
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 15px;
        }
        
        .bio-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            gap: 30px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        /* Image and details layout */
        .bio-image-container {
            flex: 0 0 150px;
            text-align: center;
        }
        .bio-image {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border: 3px solid #0b74de;
            border-radius: 5px;
        }
        .bio-form {
            flex: 1;
        }
        .bio-form .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }
        .bio-form .form-group label {
            font-weight: bold;
            color: #0b74de;
            flex-basis: 150px;
        }
        .bio-form .form-group p {
            margin: 0;
            flex: 1;
            color: #555;
        }
        
        h2 {
            color: #0b74de;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
        }

        /* Separator line */
        .bio-section:not(:last-child) {
            border-bottom: 2px solid #ddd;
        }
        
        @media (min-width: 768px) {
            .bio-section {
                flex-direction: row;
            }
            .bio-image-container {
                order: 2;
            }
            .bio-form {
                order: 1;
            }
        }
        
        @media (max-width: 767px) {
            .bio-section {
                flex-direction: column;
                align-items: center;
            }
            .bio-image-container {
                width: 100%;
            }
            .bio-form .form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            .bio-form .form-group label {
                margin-bottom: 5px;
                flex-basis: auto;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="img/default_logo.png" alt="Club Logo">
    </div>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="lineup.php">Line-Up</a>
        <a href="bio.php">Player Bios</a>
        <a href="contact.php">Contact</a>
    </div>
</header>

<div class="container">
    <?php while ($row = $player_bios->fetch_assoc()): ?>
    <div class="bio-section">
        <h2><?= htmlspecialchars($row['full_name']) ?></h2>
        
        <div class="bio-form">
            <div class="form-group">
                <label>Age:</label>
                <p><?= htmlspecialchars($row['age']) ?></p>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <p><?= htmlspecialchars($row['address']) ?></p>
            </div>
            <div class="form-group">
                <label>Country:</label>
                <p><?= htmlspecialchars($row['country']) ?></p>
            </div>
            <div class="form-group">
                <label>Current Club:</label>
                <p><?= htmlspecialchars($row['current_club']) ?></p>
            </div>
            <div class="form-group">
                <label>Past Clubs:</label>
                <p><?= nl2br(htmlspecialchars($row['past_clubs'])) ?></p>
            </div>
        </div>
        
        <div class="bio-image-container">
            <?php if (!empty($row['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="bio-image" alt="<?= htmlspecialchars($row['full_name']) ?>">
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>