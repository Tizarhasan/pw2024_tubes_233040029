<?php
require 'functions.php'; // Sesuaikan dengan lokasi file functions.php

if (isset($_GET['id_peserta'])) {
    $id_peserta = $_GET['id_peserta'];
    
    if (hapusPeserta($id_peserta) > 0) {
        echo "
            <script>
                alert('Peserta berhasil dihapus!');
                document.location.href = 'DashboardPeserta.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Peserta gagal dihapus!');
                document.location.href = 'DashboardPeserta.php';
            </script>
        ";
    }
} else {
    header("Location: DashboardPeserta.php");
    exit;
}
?>
