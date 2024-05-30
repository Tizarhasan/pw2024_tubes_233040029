
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' ) {
    echo "Anda tidak memiliki akses ke halaman ini";
    exit;
}

require 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
        /* CSS for small screens */
        @media screen and (max-width: 600px) {
            /* Adjust the mobile styles here */
            .mobile p {
                text-align: center;
            }
            .mobile {
                padding-top: 0 !important;
                margin-top: 0 !important;
                padding-bottom: 0 !important;
                color: red !important;
            }
            .mobile .col-6 {
                width: 100%;
                padding: 10px;
            }
            .mobile .tambah {
                text-align: center;
            }
            .mobile .cari {
                text-align: center;
                margin-top: 10px;
            }
            .table-responsive {
                overflow-x: auto;
            }
        }

        /* CSS for large screens */
        @media screen and (min-width: 601px) {
            .admin-container {
                margin-top: 30px;
            }
            .container.py-2 {
                padding-top: 60px;
                padding-bottom: 30px;
            }
        }

        .btn-metro {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            background-color: #0078d7;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            line-height: 1;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .btn-metro:hover {
            background-color: #005a9e;
        }

        .btn-metro:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 120, 215, 0.5);
        }

        @media print {
            .no-print,
            .no-print * {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <?php include('../NavigationBar/navDashBoard.php') ?>
    <section class="admin" style="margin-top:90px !important">
    <div class="container admin-container py-2">
        <div class="row">
            <div class="col-12">
            <div class="mb-3 cari">
                        <input type="text" id="searchKeywords" class="form-control" placeholder="Cari...">
                    </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Nama Turnamen</th>
                                <th scope="col">Tanggal Mulai</th> <!-- Tambah kolom untuk tanggal mulai -->
                                <th scope="col">Nama Tim</th>
                                <th scope="col">Aksi</th> <!-- Tambah kolom untuk aksi -->
                            </tr>
                        </thead>
                        <tbody id="TurnamenTableBody" >
                        <?php
                        // Koneksi ke database
                        $conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");

                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        // Query untuk mengambil nama turnamen, tanggal mulai, dan nama tim dari tabel turnamen_tim
                        $query = "SELECT turnamen_tim.id_turnamen_tim, turnamen.id_turnamen, turnamen.nama_turnamen, turnamen.tanggal_mulai, tim.id_tim, tim.nama_tim 
                                FROM turnamen_tim 
                                JOIN turnamen ON turnamen_tim.id_turnamen = turnamen.id_turnamen 
                                JOIN tim ON turnamen_tim.id_tim = tim.id_tim";

                        $result = mysqli_query($conn, $query);

                        if (!$result) {
                            die("Query failed: " . mysqli_error($conn));
                        }

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nama_turnamen']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tanggal_mulai']) . "</td>"; // Tampilkan tanggal mulai
                            echo "<td>" . htmlspecialchars($row['nama_tim']) . "</td>";
                            // Tambah tombol edit dan hapus dengan link ke halaman editTurnamen.php dan hapusturnamen.php
                            echo "<td>
                                    <a href='editTurnamen.php?id=" . htmlspecialchars($row['id_turnamen_tim']) . "' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='hapusturnamen.php?id=" . htmlspecialchars($row['id_turnamen_tim']) . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Anda yakin ingin menghapus turnamen ini?');\">Hapus</a>
                                </td>";
                            echo "</tr>";
                        }
                        mysqli_close($conn);
                        ?>

                        </tbody>
                    </table>
                </div>
                <!-- Tombol untuk menambah turnamen -->
                <div class="text
                <div class="text-end mt-3">
                    <button class="btn btn-success btn-metro" onclick="window.location.href='tambahEventTurnamen.php '">Tambah Turnamen</button>
                </div>
                <div class="text-end mt-3">
                    <button class="btn btn-success btn-metro" onclick="window.location.href='tambahturnamen.php'">Tambah Nama Turnamen</button>
                </div>
            </div>
        </div>
    </div>
    </section>

    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
    crossorigin="anonymous"
  ></script>
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
         <script>
        $(document).ready(function() {
            $('#searchKeywords').on('keyup', function() {
                let keywords = $(this).val().split(' ');
                $.ajax({
                    url: '../ajax/ajax_cariTurnamen.php',
                    type: 'GET',
                    data: { keywords: keywords },
                    success: function(data) {
                        $('#TurnamenTableBody').html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>