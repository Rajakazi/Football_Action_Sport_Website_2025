<?php
require_once "config.php";

// Get all FIFA rankings
$rankings = $conn->query("SELECT * FROM fifa_ranking ORDER BY ranking ASC");

// Prepare data for chart
$ranking_labels = [];
$points_data = [];
$all_rankings = []; // all data for table pagination

while($row = $rankings->fetch_assoc()){
    $ranking_labels[] = $row['country_name'];
    $points_data[] = $row['points'];
    $all_rankings[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FIFA Rankings</title>

    <style>
  /* ================= DESKTOP / LAPTOP STYLES ================= */
@media (min-width: 769px) {

    table, #chart-container {
        width: 100%;          /* desktop half screen */
        margin: 0 auto 30px auto; /* center horizontally */
        border-collapse: collapse;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    th, td{
        padding: 14px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th{
        background: linear-gradient(90deg, #0065bd, #004a8f);
        color: #fff;
        position: sticky;
        top: 0;
    }

    tr:hover{
        background: linear-gradient(90deg, #e0f2ff, #cce5ff);
    }

    .country-flag{
        width: 40px;
        border-radius: 6px;
    }

    #showMoreBtn{
        width: 150px;
        display: block;
        margin: 20px auto;
        padding: 12px 25px;
        font-size: 16px;
        background: linear-gradient(90deg,#0065bd,#004a8f);
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #showMoreBtn:hover{
        background: linear-gradient(90deg,#004a8f,#002f60);
    }

    #chart-container{
        width: 100%;
        margin: 40px auto;
    }
}

/* ================= MOBILE STYLES ================= */
@media (max-width: 768px) {
    body{
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f7fa;
        padding: 10px;
    }

    h2{
        font-size: 18px;
        margin-bottom: 15px;
    }

    table, #chart-container {
        width: 100% !important;    /* mobile full screen */
        margin: 0 auto 20px auto;
        font-size: 12px;
    }

    th, td{
        padding: 8px;
        text-align: center;
    }

    .country-flag{
        width: 25px;
    }

    #showMoreBtn{
        width: 90% !important;     /* almost full screen button */
        margin: 20px auto;
        padding: 10px;
        font-size: 14px;
    }
}

    </style>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>FIFA World Rankings</h2>

<!-- Rankings Table -->
<table id="rankingTable">
    <tr>
        <th>Rank</th>
        <th>Country</th>
        <th>Flag</th>
        <th>Points</th>
        <th>MP</th>
        <th>W</th>
        <th>D</th>
        <th>L</th>
    </tr>
</table>

<button id="showMoreBtn">Show More</button>

<!-- Chart -->
<h2>Points vs Countries</h2>
<div id="chart-container">
    <canvas id="fifaChart"></canvas>
</div>

<script>
    // All ranking data from PHP
    const allRankings = <?php echo json_encode($all_rankings); ?>;
    const table = document.getElementById('rankingTable');
    let showCount = 20; // show top 20 first

    // Function to render rows
    function renderRows(count){
        table.innerHTML = `
        <tr>
            <th>Rank</th>
            <th>Country</th>
            <th>Flag</th>
            <th>Points</th>
            <th>MP</th>
            <th>W</th>
            <th>D</th>
            <th>L</th>
        </tr>`;
        for(let i=0; i<count && i<allRankings.length; i++){
            const row = allRankings[i];
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.ranking}</td>
                <td>${row.country_name}</td>
                <td><img src="uploads/${row.country_flag}" class="country-flag"></td>
                <td>${row.points}</td>
                <td>${row.matches_played}</td>
                <td>${row.wins}</td>
                <td>${row.draws}</td>
                <td>${row.losses}</td>
            `;
            table.appendChild(tr);
        }
    }

    // Initial render top 20
    renderRows(showCount);

    // Show more button
    const btn = document.getElementById('showMoreBtn');
    btn.addEventListener('click', ()=>{
        showCount = allRankings.length;
        renderRows(showCount);
        btn.style.display = 'none'; // hide button after full show
    });

    // Chart.js
    const ctx = document.getElementById('fifaChart').getContext('2d');
    const fifaChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($ranking_labels); ?>,
            datasets: [{
                label: 'Points',
                data: <?php echo json_encode($points_data); ?>,
                backgroundColor: function(context) {
                    const value = context.dataset.data[context.dataIndex];
                    return value > 1500 ? '#ff6b6b' : '#007bff';
                },
                borderRadius: 6,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins:{
                legend:{display:false},
                tooltip:{
                    callbacks:{
                        label:function(context){
                            return context.raw + " Points";
                        }
                    }
                }
            },
            scales: {
                x: {beginAtZero: true, grid: {color:'rgba(0,0,0,0.05)'}},
                y: {grid: {display:false}}
            }
        }
    });
</script>

</body>
</html>
