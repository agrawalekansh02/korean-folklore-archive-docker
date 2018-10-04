<?php
class User {
    private $data = array();
    public $auth = false;
    function __construct() {
        if (isset($_COOKIE['kfl'])) {
            $md5 = substr($_COOKIE['kfl'], 0 , 32);
            $id = substr($_COOKIE['kfl'], 32);
            if (get_token($id) == $md5) {
                $this->auth = $id;
                $dbConn = get_connection();
                $sql = "SELECT 
                            collector_id, 
                            collector_last_name, 
                            collector_first_name,
                            collector_initial,
                            collector_sid,
                            collector_email,
                            collector_street,
                            collector_city,
                            collector_state,
                            collector_zipcode,
                            collector_country,
                            collector_age,
                            collector_dob,
                            collector_gender,
                            collector_marital_status,
                            collector_occupation,
                            collector_edu_level,
                            collector_heritage,
                            collector_language,
                            collector_status
                        FROM collector WHERE collector_sid = '$id' LIMIT 1";
                $result = mysqli_query($dbConn, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
                    foreach ($row as $k => $v) {
                        $this->data[preg_replace('/collector_/','',$k)] = $v;
                    }
                }
                mysqli_close($dbConn);
            } else {
                setcookie('kfl','',time() - 3600,'/');
                exit('Invalid token.');
            }
        }
    }
    public function is_admin() {
        return ($this->get('status') == 2) ? true : false;
    }
    public function is_user() {
        return ($this->get('status') > 0) ? true : false;
    }
    public function get($field) {
        if (isset($this->data[$field])) return $this->data[$field];
        else return false;
    }
    public function show() {
        echo '<pre>'; print_r($this->data); echo '</pre>';
        //exit();
    }
}

$user = new User();
