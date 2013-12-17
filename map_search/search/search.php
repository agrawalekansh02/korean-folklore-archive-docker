<?php

$conn = new MySQLi("127.0.0.1", "root", '', "kfl");


$statement = $conn->prepare(
    "SELECT x(location), y(location), COUNT(*) FROM context_test c, data d WHERE d.context_id = c.context_id GROUP BY c.context_id"
);

print $conn->error;

$statement->execute();
$statement->bind_result($lon, $lat, $count);

$output = array(
    'type' => 'FeatureCollection',
    'features' => array(),
);

while ($statement->fetch()) {
    $output['features'][] = array(
        'geometry' => array(
            'type' => 'Point',
            'coordinates' => array($lon, $lat),
        ),
        'properties' => array(
            'count' => $count / 7000.0
        ),
        'type' => 'Feature',
    );
}

header("Content-type: application/json");
echo json_encode($output);
?>
