<?php
require 'functions.php';

$id = $_GET["id"];

// Hapus tim dan semua entri terkait dari tabel peserta
if (hapusTim($id) > 0) {
    echo "
        <script>
            alert('Tim berhasil dihapus!');
            document.location.href = 'DashboardTim.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Tim gagal dihapus!');
            document.location.href = 'DashboardTim.php';
        </script>
    ";
}
?>
