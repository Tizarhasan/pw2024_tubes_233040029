<?php
require '../php/functions.php';

if (isset($_GET['keywords'])) {
    $keywords = $_GET['keywords'];
    $query = "SELECT peserta.*, roleingame.nama_role, tim.nama_tim 
              FROM peserta 
              LEFT JOIN roleingame ON peserta.id_role = roleingame.id_role
              LEFT JOIN tim ON peserta.id_tim = tim.id_tim
              WHERE ";

    foreach ($keywords as $key => $keyword) {
        if ($key > 0) {
            $query .= " OR ";
        }
        $query .= "peserta.id_Ingame LIKE '%$keyword%' OR
                   peserta.nickName LIKE '%$keyword%' OR
                   roleingame.nama_role LIKE '%$keyword%' OR
                   tim.nama_tim LIKE '%$keyword%'";
    }

    $result = $conn->query($query);

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
                    <a href='hapusPeserta.php?id_peserta=" . $row["id_peserta"] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")' class='btn btn-danger btn-sm'>Hapus</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Tidak ada item tersedia</td></tr>";
    }
}
?>
