<?php
include 'functions.php';

// Ambil parameter sorting dari request
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'asc';

// Perbarui query untuk mengambil tanggal mulai turnamen dengan sorting
$sql = "SELECT DISTINCT t.id_turnamen, t.nama_turnamen, t.tanggal_mulai 
        FROM turnamen_tim tt
        JOIN turnamen t ON tt.id_turnamen = t.id_turnamen 
        ORDER BY t.tanggal_mulai $sortOrder";
$result = mysqli_query($conn, $sql);

$tournaments = [];
$currentDate = new DateTime();
while ($row = mysqli_fetch_assoc($result)) {
    $tournamentID = $row['id_turnamen'];
    $tournamentName = $row['nama_turnamen'];
    $tournamentStartDate = new DateTime($row['tanggal_mulai']); // Ambil tanggal mulai

    $dateDiff = $currentDate->diff($tournamentStartDate);
    $daysDiff = (int)$dateDiff->format('%r%a');

    $keterangan = ($daysDiff > 30) ? "Yang akan datang" : "Yang sudah ada";

    $sqlTeams = "SELECT id_tim FROM turnamen_tim WHERE id_turnamen = $tournamentID";
    $resultTeams = mysqli_query($conn, $sqlTeams);
    $teamIDs = [];
    while ($rowTeam = mysqli_fetch_assoc($resultTeams)) {
        $teamIDs[] = $rowTeam['id_tim'];
    }

    $teams = ambilNamaTim($conn, $teamIDs);
    $bracket = generateBracket($teams);

    $tournaments[] = [
        'id' => $tournamentID,
        'name' => $tournamentName,
        'start_date' => $tournamentStartDate->format('Y-m-d'),
        'keterangan' => $keterangan,
        'bracket' => $bracket,
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Bracket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
        .team-card {
            min-height: 100px; /* Reduced height */
        }

        .team-logo {
            max-width: 100%; 
            height: auto; 
        }

        .bracket-section {
            border: 1px solid #000; /* Adjust border style and color as needed */
            padding: 10px; /* Reduced padding */
            margin-top: 10px; /* Reduced margin */
        }

        .bracket-section .row {
            margin-bottom: 10px; /* Reduced margin between rows */
        }

        .team-name {
            font-size: 14px; /* Reduced font size */
        }

        .vs-text {
            font-size: 40px; /* Adjusted font size for vs text */
        }
    </style>
</head>

<body>
    
    <section class="nav mb-3">
        <?php include '../NavigationBar/nav1.php'; ?>
    </section>
    
    <div class="container-md py-2" style="margin-top:7%; ">
        <!-- Display "Tournament List" heading only once -->
        <section class="tournament-list-section">
            <div class="container mt-3 text-center">
                <h2>Tournament List</h2>
                
                <!-- Dropdown for sorting -->
                <form method="GET" class="mb-3">
                    <label for="sort">Sort by Start Date:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="asc" <?= $sortOrder == 'asc' ? 'selected' : '' ?>>Oldest First</option>
                        <option value="desc" <?= $sortOrder == 'desc' ? 'selected' : '' ?>>Newest First</option>
                    </select>
                </form>
            </div>
        </section>

        <?php foreach ($tournaments as $tournament) : ?>
            <section class="bracket-section">
                <div class="container mt-3 text-center">
                    <div class="row justify-content-center">
                        <h3>Tournament Name: <?= $tournament['name'] ?></h3>
                        <p>Start Date: <?= date("F j, Y", strtotime($tournament['start_date'])) ?></p> <!-- Tampilkan tanggal mulai -->
                        <p>Status: <?= $tournament['keterangan'] ?></p> <!-- Tampilkan keterangan -->
                    </div>

                    <?php foreach ($tournament['bracket'] as $match) : 
                        $team1 = $match[0] ?? null;
                        $team2 = $match[1] ?? null;
                    ?>
                        <div class='row py-1'>
                            <div class='col-sm-4 py-1'>
                                <section>
                                    <div class='card team-card' style='background-color: #f0f0f0; border: 1px solid #ccc; width: 75%; margin: 0 auto;'>
                                        <?php if ($team1) : ?>
                                            <div class='card-body text-center' style='padding: 5px;'> <!-- Reduced padding -->
                                                <img src='../assets/img/<?= $team1['logo'] ?>' alt='<?= $team1['nama_tim'] ?>' class='team-logo'>
                                                <p class='team-name'><?= $team1['nama_tim'] ?? '' ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                            </div>

                            <?php if ($team1 && $team2) : ?>
                                <div class='col-md-4 py-1 d-flex align-items-center justify-content-center'>
                                    <section>
                                        <div class='card vs-card' style='background-color: #f0f0f0; border: 1px solid #ccc; width: 100%; height:100%; margin: 0 auto;'>
                                            <div class='card-body text-center p-0 d-flex align-items-center justify-content-center'>
                                                <p class='vs-text m-0'>vs</p>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class='col-md-4 py-1'>
                                    <section>
                                        <div class='card team-card' style='background-color: #f0f0f0; border: 1px solid #ccc; width: 75%; margin: 0 auto;'>
                                            <?php if ($team2) : ?>
                                                <div class='card-body text-center' style='padding: 5px;'> <!-- Reduced padding -->
                                                    <img src='../assets/img/<?= $team2['logo'] ?>' alt='<?= $team2['nama_tim'] ?>' class='team-logo'>
                                                    <p class='team-name'><?= $team2['nama_tim'] ?? '' ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                </div>
                            <?php elseif ($team2) : ?>
                                <div class='col-md-8 offset-md-4 py-1'>
                                    <section>
                                        <div class='card team-card'>
                                            <div class='card-body text-center' style='padding: 5px;'> <!-- Reduced padding -->
                                                <img src='../assets/img/<?= $team2['logo'] ?>' alt='<?= $team2['nama_tim'] ?>' class='team-logo'>
                                                <p class='team-name'><?= $team2['nama_tim'] ?? '' ?></p>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
    crossorigin="anonymous"
  ></script>
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

</body>

</html>
