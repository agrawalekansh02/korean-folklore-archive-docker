<?php
ini_set('default_charset', 'utf-8');

header('Content-type: application/json; charset=utf-8');

require_once 'util.php';

require_once 'QuB/Factory.php';
require_once 'QuB/QualifiedQuery.php';
require_once 'QuB/SelectQuery.php';

require_once '../../lib.php';

use QuB\Factory;

$res_query = Factory::select('co.context_city AS city',
    'co.context_date AS date',
//    'SUBSTRING(d.data_description,1,20) AS description',
    'd.data_description AS description',
    'd.data_project_title AS projectTitle', 'd.data_id AS dataId',
    'd.collector_id AS collectorId')
->from('context co', 'data d')
->where("co.context_id = d.context_id AND
    co.context_spatial_point IS NOT NULL");

$count_query = Factory::select('COUNT(*) AS totalRows')
->from('context co', 'data d')
->where("co.context_id = d.context_id AND
    co.context_spatial_point IS NOT NULL");

if (isset($_GET['context_bbox'])) {
    $bbox = $_GET['context_bbox'];
    $bbox = explode(",", $bbox);
    $polygon = "Polygon(($bbox[0] $bbox[1],$bbox[0] $bbox[3],$bbox[2] $bbox[3],
        $bbox[2] $bbox[1],$bbox[0] $bbox[1]))";
    $res_query->and("MBRContains(GeomFromText(?),co.context_spatial_point) = 1"
        , $polygon);
    $count_query->and("MBRContains(GeomFromText(?),co.context_spatial_point) = 1"
        , $polygon);
}

if ((isset($_GET['collector_gender'])) || (isset($_GET['collector_age'])) 
    || (isset($_GET['collector_occupation'])) 
    || (isset($_GET['collector_language']))) {
    $res_query->from('collector col', 'consultant con');
    $count_query->from('collector col', 'consultant con');
} elseif ((isset($_GET['consultant_gender'])) 
    || (isset($_GET['consultant_age'])) 
    || (isset($_GET['consultant_occupation'])) 
    || (isset($_GET['consultant_language'])) 
    || (isset($_GET['consultant_immigration_status']))) {
    $res_query->from('consultant con');
    $count_query->from('consultant con');
}

