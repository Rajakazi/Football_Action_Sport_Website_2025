<?php
include 'config.php';
$fixtures = $conn->query("SELECT * FROM fixtures ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Fixtures</title>
    <style>
        /* General Styles */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }

        /* Header (Placeholder for your site's header) */
        header {
            background: linear-gradient(135deg, #0b74de, #074a99);
            padding: 15px 30px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 2rem;
            text-align: center;
            padding: 20px;
        }
        
        /* Fixture Grid Container */
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        /* Fixture Card Styling */
        .fixture-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .fixture-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
        }

        .fixture-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .card-content {
            padding: 25px;
        }
        .card-content h3 {
            margin-top: 0;
            font-size: 1.4rem;
            color: #0b74de;
            font-weight: 600;
        }
        .card-content p {
            margin: 0;
            font-size: 1rem;
            color: #555;
        }
    </style>
</head>
<body>

<header>
    <div class="header">
        <h1>Football Fixtures</h1>
    </div>
</header>

<div class="container">
    <?php if ($fixtures->num_rows > 0): ?>
        <?php while($row = $fixtures->fetch_assoc()): ?>
            <div class="fixture-card">
                <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="fixture-image" alt="<?= htmlspecialchars($row['title']) ?>">
                <?php endif; ?>
                <div class="card-content">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; width: 100%;">No fixtures found.</p>
    <?php endif; ?>
</div>

</body>
</html>