<?php

ini_set('display_errors', 1);

require_once 'util.php';

require_once 'Factory.php';
require_once 'QualifiedQuery.php';
require_once 'SelectQuery.php';

require_once '../../lib.php';

use QuB\Factory;

$co_query = Factory::select('X(co.context_spatial_point) AS lng', 'Y(co.context_spatial_point) AS lat', 'count(*) AS total')
        ->from('context_test co')
        ->where("co.context_spatial_point !=''")
        ->group_by('co.context_spatial_point');

if ((isset($_GET['collector_gender'])) or (isset($_GET['collector_age'])) or (isset($_GET['collector_occupation'])) or (isset($_GET['collector_language']))) {
    $co_query->from('collector col', 'consultant con');
} elseif ((isset($_GET['consultant_gender'])) or (isset($_GET['consultant_age'])) or (isset($_GET['consultant_occupation'])) or (isset($_GET['consultant_language'])) or (isset($_GET['consultant_immigration_status']))) {
        $co_query->from('consultant con');
}

if (isset($_GET['collector_gender'])) {
    $co_query->and('co.context_consultants=con.consultant_id');
    $co_query->and('col.collector_id=con.collector_id');
    
    $co_query->open_group('AND');
    foreach ($_GET['collector_gender'] as $gender) {
            $gender = strtoupper($gender);
            $co_query->or('col.collector_gender LIKE ?', "$gender%");
    }
    $co_query->close_group();
}

if (isset($_GET['collector_age'])) {
    $co_query->and('co.context_consultants=con.consultant_id');
    $co_query->and('col.collector_id=con.collector_id');

    $age = $_GET['collector_age'];
    $co_query->and('col.collector_age = ?', $age);
}

if (isset($_GET['collector_occupation'])) {
    $co_query->and('co.context_consultants=con.consultant_id');
    $co_query->and('col.collector_id=con.collector_id');

    $occupation = $_GET['collector_occupation'];
    $co_query->and('col.collector_occupation LIKE ?', "$occupation");
}

if (isset($_GET['collector_language'])) {
    $co_query->and('co.context_consultants=con.consultant_id');
    $co_query->and('col.collector_id=con.collector_id');

    $co_query->open_group('AND');
    $language = $_GET['collector_language'];
    $languages = explode(",", $language);
    foreach ($languages as $language) {
        $co_query->or('col.collector_language LIKE ?', "%$language%");
    }
    $co_query->close_group();
}

if (isset($_GET['consultant_gender'])) {
    $co_query->and('co.context_consultants=con.consultant_id');
    
    $co_query->open_group('AND');
    foreach ($_GET['consultant_gender'] as $gender) {
            $gender = strtoupper($gender);
            $co_query->or('con.consultant_gender LIKE ?', "$gender%");
    }
    $co_query->close_group();
}

if (isset($_GET['consultant_age'])) {
    $co_query->and('co.context_consultants=con.consultant_id');

    $age = $_GET['consultant_age'];
    $co_query->and('con.consultant_age = ?', $age);
}

if (isset($_GET['consultant_occupation'])) {
    $co_query->and('co.context_consultants=con.consultant_id');

    $occupation = $_GET['consultant_occupation'];
    $co_query->and('con.consultant_occupation LIKE ?', "$occupation");
}

if (isset($_GET['consultant_language'])) {
    $co_query->and('co.context_consultants=con.consultant_id');

    $co_query->open_group('AND');
    $language = $_GET['consultant_language'];
    $languages = explode(",", $language);
    foreach ($languages as $language) {
        $co_query->or('con.consultant_language LIKE ?', "%$language%");
    }
    $co_query->close_group();
}

if (isset($_GET['consultant_immigration_status'])) {
    $co_query->and('co.context_consultants=con.consultant_id');

    $immigration_status = $_GET['consultant_immigration_status'];
    $co_query->and('con.consultant_age = ?', $immigration_status);
}

if (isset($_GET['context_name'])) {
    $name = $_GET['context_name'];
    $co_query->and('co.context_event_name LIKE ?', "$name");
}

if (isset($_GET['context_event_type'])) {
    $co_query->open_group('AND');
    foreach ($_GET['context_event_type'] as $event_type) {
            $co_query->or('co.context_event_type LIKE ?', "$event_type");
    }
    $co_query->close_group();
}

if (isset($_GET['context_time_of_day'])) {
    $co_query->open_group('AND');
    foreach ($_GET['context_time_of_day'] as $time) {
            $co_query->or('co.context_time LIKE ?', "$time");
    }
    $co_query->close_group();
}

if (isset($_GET['context_date'])) {
    $date = $_GET['context_date'];
    $co_query->and('co.context_day = ?', "$date");
}

if (isset($_GET['collection_weather'])) {
    $weather = $_GET['collection_weather'];
    $co_query->and('co.context_weather LIKE ?', "$weather");
}

if (isset($_GET['collection_language'])) {
    $co_query->open_group('AND');
    $language = $_GET['collection_language'];
    $languages = explode(",", $language);
    foreach ($languages as $language) {
        $co_query->or('co.context_language LIKE ?', "%$language%");
    }
    $co_query->close_group();
}

if (isset($_GET['collection_place_type'])) {
    $co_query->open_group('AND');
    foreach ($_GET['collection_place_type'] as $place_type) {
            $co_query->or('co.context_place LIKE ?', "$place_type");
    }
    $co_query->close_group();
}

if (isset($_GET['collection_others_present'])) {
    $num = $_GET['collection_others_present'];
    $co_query->and('co.context_otherpresent_num = ?', "$num");
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

if ((isset($_GET['project_title'])) or (isset($_GET['media'])) or (isset($_GET['description']))) {
    $co_query->from('data d');
}

if (isset($_GET['project_title'])) {
    $co_query->and('co.context_id=d.context_id');
    $project_title = $_GET['project_title'];
    $co_query->and('d.data_project_title LIKE ?', "$project_title");
}

if (isset($_GET['media'])) {
    $co_query->and('co.context_id=d.context_id');
    $co_query->open_group('AND');
    foreach ($_GET['media'] as $media) {
            $co_query->or('d.data_type LIKE ?', "$media");
    }
    $co_query->close_group();
}

if (isset($_GET['description'])) {
    $co_query->and('co.context_id=d.context_id');
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
    #print "Another ";
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

?>