if (isset($_GET['collector_gender'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $res_query->and('col.collector_id = con.collector_id');
    $res_query->open_group('AND');
    foreach ($_GET['collector_gender'] as $gender) {
        $gender = strtoupper($gender);
        $res_query->or('col.collector_gender = ?', $gender);
    }
    $res_query->close_group();

    $count_query->and('co.context_consultants = con.consultant_id');
    $count_query->and('col.collector_id = con.collector_id');
    $count_query->open_group('AND');
    foreach ($_GET['collector_gender'] as $gender) {
        $gender = strtoupper($gender);
        $count_query->or('col.collector_gender = ?', $gender);
    }
    $count_query->close_group();
}

if (isset($_GET['collector_occupation'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $res_query->and('col.collector_id = con.collector_id');
    $res_query->and('col.collector_occupation = ?', 
        $_GET['collector_occupation']);

    $count_query->and('co.context_consultants = con.consultant_id');
    $count_query->and('col.collector_id = con.collector_id');
    $count_query->and('col.collector_occupation = ?', 
        $_GET['collector_occupation']);
}

if (isset($_GET['collector_age'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $res_query->and('col.collector_id = con.collector_id');
    $age = $_GET['collector_age'];
    $age = explode(",", $age);
    if ($age[0] == 18 && $age[1] == 18) {
        $res_query->and('col.collector_age >= 0');
        $res_query->and('col.collector_age <= 18');
    } elseif ($age[0] == 18 && $age[1] == 80) {
        $res_query->and('col.collector_age >= 0');
    } elseif ($age[0] == 80 && $age[1] == 80) {
        $res_query->and('col.collector_age >= 80');
    } elseif ($age[0] == 18 && $age[1] < 80) {
        $res_query->and('col.collector_age >= 0');
        $res_query->and('col.collector_age <= ?', $age[1]);
    } elseif ($age[0]>18 && $age[1] == 80) {
        $res_query->and('col.collector_age >= ?', $age[0]);
    } else {
        $res_query->and('col.collector_age >= ?', $age[0]);
        $res_query->and('col.collector_age <= ?', $age[1]);
    }

    $count_query->and('co.context_consultants = con.consultant_id');
    $count_query->and('col.collector_id = con.collector_id');
    $age = $_GET['collector_age'];
    $age = explode(",", $age);
    if ($age[0] == 18 && $age[1] == 18) {
        $count_query->and('col.collector_age >= 0');
        $count_query->and('col.collector_age <= 18');
    } elseif ($age[0] == 18 && $age[1] == 80) {
        $count_query->and('col.collector_age >= 0');
    } elseif ($age[0] == 80 && $age[1] == 80) {
        $count_query->and('col.collector_age >= 80');
    } elseif ($age[0] == 18 && $age[1] < 80) {
        $count_query->and('col.collector_age >= 0');
        $count_query->and('col.collector_age <= ?', $age[1]);
    } elseif ($age[0]>18 && $age[1] == 80) {
        $count_query->and('col.collector_age >= ?', $age[0]);
    } else {
        $count_query->and('col.collector_age >= ?', $age[0]);
        $count_query->and('col.collector_age <= ?', $age[1]);
    }
}

if (isset($_GET['collector_language'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $res_query->and('col.collector_id = con.collector_id');
    $res_query->open_group('AND');
    foreach ($_GET['collector_language'] as $language) {
        $res_query->or('col.collector_language LIKE ?', "%$language%");
    }
    $res_query->close_group();

    $count_query->and('co.context_consultants = con.consultant_id');
    $count_query->and('col.collector_id = con.collector_id');
    $count_query->open_group('AND');
    foreach ($_GET['collector_language'] as $language) {
        $count_query->or('col.collector_language LIKE ?', "%$language%");
    }
    $count_query->close_group();
}

if (isset($_GET['consultant_gender'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $res_query->open_group('AND');
    foreach ($_GET['consultant_gender'] as $gender) {
        $gender = strtoupper($gender);
        $res_query->or('con.consultant_gender = ?', $gender);
    }
    $res_query->close_group();

    $count_query->and('co.context_consultants = con.consultant_id');
    $count_query->open_group('AND');
    foreach ($_GET['consultant_gender'] as $gender) {
        $gender = strtoupper($gender);
        $count_query->or('con.consultant_gender = ?', $gender);
    }
    $count_query->close_group();
}

if (isset($_GET['consultant_occupation'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $res_query->and('con.consultant_occupation = ?', 
        $_GET['consultant_occupation']);

    $count_query->and('co.context_consultants = con.consultant_id');
    $count_query->and('con.consultant_occupation = ?', 
        $_GET['consultant_occupation']);
}

if (isset($_GET['consultant_age'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $age = $_GET['consultant_age'];
    $age = explode(",", $age);
    if ($age[0] == 18 && $age[1] == 18) {
        $res_query->and('con.consultant_age >= 0');
        $res_query->and('con.consultant_age <= 18');
    } elseif ($age[0] == 18 && $age[1] == 80) {
        $res_query->and('con.consultant_age >= 0');
    } elseif ($age[0] == 80 && $age[1] == 80) {
        $res_query->and('con.consultant_age >= 80');
    } elseif ($age[0] == 18 && $age[1] < 80) {
        $res_query->and('con.consultant_age >= 0');
        $res_query->and('con.consultant_age <= ?', $age[1]);
    } elseif ($age[0]>18 && $age[1] == 80) {
        $res_query->and('con.consultant_age >= ?', $age[0]);
    } else {
        $res_query->and('con.consultant_age >= ?', $age[0]);
        $res_query->and('con.consultant_age <= ?', $age[1]);
    }

    $count_query->and('co.context_consultants = con.consultant_id');
    $age = $_GET['consultant_age'];
    $age = explode(",", $age);
    if ($age[0] == 18 && $age[1] == 18) {
        $count_query->and('con.consultant_age >= 0');
        $count_query->and('con.consultant_age <= 18');
    } elseif ($age[0] == 18 && $age[1] == 80) {
        $count_query->and('con.consultant_age >= 0');
    } elseif ($age[0] == 80 && $age[1] == 80) {
        $count_query->and('con.consultant_age >= 80');
    } elseif ($age[0] == 18 && $age[1] < 80) {
        $count_query->and('con.consultant_age >= 0');
        $count_query->and('con.consultant_age <= ?', $age[1]);
    } elseif ($age[0]>18 && $age[1] == 80) {
        $count_query->and('con.consultant_age >= ?', $age[0]);
    } else {
        $count_query->and('con.consultant_age >= ?', $age[0]);
        $count_query->and('con.consultant_age <= ?', $age[1]);
    }
}

if (isset($_GET['consultant_language'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $res_query->open_group('AND');
    foreach ($_GET['consultant_language'] as $language) {
        $res_query->or('con.consultant_language LIKE ?', "%$language%");
    }
    $res_query->close_group();

    $count_query->and('co.context_consultants = con.consultant_id');
    $count_query->open_group('AND');
    foreach ($_GET['consultant_language'] as $language) {
        $count_query->or('con.consultant_language LIKE ?', "%$language%");
    }
    $count_query->close_group();
}

if (isset($_GET['consultant_immigration_status'])) {
    $res_query->and('co.context_consultants = con.consultant_id');
    $res_query->and('con.consultant_age = ?', 
        $_GET['consultant_immigration_status']);

    $count_query->and('co.context_consultants = con.consultant_id');
    $count_query->and('con.consultant_age = ?', 
        $_GET['consultant_immigration_status']);
}

if (isset($_GET['context_name'])) {
    $res_query->and('co.context_event_name = ?', $_GET['context_name']);

    $count_query->and('co.context_event_name = ?', $_GET['context_name']);
}

if (isset($_GET['context_event_type'])) {
    $res_query->open_group('AND');
    foreach ($_GET['context_event_type'] as $event_type) {
        $res_query->or('co.context_event_type = ?', $event_type);
    }
    $res_query->close_group();

    $count_query->open_group('AND');
    foreach ($_GET['context_event_type'] as $event_type) {
        $count_query->or('co.context_event_type = ?', $event_type);
    }
    $count_query->close_group();
}

if (isset($_GET['context_time_of_day'])) {
    $res_query->open_group('AND');
    foreach ($_GET['context_time_of_day'] as $time) {
        $res_query->or('co.context_time = ?', $time);
    }
    $res_query->close_group();

    $count_query->open_group('AND');
    foreach ($_GET['context_time_of_day'] as $time) {
        $count_query->or('co.context_time = ?', $time);
    }
    $count_query->close_group();
}

if (isset($_GET['context_date_from']) && ($_GET['context_date_from'] != '')) {
    $res_query->and('co.context_date >= ?', $_GET['context_date_from']);

    $count_query->and('co.context_date >= ?', $_GET['context_date_from']);
}

if (isset($_GET['context_date_to']) && ($_GET['context_date_to'] != '')) {
    $res_query->and('co.context_date <= ?', $_GET['context_date_to']);

    $count_query->and('co.context_date <= ?', $_GET['context_date_to']);
}

if (isset($_GET['collection_weather'])) {
    $res_query->open_group('AND');
    foreach ($_GET['collection_weather'] as $weather) {
        $res_query->or('co.context_weather = ?', $weather);
    }
    $res_query->close_group();

    $count_query->open_group('AND');
    foreach ($_GET['collection_weather'] as $weather) {
        $count_query->or('co.context_weather = ?', $weather);
    }
    $count_query->close_group();
}

if (isset($_GET['collection_language'])) {
    $res_query->open_group('AND');
    foreach ($_GET['collection_language'] as $language) {
        $res_query->or('co.context_language LIKE ?', "%$language%");
    }
    $res_query->close_group();

    $count_query->open_group('AND');
    foreach ($_GET['collection_language'] as $language) {
        $count_query->or('co.context_language LIKE ?', "%$language%");
    }
    $count_query->close_group();
}

if (isset($_GET['collection_place_type'])) {
    $res_query->open_group('AND');
    foreach ($_GET['collection_place_type'] as $place_type) {
        $res_query->or('co.context_place = ?', $place_type);
    }
    $res_query->close_group();

    $count_query->open_group('AND');
    foreach ($_GET['collection_place_type'] as $place_type) {
        $count_query->or('co.context_place = ?', $place_type);
    }
    $count_query->close_group();
}

if (isset($_GET['collection_others_present'])) {
    $res_query->open_group('AND');
    foreach ($_GET['collection_others_present'] as $others) {
        if ($others == 1) {
            $res_query->or('co.context_otherpresent_num = 1');
        } elseif ($others == '2-5') {
            $res_query->or('co.context_otherpresent_num >= 2 OR 
                co.context_otherpresent_num <= 5');
        } else {
            $res_query->or('co.context_otherpresent_num > 5');
        }
    }
    $res_query->close_group();

    $count_query->open_group('AND');
    foreach ($_GET['collection_others_present'] as $others) {
        if ($others == 1) {
            $count_query->or('co.context_otherpresent_num = 1');
        } elseif ($others == '2-5') {
            $count_query->or('co.context_otherpresent_num >= 2 OR 
                co.context_otherpresent_num <= 5');
        } else {
            $count_query->or('co.context_otherpresent_num > 5');
        }
    }
    $count_query->close_group();
}

if (isset($_GET['collection_method'])) {
    $res_query->open_group('AND');
    foreach ($_GET['collection_method'] as $method) {
        $res_query->or('co.context_media LIKE ?', "%$method%");
    }
    $res_query->close_group();

    $count_query->open_group('AND');
    foreach ($_GET['collection_method'] as $method) {
        $count_query->or('co.context_media LIKE ?', "%$method%");
    }
    $count_query->close_group();
}

if (isset($_GET['collection_description'])) {
    $desc = $_GET['collection_description'];
    $res_query->and('co.context_description LIKE ?', "%$desc%");

    $desc = $_GET['collection_description'];
    $count_query->and('co.context_description LIKE ?', "%$desc%");
}

if (isset($_GET['project_title'])) {
    $res_query->and('d.data_project_title = ?', $_GET['project_title']);

    $count_query->and('d.data_project_title = ?', $_GET['project_title']);
}

if (isset($_GET['media'])) {
    $res_query->open_group('AND');
    foreach ($_GET['media'] as $media) {
        $res_query->or('d.data_type = ?', $media);
    }
    $res_query->close_group();

    $count_query->open_group('AND');
    foreach ($_GET['media'] as $media) {
        $count_query->or('d.data_type = ?', $media);
    }
    $count_query->close_group();
}

if (isset($_GET['description'])) {
    $desc = $_GET['description'];
    $res_query->and('d.data_description LIKE ?', "%$desc%");

    $desc = $_GET['description'];
    $count_query->and('d.data_description LIKE ?', "%$desc%");
}

$connection_count = get_connection();
$statement_count = $connection_count->prepare($count_query);
if (sizeof($count_query->params()) > 1) {
    call_user_func_array(array($statement_count, 'bind_param'), 
        $count_query->params());
}
$statement_count->execute();
$statement_count->bind_result($total);

while ($statement_count->fetch()) {
    $totalRows = $total;
}

$limit = 20;
if ((isset($_GET['rpp'])) && ($_GET['rpp'] > 0)) {
    $limit = $_GET['rpp'];
}

$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

unset($_GET['page']);
$pageUrl = http_build_query($_GET);

if ($page <= 0) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$lastPage = ceil($totalRows / $limit);

$prevPageNum = $page - 1;
$nextPageNum = $page + 1;

if (($lastPage == 0) || ($lastPage == 1)) {
    $prevPage = false;
    $nextPage = false;
} else {
    if ($page == 1) {
        $prevPage = false;
        $nextPage = "result_list.php?$pageUrl&page=$nextPageNum";
    } elseif ($page != $lastPage) {
        $prevPage = "result_list.php?$pageUrl&page=$prevPageNum";
        $nextPage = "result_list.php?$pageUrl&page=$nextPageNum";
    } else {
        $prevPage = "result_list.php?$pageUrl&page=$prevPageNum";
        $nextPage = false;
    }
}
$res_query->limit("$offset, $limit");

$connection = get_connection();
$statement = $connection->prepare($res_query);
if (sizeof($res_query->params()) > 1) {
    call_user_func_array(array($statement, 'bind_param'), $res_query->params());
}
$statement->execute();
$statement->bind_result($city, $date, $description, $projectTitle, $dataId, 
    $collectorId);

// return results as JSON
while ($statement->fetch()) {
    $split_description = explode(' ', $description);
    $first_words = array_slice($split_description, 0, 10);
    $results[] = array(
        "url" => "anonymized_data/$dataId",
        "city" => $city,
        "date" => $date,
        "description" => implode(' ', $first_words) . " ...",
        "projectTitle" => $projectTitle
   );
}
if (isset($results)) {
    echo json_encode(array(
        "prevPage" => $prevPage,
        "results" => $results,
        "nextPage" => $nextPage
       ));
} else {
    echo json_encode(array(
        "error" => "No Results Found",
       ));
}

