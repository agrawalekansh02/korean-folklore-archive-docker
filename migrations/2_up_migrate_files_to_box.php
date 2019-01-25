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

//holds the files with a numeric name and no extension
define('FILEPATH','../files/');

//holds the files with full name and extension
define('FILEPATH2','../files2/');

//if run in cli
if(php_sapi_name()==="cli") {
    $newline = "\n";
    $tab = "\t";
} else {
    $newline = "<br>";
    $tab = "&nbsp;&nbsp;&nbsp;&nbsp;";
}

define('NEWLINE', $newline);
define('NEWLINE_DOUBLE', $newline.$newline);
define('TAB', $tab);

date_default_timezone_set('America/Los_Angeles');

// ------------------ BEGIN MIGRATION ------------------

echo "Start Time: ".date('M j, Y g:i:s a').NEWLINE_DOUBLE;

$dbConn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$dbConn) {
    printf("Can't connect to localhost. Error: %s\n", mysqli_connect_error());
    exit();
}

echo "BEGIN MIGRATION...".NEWLINE_DOUBLE; 

//if database tables have not been updated yet
updateDatabaseTables();

//$failedFiles = array();

// Set autocommit to off
mysqli_autocommit($dbConn,FALSE);

$continueFirstBatch = true;

// ------- Start first batch -------

echo "First batch...".NEWLINE_DOUBLE; 

if(!file_exists(FILEPATH)){
    echo "File directory ".FILEPATH." does not exist.".NEWLINE.' Exiting script.';
    $continueFirstBatch = false;
}

if($continueFirstBatch){

    $relevantFiles = getFirstBatchFiles();

    if(empty($relevantFiles['dataRows']) && empty($relevantFiles['consultantRows'])){
        echo "No new files to upload.".NEWLINE_DOUBLE;
    }
    else{
        //upload files to box and update database
        $uploadFiles = uploadFiles($relevantFiles, FILEPATH);
        $updatedFirstBatch = $uploadFiles['updated'];
        $failedFilesFirstBatch = $uploadFiles['failedFiles'];
        //Write failed files list to file
        logFailedFiles($updatedFirstBatch, $failedFilesFirstBatch);
    }
}

// ------- End first batch --------
// ------ Start second batch ------

echo NEWLINE_DOUBLE;
echo "Second batch...".NEWLINE_DOUBLE; 

$relevantFiles = getSecondBatchFiles();


if(empty($relevantFiles['dataRows']) && empty($relevantFiles['consultantRows'])){
    echo "No new files to upload." .NEWLINE_DOUBLE;
}
else{
    //upload files to box and update database
    $uploadFiles = uploadFiles($relevantFiles, FILEPATH2);
    $updatedSecondBatch = $uploadFiles['updated'];
    $failedFilesSecondBatch = $uploadFiles['failedFiles'];
    //Write failed files list to file
    logFailedFiles($updatedSecondBatch, $failedFilesSecondBatch);
}

// ------ End second batch ------

mysqli_close($dbConn);

echo "Script complete.".NEWLINE_DOUBLE;

$executionEndTime = microtime(true);
$seconds = (float)($executionEndTime - $executionStartTime);

echo "<br>This script took $seconds seconds to execute.";

// ------------------ END MIGRATION ------------------

// -------------------- FUNCTIONS --------------------
function updateDatabaseTables(){

    global $dbConn; 

    //update database tables first
    $updatedTables = false;
    if ($result = mysqli_query($dbConn,"SHOW TABLES LIKE 'report_history'")) {
        if($result->num_rows == 1) {
            $updatedTables = true;
        }
    }

    //if they have not yet been updated
    if($updatedTables == false) {
        $dbschema = file_get_contents('2_up.sql');
        echo "Updating database tables...".NEWLINE_DOUBLE;

        if (mysqli_multi_query($dbConn,$dbschema)) {
            echo 'SUCCESS'.NEWLINE_DOUBLE;
        }
        else{
            echo 'FAIL'.NEWLINE_DOUBLE;
            exit();
        }
    }

    mysqli_close($dbConn);
    $dbConn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
}

