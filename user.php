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
                $sql = "select * from collector where collector_sid = '$id' limit 1";
                $result = mysql_query($sql);
                if ($row = mysql_fetch_assoc($result)) {
                    foreach ($row as $k => $v) {
                        $this->data[preg_replace('/collector_/','',$k)] = $v;
                    }
                }
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

?>