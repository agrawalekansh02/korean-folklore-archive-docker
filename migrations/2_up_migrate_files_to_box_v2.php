<?php
$executionStartTime = microtime(true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once (__DIR__.'/../box-jwt-php/bootstrap/autoload.php');
require_once (__DIR__.'/../box-jwt-php/helpers/helpers.php');
require_once('../dbconfig.php');

use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Config\BoxConstants;
use Box\Models\Request\BoxFileRequest;

$boxJwt     = new BoxJWTAuth();
$boxConfig  = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();
$boxClient  = new BoxClient($boxConfig, $adminToken->access_token);
$boxFolderId = '';

$filePath = '../files2/';
$prefix = 'file';

//if run in cli
if(php_sapi_name()==="cli") {
    $newline = "\n";
    $tab = "\t";
} else {
    $newline = "<br>";
    $tab = "&nbsp;&nbsp;&nbsp;&nbsp;";
}
$newline_double = $newline.$newline;

date_default_timezone_set('America/Los_Angeles');
echo "Start Time: ".date('M j, Y g:i:s a').$newline_double;

echo "Begin migration...".$newline_double; 

$dbConn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$dbConn) {
    printf("Can't connect to localhost. Error: %s\n", mysqli_connect_error());
    exit();
}

$failedFiles = array();

//update database tables first
echo "Getting relevant files from database...".$newline_double;
//Get all files from files folder


$queryData = "SELECT 
                data_id AS id,
                collector_id AS collector_id,
                data_file_name AS origFileName,
                data_file_type AS fileType,
                data_file AS currentFileName
                FROM data where data_box_file_id is null and data_file != 0 and data_file IS NOT NULL";

$queryConsultant = "SELECT 
                    consultant_id AS id, 
                    collector_id AS collector_id, 
                    consultant_file_name AS origFileName, 
                    consultant_file_type AS fileType,
                    consultant_consent_form AS currentFileName 
                FROM consultant 
                WHERE consultant_box_file_id IS NULL 
                    AND consultant_consent_form != 0 
                    AND consultant_consent_form IS NOT NULL";

echo $queryData.$newline_double;
echo $queryConsultant.$newline_double;

//Data Table Query
$dataResult = mysqli_query($dbConn,$queryData);
$duplicateSearch = array();

$dataRows = array();
if ($dataResult) {
    while ($row = mysqli_fetch_assoc($dataResult)) {
        $dataRows[] = $row;
        $duplicateSearch[] = $row['currentFileName'];
    }
}

//Consultant Table Query
$consultantResult = mysqli_query($dbConn,$queryConsultant);
$consultantRows = array();
if ($consultantResult) {
    while ($row = mysqli_fetch_assoc($consultantResult)) {
      $consultantRows[] = $row;
      $duplicateSearch[] = $row['currentFileName'];
    }
}

if(empty($dataRows) && empty($consultantRows)){
    echo "No new files to upload.".$newline.' Exiting script.';
    exit();
}

echo "Forming and Uploading Files...".$newline;

echo 'Data Count: '.count($dataRows).$newline_double;

$updateDataTable = array();
$dataBoxIdQuery = '';
$dataBoxNameQuery = '';

foreach ($dataRows as $row) {

    $row['serverFileName'] = checkFileExists($row);

    if($row['serverFileName']){
        //upload each file to box 
        try {
            //name and id to array to update database
            $boxinfo = uploadFileToBox($row);

            $updateDataTable[] = $boxinfo;

            $dataBoxIdQuery .= " WHEN data_id = ".$row['id']." THEN ".$boxinfo['box_id'];
            $dataBoxNameQuery .= " WHEN data_id = ".$row['id']." THEN '".addslashes($boxinfo['box_name'])."'";

        } catch(Exception $e) {
            $failedFiles[] = $row['serverFileName'];
            echo $e.$newline;
        }
    }
}

echo 'Consultant Count: '.count($consultantRows).$newline_double;
$updateConsultantTable = array();
$consultantBoxIdQuery = '';
$consultantBoxNameQuery = '';
foreach ($consultantRows as $row) {

    $row['serverFileName'] = checkFileExists($row);

    if($row['serverFileName']){
        try {
            //name and id to array to update database
            $boxinfo = uploadFileToBox($row);
            $updateConsultantTable[] = $boxinfo;
            $consultantBoxIdQuery .= " WHEN consultant_id = ".$row['id']." THEN ".$boxinfo['box_id'];
            $consultantBoxNameQuery .= " WHEN consultant_id = ".$row['id']." THEN '".addslashes($boxinfo['box_name'])."'";
        } catch(Exception $e) {
            $failedFiles[] = $row['serverFileName'];
            echo $e.$newline;
        }
    }
}

echo $newline_double."Updating file information in database...".$newline_double;

// Set autocommit to off
mysqli_autocommit($dbConn,FALSE);

$updated = true;

