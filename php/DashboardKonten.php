<?php 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' ) {
    echo "Anda tidak memiliki akses ke halaman ini";
    exit;
}

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

            /* Center the text in the mobile section */
            .mobile p {
                text-align: center;
            }

            /* Remove padding and margin from mobile class */
            .mobile {
                padding-top: 0 !important;
                margin-top: 0 !important;
                padding-bottom: 0 !important;
                color: red !important;
            }

            /* Adjust the column width in the row */
            .mobile .col-6 {
                width: 100%;
                padding: 10px;
            }

            /* Adjust the styles of the tambah button */
            .mobile .tambah {
                text-align: center;
            }

            /* Adjust the styles of the cari form */
            .mobile .cari {
                text-align: center;
                margin-top: 10px;
            }

            /* Adjust the styles of the table */
            .table-responsive {
                overflow-x: auto;
            }
        }

        /* CSS for large screens */
        @media screen and (min-width: 601px) {
            /* Adjust the desktop styles here */

            /* Add margin to the admin container */
            .admin-container {
                margin-top: 30px;
            }

            /* Adjust the padding of the container */
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
    <?php include('../NavigationBar/navDashBoard.php'); ?>
    <section class="admin" style="margin-top:90px !important">
        <div class="container admin-container py-2">
            <div class="row">
                <div class="col-12">
                    <!-- Form Pencarian -->
                    <div class="mb-3 cari">
                        <input type="text" id="searchKeywords" class="form-control" placeholder="Cari...">
                    </div>
                    <!-- Tabel untuk Peserta -->
                    <div class="table-responsive">
                    <table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">ID Content</th>
            <th scope="col">Nama Turnamen</th>
            <th scope="col">Gambar</th>
            <th scope="col">Deskripsi</th>
            <th scope="col">Tanggal Upload</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody id="contentTableBody">
    <?php
$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT c.id_content, c.id_turnamen, c.image_path, c.description, c.upload_date, t.nama_turnamen 
          FROM content c 
          JOIN turnamen t ON c.id_turnamen = t.id_turnamen";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id_content']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_turnamen']) . "</td>"; // Tampilkan nama turnamen
    echo "<td><img src='../assets/img/" . htmlspecialchars($row['image_path']) . "' alt='Gambar Konten' style='max-width: 100px;'></td>";
    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
    echo "<td>" . htmlspecialchars($row['upload_date']) . "</td>";
    echo "<td>
            <a href='editContent.php?id_content=" . htmlspecialchars($row['id_content']) . "' class='btn btn-primary btn-sm'>Edit</a>
            <a href='hapuskonten.php?id_content=" . htmlspecialchars($row['id_content']) . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Anda yakin ingin menghapus konten ini?');\">Hapus</a>
          </td>";
    echo "</tr>";
}
mysqli_close($conn);
?>

    </tbody>
</table>

                    </div>
                    <!-- Tombol untuk menambah peserta -->
                    <div class="text-end mt-3">
                        <button class="btn btn-success btn-metro" onclick="window.location.href='tambahKonten.php'">Tambah Konten</button>
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
        $('#searchKeywords').on('input', function() {
            var keywords = $(this).val();

            $.ajax({
                url: '../ajax/ajax_carikonten.php', // File PHP untuk menangani pencarian
                type: 'GET',
                data: { keywords: keywords },
                success: function(response) {
                    $('#contentTableBody').html(response);
                }
            });
        });
    });
  </script>
</body>
</html>
