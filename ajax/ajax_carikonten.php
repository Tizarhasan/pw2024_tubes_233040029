<?php
require '../php/functions.php';

if (isset($_GET['keywords'])) {
    $keywords = $_GET['keywords'];
    $keywordsArray = explode(' ', $keywords);
    $query = "SELECT c.id_content, c.id_turnamen, c.image_path, c.description, c.upload_date, t.nama_turnamen 
              FROM content c 
              JOIN turnamen t ON c.id_turnamen = t.id_turnamen
              WHERE ";

    foreach ($keywordsArray as $index => $keyword) {
        if ($index > 0) {
            $query .= " OR ";
        }
        $query .= "c.description LIKE '%$keyword%' OR
                   t.nama_turnamen LIKE '%$keyword%'";
    }

    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id_content']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_turnamen']) . "</td>";
        echo "<td><img src='../assets/img/" . htmlspecialchars($row['image_path']) . "' alt='Gambar Konten' style='max-width: 100px;'></td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['upload_date']) . "</td>";
        echo "<td>
                <a href='editContent.php?id_content=" . htmlspecialchars($row['id_content']) . "' class='btn btn-primary btn-sm'>Edit</a>
                <a href='hapuskonten.php?id_content=" . htmlspecialchars($row['id_content']) . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Anda yakin ingin menghapus konten ini?');\">Hapus</a>
              </td>";
        echo "</tr>";
     }
 } else {
    echo "<tr><td colspan='6' class='text-center'>Tidak ada item tersedia</td></tr>";
 }

}
?>