if (count($updateDataTable) > 0) {
    
    $dataQuery = "UPDATE data 
                    SET data_box_file_id = 
                        CASE $dataBoxIdQuery ELSE data_box_file_id END
                    , data_file =
                        CASE $dataBoxNameQuery ELSE data_file END";

    echo $dataQuery.$newline_double;

    if (mysqli_query($dbConn, $dataQuery)) {
        $updated = true;
        echo "Data table update SUCCESS. Updated with new box ids.".$newline_double;
    } else {
        $updated = false;
        echo "Data table update FAILED.".$newline_double;
    }
}

if (count($updateConsultantTable) > 0 && $updated) {
    
    $consultantQuery = "UPDATE consultant 
                            SET consultant_box_file_id = 
                                CASE $consultantBoxIdQuery ELSE consultant_box_file_id END
                            , consultant_consent_form =
                                CASE $consultantBoxNameQuery ELSE consultant_consent_form END";

    echo $consultantQuery . $newline_double;

    if (mysqli_query($dbConn, $consultantQuery)) {
        $updated = true;
        echo "Consultant table update SUCCESS. Updated with new box ids.".$newline_double;
    } else {
        $updated = false;
        echo "Consultant table update FAILED.".$newline_double;
    }
}

if ($updated) {
    mysqli_commit($dbConn);
} else {
    echo 'Rolling back database update...'.$newline_double;
    mysqli_rollback($dbConn);
}

mysqli_close($dbConn);
    
//Write failed files list to file
if($updated && count($failedFiles) > 0) {

    $failedFilesLog = 'failedfiles_v2.txt';

    echo "Some files have failed to move to box...".$newline_double;

    $fileList = "Failed to move the following files to Box on " . date('m/d/Y') . ' at ' . date('h:i:s a') . "\n" . implode("\n", $failedFiles);

    if (file_exists($failedFilesLog)) {
        //prepend current contents
        $fileList .= "\n\n" . file_get_contents($failedFilesLog);
    }

    if(file_put_contents($failedFilesLog, $fileList)){
        echo "List of files saved in failedfiles.txt".$newline.$tab;
    } else {
        echo "List of files was unable to be written.".$newline.$tab; 
    }

    echo implode($newline, $failedFiles);
}

echo $newline_double."Script complete.";

$executionEndTime = microtime(true);
$seconds = (float)($executionEndTime - $executionStartTime);

echo "<br>This script took $seconds seconds to execute.";


function checkFileExists($row){

    GLOBAL $prefix, $filePath;

    $fileId = $row['currentFileName'];
    $origFileName = $row['origFileName'];

    $serverFile = $prefix.$fileId.$origFileName;
    
    if(file_exists($filePath.$serverFile)){
        return $serverFile;
    }
    else{
        return false;
    }
}

function uploadFileToBox($row){

    global $duplicateSearch, $boxFolderId, $boxClient, $filePath, $tab, $newline;

    $dbId = $row['id'];
    //$origFileName = $row['origFileName'];
    $currentFileName = $row['currentFileName'];
    //$fileType = $row['fileType'];
    $serverFileName = $row['serverFileName'];
    $boxFileName = $serverFileName;

    
    //if(count(array_keys($duplicateSearch, $currentFileName))>1){
        $boxFileName = $dbId.'_'.$boxFileName;
    //}

    echo $boxFileName . $newline;

    $fileRequest = new BoxFileRequest(['name' => $boxFileName, 'parent' => ['id' => $boxFolderId]]);
    $res         = $boxClient->filesManager->uploadFile($fileRequest, $filePath.$serverFileName);

    //var_dump($res);
    $uploadedFileObject = json_decode($res->getBody());

    //get id from box
    $uploadedFileId   = $uploadedFileObject->entries[0]->id;
    $uploadedFileName = $uploadedFileObject->entries[0]->name;

    //name and id to array to update database
    return array('box_id' => $uploadedFileId, 'box_name' => $uploadedFileName );
}

function deleteRecentlyUploaded($allUploadedFiles){
    
    global $boxFolderId, $boxClient, $tab, $newline, $newline_double;
    
    echo "Removing uploaded files...".$newline_double;

    $manuallyRemoveFromBox = array();
    foreach($allUploadedFiles as $file){

        $boxFileId = $file['box_id'];
        $boxFileName = $file['box_name'];

        echo $tab.$boxFileId.$newline;
        $res = $boxClient->filesManager->deleteFile($boxFileId);
        $status = $res->getStatusCode();

        //204 = successful delete
        if($status != 204) { 
            $ManuallyRemoveFromBox[] = $boxFileId . '   '. $boxFileName;
        }
    }
    
    if(count($manuallyRemoveFromBox)){
        echo "Please manually remove the following files from box before restarting migration script:".$newline.$tab;
        echo implode($newline.$tab, $manuallyRemoveFromBox);
    }

}