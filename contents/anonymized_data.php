<?php
ini_set('display_errors', 1);

require_once 'map_search/search/QuB/Factory.php';
require_once 'map_search/search/QuB/QualifiedQuery.php';
require_once 'map_search/search/QuB/SelectQuery.php';

require_once 'lib.php';
use QuB\Factory;

$query = Factory::select(
    'col.collector_gender AS collector_gender',
    'col.collector_age AS collector_age',
    'col.collector_edu_level AS collector_edu_level',
    'col.collector_occupation AS collector_occupation',
    'col.collector_language AS collector_language',
    'c.consultant_gender AS consultant_gender',
    'c.consultant_age AS consultant_age',
    'c.consultant_edu_level AS consultant_edu_level',
    'c.consultant_occupation AS consultant_occupation',
    'c.consultant_language AS consultant_language',
    'c.consultant_heritage AS consultant_heritage',
    'c.consultant_city AS consultant_city',
    'c.consultant_state AS consultant_state',
    'c.consultant_country AS consultant_country',
    'con.context_date AS context_date',
    'con.context_time AS context_time',
    'con.context_weather AS context_weather',
    'con.context_otherpresent_num AS context_otherpresent_num',
    'con.context_place AS context_place',
    'con.context_city AS context_city',
    'con.context_state AS context_state',
    'con.context_country AS context_country',
    'd.data_type AS data_type',
    'd.data_file_name AS data_file_name',
    'd.data_description AS data_description'
  )
->from('collector col', 'consultant c', 'context con', 'data d')
->where('col.collector_id = d.collector_id')
->and('c.consultant_id = d.consultant_id')
->and('con.context_id = d.context_id');

if (isset($data[0])) {
  $query->and('data_id = ?', $data[0]);

  $connection = get_connection();
  $statement = $connection->prepare($query);
  if (sizeof($query->params()) >= 1) {
    call_user_func_array(array($statement, 'bind_param'), $query->params());
  }
  $statement->execute();
  $statement->bind_result(
      $collector_gender,
      $collector_age,
      $collector_edu_level,
      $collector_occupation,
      $collector_language,
      $consultant_gender,
      $consultant_age,
      $consultant_edu_level,
      $consultant_occupation,
      $consultant_language,
      $consultant_heritage,
      $consultant_city,
      $consultant_state,
      $consultant_country,
      $context_date,
      $context_time,
      $context_weather,
      $context_otherpresent_num,
      $context_place,
      $context_city,
      $context_state,
      $context_country,
      $data_type,
      $data_file_name,
      $data_description
    );
  $statement->fetch();

  if ($collector_gender == 'M') {
    $collector_gender = 'Male';
  } else {
    $collector_gender = 'Female';
  }

  if ($consultant_gender == 'M') {
    $consultant_gender = 'Male';
  } else {
    $consultant_gender = 'Female';
  }

  date_default_timezone_set('US/Pacific');
  $context_date = date("F n, Y", strtotime($context_date));

  $collector_array = array_filter(
    array($collector_gender, $collector_age, $collector_edu_level,
      $collector_occupation, $collector_language), 'strlen');
  $collector_text = implode(', ', $collector_array);

  $consultant_array = array_filter(
    array($consultant_gender, $consultant_age, $consultant_edu_level, 
      $consultant_occupation, $consultant_language, $consultant_heritage,
      $consultant_city, $consultant_state, $consultant_country), 'strlen');
  $consultant_text = implode(', ', $consultant_array);

  $context_array_p1 = array_filter(
    array($context_date, $context_time, $context_weather), 'strlen');
  if($context_otherpresent_num != '') {
    $context_text_p2 = "People Present: " . $context_otherpresent_num
     . "<br/>";
  }
  $context_array_p3 = array_filter(array($context_place, $context_city, 
    $context_state, $context_country), 'strlen');
  $context_text = implode(', ', $context_array_p1) . "<br/>";
  if(isset($context_text_p2)) $context_text .= $context_text_p2;
  $context_text .= implode(', ', $context_array_p3);

  $data_array = array_filter(array($data_type, $data_file_name), 'strlen');
  $data_desc = str_replace("\n", "<br/>", $data_description);
  $data_text = implode(', ', $data_array) . "<br/>" . $data_desc;
}

?>
<div style="padding:25px;border-style:solid;border-width:2px;border-color:#C0C0C0">
  <h2 style="font-size:13px; font-weight:normal; margin:-10px 0px 10px 0px">
    <b style="font-size:16px">Results</b>
     (Crawl by date of collection, most recent list)
  </h2>
  <h3 style="font-size:18px;margin:0px 0px 2px 0px;"><u>Collector</u></h3>
  <p style="margin:0px 0px 10px 0px;">
    <?php 
      echo $collector_text; 
    ?>
  </p>
  <hr style="border-style:dotted; border-width:1px" />
  <h3 style="font-size:18px;margin:0px 0px 2px 0px;"><u>Consultant</u></h3>
  <p style="margin:0px 0px 10px 0px;">
    <?php 
      echo $consultant_text; 
    ?>
  </p>
  <hr style="border-style:dotted; border-width:1px" />
  <h3 style="font-size:18px;margin:0px 0px 2px 0px;"><u>Context</u></h3>
  <p style="margin:0px 0px 10px 0px;">
    <?php 
      echo $context_text; 
    ?>
  </p>
  <hr style="border-style:dotted; border-width:1px" />
  <h3 style="font-size:18px;margin:0px 0px 2px 0px;"><u>Data</u></h3>
  <p style="margin:0px 0px 10px 0px;">
    <?php 
      echo $data_text;
    ?>
  </p>
  <hr style="border-style:dotted; border-width:1px" />
</div>
