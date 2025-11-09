<?php
    // Ambil data yg telah diubah (post)
    $id = $_POST['id'];
    $kecamatan = $_POST['kecamatan'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];
    // Sesuaikan dengan setting MySQL
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latihan_8";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //UPDATE table_name SET field1=new-value1, field2=new-value2, ... WHERE condition;
    $sql = "UPDATE data_kecamatan SET kecamatan='$kecamatan', longitude=$longitude,
    latitude=$latitude, luas=$luas, jumlah_penduduk=$jumlah_penduduk WHERE
    id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record edited successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    header("Location: ../index.php");
?>