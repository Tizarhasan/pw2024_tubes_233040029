<?php
// Menghubungkan ke database
$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query untuk mengambil data tim dan pemain
$queryTeam = "SELECT * FROM tim";
$resultTeam = mysqli_query($conn, $queryTeam);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim dan Pemain</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Daftar Tim dan Pemain</h2>
        <?php while ($rowTeam = mysqli_fetch_assoc($resultTeam)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <?php echo $rowTeam['nama_tim']; ?>
                </div>
                <div class="card-body">
                    <ul>
                        <?php
                        // Query untuk mengambil pemain berdasarkan id_tim
                        $idTim = $rowTeam['id_tim'];
                        $queryPlayers = "SELECT nickName FROM peserta WHERE id_tim = $idTim";
                        $resultPlayers = mysqli_query($conn, $queryPlayers);

                        while ($rowPlayer = mysqli_fetch_assoc($resultPlayers)): ?>
                            <li><?php echo $rowPlayer['nickName']; ?></li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

<?php
// Menutup koneksi database
mysqli_close($conn);
?>
