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
                                    <th scope="col">ID Peserta</th>
                                    <th scope="col">ID InGame</th>
                                    <th scope="col">NickName</th>
                                    <th scope="col">ID Role</th>
                                    <th scope="col">Nama Tim</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="pesertaTableBody">
                                <?php
                                include 'functions.php';

                                $sql = "SELECT peserta.*, roleingame.nama_role, tim.nama_tim 
                                        FROM peserta 
                                        LEFT JOIN roleingame ON peserta.id_role = roleingame.id_role
                                        LEFT JOIN tim ON peserta.id_tim = tim.id_tim";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row["id_peserta"] . "</td>";
                                        echo "<td>" . $row["id_inGame"] . "</td>";
                                        echo "<td>" . $row["nickName"] . "</td>";
                                        echo "<td>" . $row["nama_role"] . "</td>";
                                        echo "<td>" . $row["nama_tim"] . "</td>";
                                        echo "<td>
                                                <a href='editPeserta.php?id=" . $row["id_peserta"] . "' class='btn btn-primary btn-sm'>Edit</a>
                                                <a href=\"hapusPeserta.php?id_peserta=" . $row["id_peserta"] . "\" onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\" class='btn btn-danger btn-sm'>Hapus</a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>0 results</td></tr>";
                                }
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Tombol untuk menambah peserta -->
                    <div class="text-end mt-3">
                        <button class="btn btn-success btn-metro" onclick="window.location.href='tambahPeserta.php'">Tambah Peserta</button>
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
                    url: '../ajax/ajax_caripeserta.php',
                    type: 'GET',
                    data: { keywords: keywords },
                    success: function(data) {
                        $('#pesertaTableBody').html(data);
                    }
                });
            });
        });
    </script>
    
</body>
</html>
