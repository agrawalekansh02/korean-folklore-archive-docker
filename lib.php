<?php
include_once('dbconfig.php');
include_once('user.php');

define_passcode();

function get_connection() {
    $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($connection, "SET NAMES 'utf8'");
    return $connection;
}

$dbConn = get_connection();



function get_token($str) {
    return md5(SECRET . $str);
}

function get_user() {
    return array(
        'collector_id' => 1,
        'ucla_id' => 'jwan123',
        'name' => 'Jun Wan',
        'is_admin' => true
    );    
}

function get_auth_sql($and = true) {
    global $user;
    $and = ($and) ? ' and' : '';
    if ($user->is_admin()) return '';
    else return "$and collector_id=" . $user->get('id');
}

function get_collector_sql($and = true) {
    global $user;
    $and = ($and) ? ' and' : '';
    return "$and collector_id=" . $user->get('id');
}

function get_record($table, $id, $collector_id=false) {
    global $user, $dbConn;
    if (!$user->is_admin()) $collector_id = $user->get('id');
    $and = ($collector_id) ? "and collector_id=$collector_id" : '';

    $sql = "select * from $table where ${table}_id = $id $and";
    $result = mysqli_query($dbConn, $sql);
    if (!$result) return 'Query was unsuccesssful';
    if ($row=mysqli_fetch_assoc($result)) return $row;
    else return array();
}

function get_records($table, $collector_id=false) {
    global $user, $dbConn;
    if (!$user->is_admin()) $collector_id = $user->get('id');
    $and = ($collector_id) ? "and collector_id=$collector_id" : '';

    $sql = "select * from $table where 1 $and";
    $result = mysqli_query($dbConn, $sql);
    if (!$result) return array();
    $data = array();
    while ($row=mysqli_fetch_assoc($result)) $data[] = $row;
    return $data;
}

function check_auth() {
    global $user;
    if (isset($_COOKIE['kfl'])){
        if (!$user->auth) { exit('Not authorized'); }
        if (!$user->is_user()) { 
            header("Location: " . HOST . "collector"); 
            exit(); 
        }
    }
    else{
        header("Location: " . HOST . "login"); 
        exit(); 
    }
    return $user;
}

function define_passcode(){
    $passcode = file_get_contents('mini/passcode.txt', FILE_USE_INCLUDE_PATH);
    define('PASSCODE', $passcode );
}

function update_passcode($passcode){
    file_put_contents('mini/passcode.txt', $passcode);
}

function add_quarter($quarter){
    global $dbConn;

    $sql = "update quarter set is_current_quarter = 0";
    mysqli_query($dbConn, $sql);

    $sql = "insert into quarter(quarter_short_name, is_current_quarter) values (?, 1) on duplicate key update is_current_quarter = 1";
    $stmt = mysqli_prepare($dbConn, $sql);
    mysqli_stmt_bind_param($stmt,'s', $quarter);
    mysqli_stmt_execute($stmt);
    $current_quarter = mysqli_insert_id($stmt);
    mysqli_stmt_close($stmt);
    
    return $current_quarter;
}

function get_quarter_by_id($quarter_id){
    global $dbConn;

    $sql = "select upper(quarter_short_name) AS quarter_short_name from quarter where quarter_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);
    mysqli_stmt_bind_param($stmt,'i', $quarter_id);
    mysqli_stmt_execute($stmt); 
    mysqli_stmt_bind_result($stmt, $quarter_short_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $quarter_short_name;
}

function get_current_quarter(){
    global $dbConn;

    $sql2 = "select quarter_id from quarter where is_current_quarter = 1";
    $result2 = mysqli_query($dbConn, $sql2);
    $row2 = mysqli_fetch_array($result2);
    return $row2['quarter_id'];
}

/* switch user on and off admin */
function update_admin($uclalogonid, $admin=1){
    global $dbConn;

    $sql = "update collector set collector_status = ? where collector_sid = ?";
    $stmt = mysqli_prepare($dbConn, $sql);
    mysqli_stmt_bind_param($stmt,'ss', $admin, $uclalogonid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

/* find by ucla logon id */
function find_collector($uclalogonid){
    global $dbConn;

    $sql = "select collector_sid from collector where lower(collector_sid) = '". $uclalogonid . "'";
    $stmt = mysqli_prepare($dbConn, $sql);
    mysqli_stmt_bind_param($stmt,'s', $uclalogonid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $collector_sid);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $collector_sid;
}

function run_quarter_report($quarter_id){
    global $dbConn;

    $sql = "INSERT INTO report_history (quarter_id, active_collectors, new_consultants, new_contexts, new_data, total_data_size)
            values(
                ?, /* quarter */
                (SELECT COUNT(*) FROM
                    (SELECT collector_id FROM consultant WHERE consultant_quarter_created = ?
                     UNION
                    SELECT collector_id FROM context WHERE context_quarter_created = ?
                     UNION
                    SELECT collector_id FROM data WHERE data_quarter_created = ?) 
                AS all_collectors), /* total_active_collectors */
                (SELECT
                    count(consultant_id) 
                FROM consultant
                WHERE consultant_quarter_created = ?), /* total_new_consultants */
                (SELECT
                    count(context_id) AS total_new_contexts
                FROM context
                WHERE context_quarter_created = ?), /* total_new_contexts */
                (SELECT
                    count(data_id)
                FROM data
                WHERE data_quarter_created = ?), /* total_new_data */
                (SELECT
                    COALESCE(SUM(data_file_size), 0)
                FROM data
                WHERE data_quarter_created = ?) /* total_new_file_size */
            )";
    $stmt = mysqli_prepare($dbConn, $sql);
    mysqli_stmt_bind_param($stmt,'iiiiiiii', $quarter_id, $quarter_id, $quarter_id, $quarter_id, $quarter_id, $quarter_id, $quarter_id, $quarter_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function check_test(){
    return "hello";
}

