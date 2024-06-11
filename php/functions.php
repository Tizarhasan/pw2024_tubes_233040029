<?php
$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}



function registrasi($data)
{
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);
    $role = isset($data["role"]) ? $data["role"] : ""; // Assign an empty string if the "role" key is not set

    // Cek username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
        alert('Username sudah terdaftar!');
        </script>";
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>
            alert('Konfirmasi password tidak sesuai!');
        </script>";
        return false;
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database dengan role
    mysqli_query($conn, "INSERT INTO user (username, password) VALUES ('$username', '$password')");

    return mysqli_affected_rows($conn);
}


function tambahTim($data)
{
    global $conn;

    // Panggil fungsi upload untuk mengupload file dan mendapatkan nama file yang baru
    $logo = upload();
    if ($logo === "Pilih logo terlebih dahulu" || $logo === "Yang Anda upload bukan file gambar" || $logo === "Ukuran logo terlalu besar" || $logo === "Gagal mengunggah file") {
        return -1; // Mengembalikan nilai negatif untuk menandakan kesalahan
    }

    $nama_tim = htmlspecialchars($data["nama_tim"]);
    $Region = htmlspecialchars($data["Region"]);
    $id_peserta =!empty($data["id_peserta"])? "'". htmlspecialchars($data["id_peserta"]). "'" : "NULL";

    // Query insert data
    $query = "INSERT INTO tim (nama_tim, Region, logo, id_peserta) 
              VALUES ('$nama_tim', '$Region', '$logo', $id_peserta)";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


function getTimById($id_tim) {
    global $conn;

    $result = mysqli_query($conn, "SELECT * FROM tim WHERE id_tim = $id_tim");
    return mysqli_fetch_assoc($result);
}

function editTim($data) {
    global $conn;

    $id_tim = $data["id_tim"];
    $nama_tim = htmlspecialchars($data["nama_tim"]);
    $Region = htmlspecialchars($data["Region"]);
    $id_peserta = htmlspecialchars($data["id_peserta"]);



    // Query to update team data
    $query_tim = "UPDATE tim 
                  SET nama_tim = '$nama_tim', 
                      Region = '$Region',
                      id_peserta = '$id_peserta'
                  WHERE id_tim = $id_tim";

    // Execute query to update team data
    mysqli_query($conn, $query_tim);

    // Query to update participant data to update id_tim
    $query_peserta = "UPDATE peserta 
                      SET id_tim = $id_tim
                      WHERE id_peserta = $id_peserta";

    // Execute query to update participant data
    mysqli_query($conn, $query_peserta);

    return mysqli_affected_rows($conn);
}


function hapusTim($id)
{
    global $conn;

    // Hapus semua entri terkait dari tabel turnamen_tim
    $query1 = "DELETE FROM turnamen_tim WHERE id_tim = $id";
    mysqli_query($conn, $query1);
    
    // Set id_tim pada peserta menjadi NULL sebelum menghapus tim
    $query2 = "UPDATE peserta SET id_tim = NULL WHERE id_tim = $id";
    mysqli_query($conn, $query2);

    // Hapus tim dari tabel tim
    $query3 = "DELETE FROM tim WHERE id_tim = $id";
    mysqli_query($conn, $query3);
    
    return mysqli_affected_rows($conn);
}





function tambahPeserta($data_peserta)
{
    global $conn;
    $id_inGame = $data_peserta["id_inGame"];
    $nickName = $data_peserta["nickName"];
    $id_role = $data_peserta["id_role"];
    $id_tim = $data_peserta["id_tim"];
    $email = $data_peserta["email"];

    $query = "INSERT INTO peserta (id_inGame, nickName, id_role, id_tim, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssiss', $id_inGame, $nickName, $id_role, $id_tim, $email);
    
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        return -1; // Jika terjadi kesalahan saat mengeksekusi statement
    }

   
}

function getAllRoles($conn) {
    $result = mysqli_query($conn, "SELECT * FROM roleingame");
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function ambilSemuaTurnamen() {
    global $conn;
    $query = "SELECT * FROM turnamen";
    $result = mysqli_query($conn, $query);
    $turnamens = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $turnamens[] = $row;
    }
    return $turnamens;
}

