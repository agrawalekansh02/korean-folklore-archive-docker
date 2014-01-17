<?php
ini_set('display_errors', 1);

require_once 'util.php';

require_once 'Factory.php';
require_once 'QualifiedQuery.php';
require_once 'SelectQuery.php';

require_once '../../lib.php';

use QuB\Factory;

$res_query = Factory::select('co.context_city AS city', 'co.context_date AS date', 'SUBSTRING(da.data_description,1,20) AS description', 'da.data_project_title AS projectTitle', 'da.data_id AS dataId', 'da.collector_id AS collectorId')
->from('context co', 'data da')
->where("co.context_id = da.context_id");

$count_query = Factory::select('count(*) AS totalRows')
->from('context co', 'data da')
->where("co.context_id = da.context_id");

if (isset($_GET['context_bbox'])) {
    $bbox = $_GET['context_bbox'];
    $bbox = explode(",", $bbox);
    $polygon = "GeomFromText('Polygon(($bbox[0] $bbox[1],$bbox[0] $bbox[3],$bbox[2] $bbox[3],$bbox[2] $bbox[1],$bbox[0] $bbox[1]))')";
    $res_query->and("MBRContains($polygon,co.context_spatial_point)=1");
    $count_query->and("MBRContains($polygon,co.context_spatial_point)=1");
}

$connection_count = get_connection();
$statement_count = $connection_count->prepare($count_query);
if (sizeof($count_query->params()) > 1) {
    call_user_func_array(array($statement_count, 'bind_param'), $count_query->params());
}
$statement_count->execute();
$statement_count->bind_result($total);

while ($statement_count->fetch()) {
    $totalRows=$total;
}

$limit = 20;

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if($page <= 0) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$lastPage = ceil($totalRows/$limit);

$prevPageNum = $page - 1;
$nextPageNum = $page + 1;

if(($lastPage == 0) or ($lastPage == 1)) {
    $prevPage = false;
    $nextPage = false;
} else {
    if ($page == 1) {
        $prevPage = false;
        $nextPage = "result_list.php?bbox=" . $_GET['bbox'] . "&page=" . $nextPageNum;
    } elseif ($page != $lastPage) {
        $prevPage = "result_list.php?bbox=" . $_GET['bbox'] . "&page=" . $prevPageNum;
        $nextPage = "result_list.php?bbox=" . $_GET['bbox'] . "&page=" . $nextPageNum;
    } else {
        $prevPage = 'result_list.php?bbox=' . $_GET['bbox'] . '&page=' . $prevPageNum;
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
$statement->bind_result($city, $date, $description, $projectTitle, $dataId, $collectorId);

// return results as JSON
while ($statement->fetch()) {
    #print "Another ";
    $results[] = array(
        "url" => "../../data/$dataId/$collectorId",
        "city" => $city,
        "date" => $date, 
        "description" => $description, 
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
?>
