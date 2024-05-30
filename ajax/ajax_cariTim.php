<?php
require '../php/functions.php';

if (isset($_GET['keywords'])) {
    $keywords = $_GET['keywords'];
    $query =   $sql = "SELECT tim.*, peserta.nickName AS nama_peserta 
                FROM tim LEFT JOIN peserta ON tim.id_peserta = peserta.id_peserta
              WHERE ";

    foreach ($keywords as $key => $keyword) {
        if ($key > 0) {
            $query .= " OR ";
        }
        $query .= "tim.nama_tim LIKE '%$keyword%' OR
                    tim.Region LIKE '%$keyword%' OR
                   peserta.nickName LIKE '%$keyword%' ";
    }

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_tim"] . "</td>";
            echo "<td>" . $row["nama_tim"] . "</td>";
            echo "<td>" . $row["Region"] . "</td>";
            echo "<td><img src='../assets/img/" . $row["logo"] . "' alt='Logo Tim' style='max-width: 100px;'></td>"; 
            echo "<td>" . $row["nama_peserta"] . "</td>";
            echo "<td>
                    <a href='ubahtim.php?id=" . $row["id_tim"] . "' class='btn btn-primary btn-sm btn-edit'>Edit</a>
                    <a href=\"hapusTim.php?id=" . $row["id_tim"] . "\" onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\" class='btn btn-danger btn-sm'>Hapus</a>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Tidak ada item tersedia</td></tr>";
    }
}
?>