function getTournamentName($conn, $tournamentID) {
    $sql = "SELECT nama_turnamen FROM turnamen WHERE id_turnamen = $tournamentID";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['nama_turnamen'];
}

// Fungsi untuk mengambil semua data tim dari tabel tim
function ambilSemuaTim() {
    global $conn;
    $query = "SELECT * FROM tim";
    $result = mysqli_query($conn, $query);
    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $teams[] = $row;
    }
    return $teams;
}
function getAllTeamsForTurnamen($id_turnamen) {
    global $conn;
    $query = "SELECT tim.id_tim, tim.nama_tim FROM tim INNER JOIN turnamen_tim ON tim.id_tim = turnamen_tim.id_tim WHERE turnamen_tim.id_turnamen = $id_turnamen";
    $result = mysqli_query($conn, $query);
    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $teams[] = $row;
    }
    return $teams;
}

function ambilNamaTim($conn, $teamIDs) {
    global $conn;
    $teamsData = [];
    foreach ($teamIDs as $teamID) {
        $sql = "SELECT id_tim, nama_tim, logo FROM tim WHERE id_tim = $teamID"; // tambahkan field logo
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $teamsData[] = $row; // Memasukkan data tim beserta logo ke dalam array
    }
    return $teamsData; // Mengembalikan data tim beserta logo
}




function getPesertaById($id, $conn) {
    $result = mysqli_query($conn, "SELECT * FROM peserta WHERE id_peserta = $id");
    return mysqli_fetch_assoc($result);
}

