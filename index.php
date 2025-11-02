<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kecamatan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            display: block;
            width: fit-content;
            margin: 10px auto;
            text-decoration: none;
            background: #4CAF50;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
        }
        a:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <?php

    //koneksi ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latihan_8";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    //mengambil data dari database
    $sql = "SELECT * FROM data_kecamatan";
    //menampilkan data
    $result = mysqli_query($conn, $sql);

    //menambahkan link utk input data
    echo "<a href='input/index.html'>+ Input Data</a>";

    //menampilkan data pada tabel (jika ada)
    if (mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Nama Kecamatan</th>
                    <th>Luas</th>
                    <th>Jumlah Penduduk</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["kecamatan"] . "</td>
                    <td>" . $row["luas"] . "</td>
                    <td>" . $row["jumlah_penduduk"] . "</td>
                    <td>" . $row["latitude"] . "</td>
                    <td>" . $row["longitude"] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center;'>Belum ada data.</p>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>