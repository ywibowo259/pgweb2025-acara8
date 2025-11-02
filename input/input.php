<?php
// Konfigurasi koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "latihan_8";

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data yg dikirim
$kecamatan = $_POST['kecamatan'];
$luas = $_POST['luas'];
$jumlah_penduduk = $_POST['jumlah_penduduk'];
$longitude = $_POST['longitude'];
$latitude = $_POST['latitude'];

// Query untuk memasukkan data
$sql = "INSERT INTO data_kecamatan (kecamatan, luas, jumlah_penduduk, longitude, latitude) 
        VALUES ('$kecamatan', '$luas', '$jumlah_penduduk', '$longitude', '$latitude')";

// Eksekusi query
if (mysqli_query($conn, $sql)) {
    // Jika berhasil, redirect ke halaman utama
    header("Location: ../index.php");
} else {
    // Jika gagal, tampilkan pesan error
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Tutup koneksi
mysqli_close($conn);
?>