function editPeserta($data)
{
    global $conn;

    $id_peserta = $data["id_peserta"];
    $id_inGame = htmlspecialchars($data["id_inGame"]);
    $nickName = htmlspecialchars($data["nickName"]);
    $id_role = htmlspecialchars($data["id_role"]);

    // Query update data
    $query = "UPDATE peserta 
              SET id_inGame = '$id_inGame', 
                  nickName = '$nickName',
                  id_role = '$id_role'
              WHERE id_peserta = $id_peserta";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function hapusPeserta($id)
{
    global $conn;

    // Set id_peserta pada tim menjadi NULL sebelum menghapus peserta
    $query1 = "UPDATE tim SET id_peserta = NULL WHERE id_peserta = $id";
    mysqli_query($conn, $query1);

    // Hapus peserta dari tabel peserta
    $query2 = "DELETE FROM peserta WHERE id_peserta = $id";
    mysqli_query($conn, $query2);
    
    return mysqli_affected_rows($conn);
}



// Fungsi untuk menambahkan data turnamen ke dalam tabel turnamen
function tambahTurnamen($data) {
    global $conn;
    $nama_turnamen = htmlspecialchars($data["nama_turnamen"]);
    $tanggal_mulai = htmlspecialchars($data["tanggal_mulai"]); // Menggunakan htmlspecialchars untuk menghindari injection

    $query_turnamen = "INSERT INTO turnamen (nama_turnamen, tanggal_mulai) VALUES ('$nama_turnamen', '$tanggal_mulai')";
    mysqli_query($conn, $query_turnamen);

    return mysqli_affected_rows($conn);
}


function tambahTurnamenTim($data) {
    global $conn;
    $id_turnamen = htmlspecialchars($data["id_turnamen"]);
    $tim_ids = $data["id_tim"];

    // Tambahkan data tim yang berpartisipasi ke dalam tabel turnamen_tim
    foreach ($tim_ids as $id_tim) {
        $query_turnamen_tim = "INSERT INTO turnamen_tim (id_turnamen, id_tim) VALUES ('$id_turnamen', '$id_tim')";
        mysqli_query($conn, $query_turnamen_tim);
    }
    
    return count($tim_ids);
}

function tambahTurnamenTimUser($data) {
    global $conn;
    
    // Debugging: Check if 'id_turnamen' is set and not empty
    if (isset($data["id_turnamen"]) && !empty($data["id_turnamen"])) {
        $id_turnamen = htmlspecialchars($data["id_turnamen"]);
        echo "ID Turnamen: " . $id_turnamen . "<br>"; // Debugging
        
        // Ensure 'id_tim' is set and not empty
        if (!empty($data["id_tim"])) {
            $id_tim = htmlspecialchars($data["id_tim"]);
            echo "ID Tim: " . $id_tim . "<br>"; // Debugging
            
            // Insert data into the 'turnamen_tim' table 
            $query_turnamen_tim = "INSERT INTO turnamen_tim (id_turnamen, id_tim) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $query_turnamen_tim);
            
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, 'ii', $id_turnamen, $id_tim);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script> 
                    alert('Data turnamen tim berhasil ditambahkan!');
                    document.location.href = 'turnamenku.php';
                </script>";
            } else {
                echo "<script>
                    alert('Gagal menambahkan data turnamen tim!');
                    document.location.href = 'turnamenku.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('ID tim tidak valid!');
                document.location.href = 'turnamenku.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('ID turnamen tidak valid!');
            document.location.href = 'turnamenku.php';
        </script>";
    }
}






// Fungsi untuk mendapatkan data turnamen_tim berdasarkan ID
function getTurnamenTimById($id, $conn) {
    $query = "SELECT * FROM turnamen_tim WHERE id_turnamen_tim = $id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    return mysqli_fetch_assoc($result);
}

function getAllTournaments($conn) {
    $query = "SELECT * FROM turnamen";
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function getAllTeams($conn) {
    $query = "SELECT * FROM tim";
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function updateTurnamenTim($data) {
    global $conn;
    $id_turnamen_tim = htmlspecialchars($data["id_turnamen_tim"]);
    $id_turnamen = htmlspecialchars($data["id_turnamen"]);
    $id_tim = htmlspecialchars($data["id_tim"]);

    // Query update data
    $query = "UPDATE turnamen_tim SET id_turnamen = '$id_turnamen', id_tim = '$id_tim' WHERE id_turnamen_tim = $id_turnamen_tim";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}



function hapusTurnamenTim($id_turnamen_tim)
{
    global $conn;

    // Pastikan koneksi database telah diinisialisasi dengan benar
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "DELETE FROM turnamen_tim WHERE id_turnamen_tim = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Pastikan persiapan statement berhasil
    if ($stmt === false) {
        echo "Error: " . mysqli_error($conn);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $id_turnamen_tim);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_affected_rows($stmt);
}

function upload()
{
    // Cek apakah input file 'logo' ada
    if (!isset($_FILES['logo'])) {
        return "Logo tidak ditemukan";
    }

    $namafile = $_FILES['logo']['name'];
    $ukuranfile = $_FILES['logo']['size'];
    $error = $_FILES['logo']['error'];
    $tmpName = $_FILES['logo']['tmp_name'];

    // Cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        return "Pilih logo terlebih dahulu";
    }

    // Cek apakah yang diupload adalah gambar
    $ekstensifileValid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensifile = explode('.', $namafile);
    $ekstensifile = strtolower(end($ekstensifile));
    if (!in_array($ekstensifile, $ekstensifileValid)) {
        return "Yang Anda upload bukan file gambar";
    }

    // Cek jika ukurannya terlalu besar
    if ($ukuranfile > 5000000) {
        return "Ukuran logo terlalu besar";
    }

    // Generate nama gambar baru
    $namafilebaru = uniqid() . '.' . $ekstensifile;

    // Tentukan jalur direktori penyimpanan
    $direktori_simpan = $_SERVER['DOCUMENT_ROOT'] . '/pw2024_tubes_233040029/assets/img/';

   
    // Pindahkan file ke direktori yang diinginkan
    if (move_uploaded_file($tmpName, $direktori_simpan . $namafilebaru)) {
        // Jika berhasil, kembalikan nama file baru
        return $namafilebaru;
    } else {
        // Jika gagal, kembalikan pesan kesalahan
        return "Gagal mengunggah file";
    }
}


function generateBracket($teams) {
    $matches = [];
    $numMatches = count($teams) / 2;
    for ($i = 0; $i < $numMatches; $i++) {
        $team1Index = $i * 2;
        $team2Index = $i * 2 + 1;
        $matches[] = [
            $teams[$team1Index], // Memasukkan data tim beserta logo ke dalam array
            $teams[$team2Index]  // Memasukkan data tim beserta logo ke dalam array
        ];
    }
    return $matches;
}



// Ensure you have the connection to the database
$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");

// Function to add content
function tambahKonten($data) {
    global $conn;

    $id_turnamen = htmlspecialchars($data["id_turnamen"]);
    $description = htmlspecialchars($data["description"]);
    $video_url = isset($data["video_url"]) ? htmlspecialchars($data["video_url"]) : ''; 

    // Proses upload gambar
    $image = uploadImage();
    if (!$image) {
        return -1; // Gagal upload gambar
    }

    $query = "INSERT INTO content (id_turnamen, image_path, description, video_url) 
              VALUES ('$id_turnamen', '$image', '$description', '$video_url')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


// Function to edit content
function editKonten($data, $image) {
    global $conn;

    $id_content = $data["id_content"];
    $id_turnamen = htmlspecialchars($data["id_turnamen"]);
    $description = htmlspecialchars($data["description"]);
    $video_url = htmlspecialchars($data["video_url"]);

    // Check if there is a new image uploaded
    if ($image) {
        // Update with new image
        $new_image_path = uploadImage();
        $query = "UPDATE content 
                  SET id_turnamen = '$id_turnamen', 
                      image_path = '$new_image_path', 
                      description = '$description' ,
                      video_url = '$video_url'
                  WHERE id_content = $id_content";
    } else {
        // Update without new image
        $query = "UPDATE content 
                  SET id_turnamen = '$id_turnamen', 
                      description = '$description' ,
                      video_url = '$video_url'
                  WHERE id_content = $id_content";
    }

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// Function to delete content
function hapusKonten($id_content) {
    global $conn;

    // Bersihkan input
    $id_content = intval($id_content);

    // Delete the content from the database
    $query = "DELETE FROM content WHERE id_content = $id_content";
    mysqli_query($conn, $query);

    // Periksa apakah query berhasil dijalankan
    if(mysqli_affected_rows($conn) > 0) {
        return true;
    } else {
        // Untuk debugging: echo "Error: " . mysqli_error($conn);
        return false;
    }
}


// Function to upload an image
function uploadImage() {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $namafile = $_FILES['image']['name'];
    $ukuranfile = $_FILES['image']['size'];
    $tmpName = $_FILES['image']['tmp_name'];

    $ekstensifileValid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensifile = strtolower(pathinfo($namafile, PATHINFO_EXTENSION));
    
    if (!in_array($ekstensifile, $ekstensifileValid)) {
        return false;
    }

    if ($ukuranfile > 5000000) {
        return false;
    }

    $namafilebaru = uniqid() . '.' . $ekstensifile;
    $direktori_simpan = $_SERVER['DOCUMENT_ROOT'] . '/pw2024_tubes_233040029/assets/img/';

    if (move_uploaded_file($tmpName, $direktori_simpan . $namafilebaru)) {
        return $namafilebaru;
    } else {
        return false;
    }
}

function tambahTimUser($data, $id_user)
{
    global $conn;

    // Panggil fungsi upload untuk mengupload file dan mendapatkan nama file yang baru
    $logo = upload();
    if ($logo === "Pilih logo terlebih dahulu" || $logo === "Yang Anda upload bukan file gambar" || $logo === "Ukuran logo terlalu besar" || $logo === "Gagal mengunggah file") {
        return -1; // Mengembalikan nilai negatif untuk menandakan kesalahan
    }

    $nama_tim = htmlspecialchars($data["nama_tim"]);
    $Region = htmlspecialchars($data["Region"]);
    $id_peserta = !empty($data["id_peserta"]) ? "'" . htmlspecialchars($data["id_peserta"]) . "'" : "NULL";

    // Query insert data tim
    $query = "INSERT INTO tim (nama_tim, Region, logo, id_peserta) 
              VALUES ('$nama_tim', '$Region', '$logo', $id_peserta)";
    mysqli_query($conn, $query);

    // Mendapatkan id_tim dari tim yang baru saja dibuat
    $id_tim_baru = mysqli_insert_id($conn);

    // Update id_tim pada user dengan id_user tertentu
    $query_update_user = "UPDATE user SET id_tim = $id_tim_baru WHERE id_user = $id_user";
    mysqli_query($conn, $query_update_user);

    // Mengembalikan jumlah baris yang terpengaruh oleh operasi MySQL
    return mysqli_affected_rows($conn);

    $result = mysqli_query($conn, $query);
if (!$result) {
    echo "Error: " . mysqli_error($conn);
}

}
