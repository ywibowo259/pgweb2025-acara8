<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Kecamatan</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    <style>
        #map { height: 600px; }
    </style>
</head>
<body>

<h2>Peta Persebaran Kecamatan</h2>
<div id="map"></div>

<?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latihan_8";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch data
    $sql = "SELECT kecamatan, latitude, longitude FROM data_kecamatan WHERE latitude IS NOT NULL AND longitude IS NOT NULL";
    $result = $conn->query($sql);
    $locations = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Pastikan latitude dan longitude adalah angka
            $row['latitude'] = floatval($row['latitude']);
            $row['longitude'] = floatval($row['longitude']);
            $locations[] = $row;
        }
    }
    $conn->close();
?>

<script>
    // Get the PHP data into JavaScript
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
        // Create a feature group to hold the markers
        var markerGroup = L.featureGroup();

        // Loop through the locations and add markers
        locations.forEach(function(location) {
            if (location.latitude && location.longitude) {
                var marker = L.marker([location.latitude, location.longitude]);
                marker.bindPopup("<b>" + location.kecamatan + "</b>");
                markerGroup.addLayer(marker);
            }
        });

        // Add the group to the map
        markerGroup.addTo(map);

        // Fit the map to show all markers
        map.fitBounds(markerGroup.getBounds());
    } else {
        // If no locations, set a default view (e.g., centered on Indonesia)
        map.setView([-2.548926, 118.0148634], 5);
    }
</script>

</body>
</html>