function getFirstBatchFiles(){

    global $dbConn;

    echo "Getting relevant files...".NEWLINE_DOUBLE;

    //Get all files from files folder
    $files = array_diff(scandir(FILEPATH), array('.', '..', '.DS_Store'));

    $filesList = implode (", ", $files);

    echo "Searching for files to upload...".NEWLINE_DOUBLE;

    if (!$dbConn) {
        printf("Can't connect to localhost. Error: %s\n", mysqli_connect_error());
        exit();
    }

    $queryData = "SELECT 
                    data_id AS id, 
                    collector_id AS collector_id, 
                    data_file_name AS origFileName, 
                    data_file_type AS fileType,
                    data_file AS currentFileName 
                FROM data WHERE data_file in (".$filesList.")
                AND data_box_file_id IS NULL";

    $queryConsultant = "SELECT 
                        consultant_id AS id, 
                        collector_id AS collector_id, 
                        consultant_file_name AS origFileName, 
                        consultant_file_type AS fileType,
                        consultant_consent_form AS currentFileName 
                    FROM consultant 
                    WHERE consultant_consent_form in (".$filesList.")
                    AND consultant_box_file_id IS NULL";

    echo $queryData.NEWLINE_DOUBLE;
    echo $queryConsultant.NEWLINE_DOUBLE;

    //Data Table Query
    $dataResult = mysqli_query($dbConn,$queryData);

    $dataRows = array();
    if ($dataResult) {
        while ($row = mysqli_fetch_assoc($dataResult)) {
          $dataRows[] = $row;
        }
    }

    //Consultant Table Query
    $consultantResult = mysqli_query($dbConn,$queryConsultant);
    $consultantRows = array();
    if ($consultantResult) {
        while ($row = mysqli_fetch_assoc($consultantResult)) {
          $consultantRows[] = $row;
        }
    }

    return array('dataRows' => $dataRows, 'consultantRows' => $consultantRows);
}

function uploadFirstBatchFileToBox($row) {

    $dbId = $row['id'];
    $origFileName = $row['origFileName'];
    $currentFileName = $row['currentFileName'];
    $fileType = $row['fileType'];

    $info = pathinfo($origFileName);

    $validFileType = array('m4a','jpg','wma','wav','docx','pdf','MP3','mp3','png','3gp','doc','mov','amr','JPG','zip','WMA','do','m4v','3ga','mp4','ppt','mpeg','mpg','MOV','caf','txt','avi','pages','jpeg','aifc','flv','PNG','rtf','wve','gif','mP3','HEIC','webarchive','BMP','PDF','aif','j','jp','bmp','html');

    // get the filename without the extension
    if(!isset($info['extension']) || !in_array($info['extension'], $validFileType)){
        $fileExt = '';
        $fileBasename = $origFileName;
    }
    else{
        $fileExt = '.'.$info['extension'];
        $fileBasename = basename($origFileName,$fileExt);
    }

    //if cannot find extension from filename
    if($fileExt == ''){
        if($fileType == 'audio/mpeg'){
            $fileExt = '.mpeg';
        }
        else if($fileType == 'application/vnd.openxmlformats-officedocument.word'){
            $fileExt = '.doc';
        }
        else {
            $fileExt = ''; //can be anytype of file
        }
    }

    $finalBoxName = $fileBasename . '_' . $currentFileName . $fileExt;

    echo TAB . $currentFileName . TAB . $origFileName . TAB . $fileBasename . TAB . $fileExt . TAB . $finalBoxName . NEWLINE;

    return array(
        'boxFileName' => $finalBoxName, 
        'origFileName' => $currentFileName, 
        'origFilePath' => FILEPATH
    );
}

function getSecondBatchFiles(){

    global $dbConn;

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

    echo $queryData.NEWLINE_DOUBLE;
    echo $queryConsultant.NEWLINE_DOUBLE;

    //Data Table Query
    $dataResult = mysqli_query($dbConn,$queryData);

    $dataRows = array();
    if ($dataResult) {
        while ($row = mysqli_fetch_assoc($dataResult)) {
            $serverName = checkSecondBatchFileExists($row);
            if($serverName){
                $row['currentFileName'] = $serverName;
                $dataRows[] = $row;
            }
        }
    }

    //Consultant Table Query
    $consultantResult = mysqli_query($dbConn,$queryConsultant);
    $consultantRows = array();
    if ($consultantResult) {
        while ($row = mysqli_fetch_assoc($consultantResult)) {
            $serverName = checkSecondBatchFileExists($row);
            if($serverName){
                $row['currentFileName'] = $serverName;
                $consultantRows[] = $row;
            }
        }
    }

    return array('dataRows' => $dataRows, 'consultantRows' => $consultantRows);
}

