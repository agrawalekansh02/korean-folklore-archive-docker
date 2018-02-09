<?php
require_once 'util.php';

require_once 'QuB/Factory.php';
require_once 'QuB/QualifiedQuery.php';
require_once 'QuB/SelectQuery.php';

require_once '../../lib.php';

use QuB\Factory;

$co_query = Factory::select('X(co.context_spatial_point) AS lng',
    'Y(co.context_spatial_point) AS lat', 'COUNT(*) AS total')
    ->from('data d', 'context co') 
    ->where('d.context_id = co.context_id')
        ->and("co.context_spatial_point IS NOT NULL")
    ->group_by('d.context_id');

$has_consultant_fields = array_filter(
    array_keys($_GET),
    function ($key) {
        return strpos($key, 'consultant_') === 0;
    }
); 

if ($has_consultant_fields) {
    $co_query->from('consultant con');
    $co_query->and('d.consultant_id = con.consultant_id');
}

$has_collector_fields = array_filter(
    array_keys($_GET),
    function ($key) {
        return strpos($key, 'collector_') === 0;
    }
);

if ($has_collector_fields) {
    $co_query->from('collector col');
    $co_query->and('d.collector_id = col.collector_id');
}

if (isset($_GET['collector_gender'])) {
    $co_query->open_group('AND');
    foreach ($_GET['collector_gender'] as $gender) {
        $gender = strtoupper($gender);
        $co_query->or('col.collector_gender = ?', $gender);
    }
    $co_query->close_group();
}

if (isset($_GET['collector_occupation'])) {
    $co_query->and('col.collector_occupation = ?',
        $_GET['collector_occupation']);
}

if (isset($_GET['collector_age'])) {
    $age = explode(",", $_GET['collector_age']);
    if (($age[0] == 18) && ($age[1] == 18)) {
        $co_query->and('col.collector_age <= 18');
    } elseif (($age[0] == 18) && ($age[1] == 80)) {
        $co_query->and('col.collector_age >= 0');
    } elseif (($age[0] == 80) && ($age[1] == 80)) {
        $co_query->and('col.collector_age >= 80');
    } elseif (($age[0] == 18) && ($age[1] < 80)) {
        $co_query->and('col.collector_age <= ?', $age[1]);
    } elseif (($age[0] > 18) && ($age[1] == 80)) {
        $co_query->and('col.collector_age >= ?', $age[0]);
    } else {
        $co_query->and('col.collector_age >= ?', $age[0]);
        $co_query->and('col.collector_age <= ?', $age[1]);
    }
}

if (isset($_GET['collector_language'])) {
    $co_query->open_group('AND');
    foreach ($_GET['collector_language'] as $language) {
        $co_query->or('col.collector_language LIKE ?', "%$language%");
    }
    $co_query->close_group();
}

if (isset($_GET['consultant_gender'])) {
    $co_query->open_group('AND');
    foreach ($_GET['consultant_gender'] as $gender) {
        $gender = strtoupper($gender);
        $co_query->or('con.consultant_gender = ?', $gender);
    }
    $co_query->close_group();
}

if (isset($_GET['consultant_occupation'])) {
    $co_query->and('con.consultant_occupation = ?',
        $_GET['consultant_occupation']);
}

if (isset($_GET['consultant_age'])) {
    $age = $_GET['consultant_age'];
    $age = explode(",", $age);
    if ($age[0] == 18 && $age[1] == 18) {
        $co_query->and('con.consultant_age >= 0');
        $co_query->and('con.consultant_age <= 18');
    } elseif ($age[0] == 18 && $age[1] == 80) {
        $co_query->and('con.consultant_age >= 0');
    } elseif ($age[0] == 80 && $age[1] == 80) {
        $co_query->and('con.consultant_age >= 80');
    } elseif ($age[0] == 18 && $age[1] < 80) {
        $co_query->and('con.consultant_age >= 0');
        $co_query->and('con.consultant_age <= ?', $age[1]);
    } elseif ($age[0]>18 && $age[1] == 80) {
        $co_query->and('con.consultant_age >= ?', $age[0]);
    } else {
        $co_query->and('con.consultant_age >= ?', $age[0]);
        $co_query->and('con.consultant_age <= ?', $age[1]);
    }
}

