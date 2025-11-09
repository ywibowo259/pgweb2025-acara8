<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB GIS Sleman</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

    <style>
        /* Modern Font & Body Background */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            line-height: 1.6;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1, h2 {
            color: #1d2129;
        }
        /* Layout Container */
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Adds space between columns */
        }
        .table-container, .map-container {
            flex: 1;
            min-width: 450px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        /* Map Styling */
        #map {
            height: 600px;
            border-radius: 8px;
        }
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50; /* Green header */
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Zebra striping */
        }
        tr:hover {
            background-color: #f1f1f1; /* Hover effect */
        }
        /* Link/Button Styling */
        .input-link {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }
        .input-link:hover {
            background-color: #45a049;
        }
        .action-link-delete, .action-link-edit {
            display: inline-block;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-size: 0.9em;
        }
        .action-link-delete {
            background-color: #f44336; /* Red */
        }
        .action-link-delete:hover {
            background-color: #da190b;
        }
        .action-link-edit {
            background-color: #008CBA; /* Blue */
        }
        .action-link-edit:hover {
            background-color: #007ba7;
        }
    </style>
</head>
<body>

    <h1>WebGIS Kabupaten Sleman</h1>

<?php
    // --- SINGLE DATABASE CONNECTION AND DATA FETCH ---
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latihan_8";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Fetch all data for table and map
    $sql = "SELECT * FROM data_kecamatan";
    $result = mysqli_query($conn, $sql);

    // Prepare an array for map locations
    $locations = [];
    $table_rows = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Store row for the table
            $table_rows[] = $row;
            // Store location for the map if lat/long exists
            if (!empty($row['latitude']) && !empty($row['longitude'])) {
                $locations[] = [
                    'kecamatan' => $row['kecamatan'],
                    'latitude' => floatval($row['latitude']),
                    'longitude' => floatval($row['longitude'])
                ];
            }
        }
    }
    mysqli_close($conn);
?>
    
    <div class="container">
        <div class="table-container">
            <h2>Data Tabel</h2>
            <a href='input/index.html' class='input-link'>+ Input Data</a>
            <?php
            //penambahan kolom aksi
                if (!empty($table_rows)) {
                    echo "<table>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kecamatan</th>
                                <th>Luas</th>
                                <th>Jumlah Penduduk</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th colspan='2'>Aksi</th>
                            </tr>";
                    //penambbahan link delete dan edit        
                    foreach ($table_rows as $row) {
                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["kecamatan"] . "</td>
                                <td>" . $row["luas"] . "</td>
                                <td>" . $row["jumlah_penduduk"] . "</td>
                                <td>" . $row["latitude"] . "</td>
                                <td>" . $row["longitude"] . "</td>
                                <td><a href='delete.php?id=" . $row["id"] . "' class='action-link-delete'>hapus</a></td>
                                <td><a href='edit/index.php?id=" . $row["id"] . "' class='action-link-edit'>edit</a></td>
                            </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Belum ada data.</p>";
                }
            ?>
        </div>

        <div class="map-container">
            <h2>Peta Lokasi</h2>
            <div id="map"></div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

    <script>
        // Get the PHP data for map locations
        var locations = <?php echo json_encode($locations, JSON_NUMERIC_CHECK); ?>;

        // Initialize the map
        var map = L.map('map');

        // Add a tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Check if there are locations to display
        if (locations.length > 0) {
            var markerGroup = L.featureGroup();

            locations.forEach(function(location) {
                if (location.latitude && location.longitude) {
                    var marker = L.marker([location.latitude, location.longitude]);
                    marker.bindPopup("<b>" + location.kecamatan + "</b>");
                    markerGroup.addLayer(marker);
                }
            });

            markerGroup.addTo(map);
            map.fitBounds(markerGroup.getBounds());
        } else {
            // Default view if no locations
            map.setView([-2.548926, 118.0148634], 5);
        }
    </script>

</body>
</html>
