<?php
require '../php/functions.php';

if (isset($_GET['keywords'])) {
    $keywords = $_GET['keywords'];
    $query = "SELECT turnamen_tim.id_turnamen_tim, turnamen.id_turnamen, turnamen.nama_turnamen, turnamen.tanggal_mulai, tim.id_tim, tim.nama_tim 
                FROM turnamen_tim 
                JOIN turnamen ON turnamen_tim.id_turnamen = turnamen.id_turnamen 
                JOIN tim ON turnamen_tim.id_tim = tim.id_tim
              WHERE ";

foreach ($keywords as $key => $keyword) {
    if ($key > 0) {
        $query .= " OR ";
    }
    $query .= "turnamen.nama_turnamen LIKE '%$keyword%' OR
               turnamen.tanggal_mulai LIKE '%$keyword%' OR
               tim.nama_tim LIKE '%$keyword%' ";
}


    $result = $conn->query($query);

if(mysqli_num_rows($result)> 0) {
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
} else {
    echo "<tr><td colspan='6' class='text-center'>Tidak ada item tersedia</td></tr>";
}
   
}
    mysqli_close($conn);
    ?>