function uploadSecondBatchFileToBox($row){

    global $boxFolderId, $boxClient;

    $dbId = $row['id'];

    $boxFileName = $dbId.'_'.$row['currentFileName'];
    
    echo $boxFileName . NEWLINE;

    return array(
        'boxFileName' => $boxFileName, 
        'origFileName' => $row['currentFileName'], 
        'origFilePath' => FILEPATH2
    );
}

function checkSecondBatchFileExists($row){

    $prefix = 'file';
    $fileId = $row['currentFileName'];
    $origFileName = $row['origFileName'];

    $serverFile = $prefix.$fileId.$origFileName;
    
    if(file_exists(FILEPATH2.$serverFile)){
        return $serverFile;
    }
    else{
        return false;
    }
}

function uplogit pFiles($relevantFiles, $batch){

    global $dbConn;

    echo "Uploading files...".NEWLINE;

    $updated = false;
    $failedFiles = array();

    //Data Table
    $updateDataTable = array();
    $dataBoxIdQuery = '';
    $dataBoxNameQuery = '';
    $dataRows = $relevantFiles['dataRows'];

    echo NEWLINE_DOUBLE.'Data Count: '.count($dataRows).NEWLINE_DOUBLE;

    foreach ($dataRows as $row) {

        //upload each file to box 
        try {
            //name and id to array to update database
            if($batch == FILEPATH){
                $preppedInfo = uploadFirstBatchFileToBox($row);
            }else if($batch == FILEPATH2){
                $preppedInfo = uploadSecondBatchFileToBox($row);
            }

            extract($preppedInfo);
            $boxinfo = uploadToBox($boxFileName, $origFileName, $origFilePath);
            $updateDataTable[] = $boxinfo;
            $dataBoxIdQuery .= " WHEN data_id = ".$row['id']." THEN ".$boxinfo['box_id'];
            $dataBoxNameQuery .= " WHEN data_id = ".$row['id']." THEN '".mysqli_real_escape_string($dbConn, $boxinfo['box_name'])."'";
        } catch(Exception $e) {
            $failedFiles[] = $row['currentFileName'];
            echo $e.NEWLINE;
        }
    }

    //Consultant Table
    $updateConsultantTable = array();
    $consultantBoxIdQuery = '';
    $consultantBoxNameQuery = '';
    $consultantRows = $relevantFiles['consultantRows'];

    echo NEWLINE_DOUBLE.'Consultant Count: '.count($consultantRows).NEWLINE_DOUBLE;

    foreach ($consultantRows as $row) {
        //upload each file to box 
        try {
            //name and id to array to update database
            if($batch == FILEPATH){
                $preppedInfo = uploadFirstBatchFileToBox($row);
            }else if($batch == FILEPATH2){
                $preppedInfo = uploadSecondBatchFileToBox($row);
            }

            extract($preppedInfo);
            $boxinfo = uploadToBox($boxFileName, $origFileName, $origFilePath);
            $updateConsultantTable[] = $boxinfo;
            $consultantBoxIdQuery .= " WHEN consultant_id = ".$row['id']." THEN ".$boxinfo['box_id'];
            $consultantBoxNameQuery .= " WHEN consultant_id = ".$row['id']." THEN '".mysqli_real_escape_string($dbConn, $boxinfo['box_name'])."'";
        } catch(Exception $e) {
            $failedFiles[] = $row['currentFileName'];
            echo $e.NEWLINE;
        }
    }

    echo NEWLINE_DOUBLE."Updating file information in database...".NEWLINE_DOUBLE;

    if (count($updateDataTable) > 0) {
        
        $dataQuery = "UPDATE data 
                        SET data_box_file_id = 
                            CASE $dataBoxIdQuery ELSE data_box_file_id END
                        , data_file =
                            CASE $dataBoxNameQuery ELSE data_file END";

        echo $dataQuery.NEWLINE_DOUBLE;

        if (mysqli_query($dbConn, $dataQuery)) {
            $updated = true;
            echo "Data table update SUCCESS. Updated with new box ids.".NEWLINE_DOUBLE;
        } else {
            $updated = false;
            echo "Data table update FAILED.".NEWLINE_DOUBLE;
        }
    }

    if (count($updateConsultantTable) > 0 && $updated) {
        
        $consultantQuery = "UPDATE consultant 
                                SET consultant_box_file_id = 
                                    CASE $consultantBoxIdQuery ELSE consultant_box_file_id END
                                , consultant_consent_form =
                                    CASE $consultantBoxNameQuery ELSE consultant_consent_form END";

        echo $consultantQuery . NEWLINE_DOUBLE;

        if (mysqli_query($dbConn, $consultantQuery)) {
            $updated = true;
            echo "Consultant table update SUCCESS. Updated with new box ids.".NEWLINE_DOUBLE;
        } else {
            $updated = false;
            echo "Consultant table update FAILED.".NEWLINE_DOUBLE;
        }
    }


    if ($updated) {
        mysqli_commit($dbConn);
    } else {
        echo 'Rolling back database update...'.NEWLINE_DOUBLE;
        mysqli_rollback($dbConn);
        $allUploadedFiles = array_merge($updateDataTable, $updateConsultantTable);
        deleteRecentlyUploaded($allUploadedFiles);
    }

    return array('updated' => $updated, 'failedFiles' => $failedFiles);
}