if (isset($_GET['consultant_language'])) {
    $co_query->open_group('AND');
    foreach ($_GET['consultant_language'] as $language) {
        $co_query->or('con.consultant_language LIKE ?', "%$language%");
    }
    $co_query->close_group();
}

if (isset($_GET['consultant_immigration_status'])) {
    $co_query->and('con.consultant_age = ?',
        $_GET['consultant_immigration_status']);
}

if (isset($_GET['context_name'])) {
    $co_query->and('co.context_event_name = ?', $_GET['context_name']);
}

if (isset($_GET['context_event_type'])) {
    $co_query->open_group('AND');
    foreach ($_GET['context_event_type'] as $event_type) {
        $co_query->or('co.context_event_type = ?', $event_type);
    }
    $co_query->close_group();
}

if (isset($_GET['context_time_of_day'])) {
    $co_query->open_group('AND');
    foreach ($_GET['context_time_of_day'] as $time) {
        $co_query->or('co.context_time = ?', $time);
    }
    $co_query->close_group();
}

if (isset($_GET['context_date_from']) && ($_GET['context_date_from'] != '')) {
    $co_query->and('co.context_date >= ?', $_GET['context_date_from']);
}

if (isset($_GET['context_date_to']) && ($_GET['context_date_to'] != '')) {
    $co_query->and('co.context_date <= ?', $_GET['context_date_to']);
}

if (isset($_GET['collection_weather'])) {
    $co_query->open_group('AND');
    foreach ($_GET['collection_weather'] as $weather) {
        $co_query->or('co.context_weather = ?', $weather);
    }
    $co_query->close_group();
}

if (isset($_GET['collection_language'])) {
    $co_query->open_group('AND');
    foreach ($_GET['collection_language'] as $language) {
        $co_query->or('co.context_language LIKE ?', "%$language%");
    }
    $co_query->close_group();
}

if (isset($_GET['collection_place_type'])) {
    $co_query->open_group('AND');
    foreach ($_GET['collection_place_type'] as $place_type) {
        $co_query->or('co.context_place = ?', $place_type);
    }
    $co_query->close_group();
}

if (isset($_GET['collection_others_present'])) {
    $co_query->open_group('AND');
    foreach ($_GET['collection_others_present'] as $others) {
        if ($others == 1) {
            $co_query->or('co.context_otherpresent_num = 1');
        } elseif ($others == '2-5') {
            $co_query->or('co.context_otherpresent_num >= 2');
            $co_query->or('co.context_otherpresent_num <= 5');
        } else {
            $co_query->or('co.context_otherpresent_num > 5');
        }
    }
    $co_query->close_group();
}

if (isset($_GET['collection_method'])) {
    $co_query->open_group('AND');
    foreach ($_GET['collection_method'] as $method) {
        $co_query->or('co.context_media LIKE ?', "%$method%");
    }
    $co_query->close_group();
}

if (isset($_GET['collection_description'])) {
    $desc = $_GET['collection_description'];
    $co_query->and('co.context_description LIKE ?', "%$desc%");
}

if ((isset($_GET['project_title'])) || (isset($_GET['media']))
    || (isset($_GET['description']))) {
}

if (isset($_GET['project_title'])) {
    $co_query->and('d.data_project_title = ?', $_GET['project_title']);
}

if (isset($_GET['media'])) {
    $co_query->open_group('AND');
    foreach ($_GET['media'] as $media) {
        $co_query->or('d.data_type = ?', $media);
    }
    $co_query->close_group();
}

if (isset($_GET['description'])) {
    $desc = $_GET['description'];
    $co_query->and('d.data_description LIKE ?', "%$desc%");
}

$connection = get_connection();
$statement = $connection->prepare($co_query);
if (sizeof($co_query->params()) > 1) {
    call_user_func_array(array($statement, 'bind_param'), $co_query->params());
}
$statement->execute();
$statement->bind_result($lat, $long, $total);

// return results as JSON

while ($statement->fetch()) {
    $coordinates[] = array(
        'type' => 'Feature',
        'geometry' => array(
            'type' => 'Point',
            'coordinates' => array($lat, $long)
       ),
        'properties' => array(
            'type' => 'context',
            'total' => $total,
       ),
   );
}

if (isset($coordinates)) {
    echo json_encode(array(
        "type" => "FeatureCollection",
        "features" => $coordinates,
       ));
} else {
    echo json_encode(array(
        "error" => "No Results Found",
       ));
}