function uploadToBox($boxFileName, $origFileName, $origFilePath){

    global $boxFolderId, $boxClient;

    $fileRequest = new BoxFileRequest(['name' => $boxFileName, 'parent' => ['id' => $boxFolderId]]);
    $res         = $boxClient->filesManager->uploadFile($fileRequest, $origFilePath.$origFileName);
    $uploadedFileObject = json_decode($res->getBody());

    //get id from box
    $uploadedFileId   = $uploadedFileObject->entries[0]->id;
    $uploadedFileName = $uploadedFileObject->entries[0]->name;

    //name and id to array to update database
    return array('box_id' => $uploadedFileId, 'box_name' => $uploadedFileName );
}

function logFailedFiles($updated, $failedFiles){

    if($updated && count($failedFiles) > 0) {

        $failedFilesLog = 'failedfiles.txt';

        echo "Some files have failed to move to box...".NEWLINE_DOUBLE;

        $fileList = "Failed to move the following files to Box on " . date('m/d/Y') . ' at ' . date('h:i:s a') . "\n" . implode("\n", $failedFiles);

        if (file_exists($failedFilesLog)) {
            //prepend current contents
            $fileList .= "\n\n" . file_get_contents($failedFilesLog);
        }

        if(file_put_contents($failedFilesLog, $fileList)){
            echo "List of files saved in failedfiles.txt".NEWLINE.TAB;
        } else {
            echo "List of files was unable to be written.".NEWLINE.TAB; 
        }

        echo implode(NEWLINE.TAB, $failedFiles);
    }
}

function deleteRecentlyUploaded($allUploadedFiles){
    
    global $boxFolderId, $boxClient;
    
    echo "Removing uploaded files...".NEWLINE_DOUBLE;

    $manuallyRemoveFromBox = array();
    foreach($allUploadedFiles as $file){

        $boxFileId = $file['box_id'];
        $boxFileName = $file['box_name'];

        echo TAB.$boxFileId.NEWLINE;
        $res = $boxClient->filesManager->deleteFile($boxFileId);
        $status = $res->getStatusCode();

        //204 = successful delete
        if($status != 204) { 
            $ManuallyRemoveFromBox[] = $boxFileName;
        }
    }
    
    if(count($manuallyRemoveFromBox)){
        echo "Please manually remove the following files from box before restarting migration script:".NEWLINE.TAB;
        echo implode(NEWLINE.TAB, $manuallyRemoveFromBox);
    }
}