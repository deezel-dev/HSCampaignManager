<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/db-config.php";

foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_") !== 0) {
        continue;
    }

    $GLOBALS['DB_HOST'] = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $GLOBALS['DB_USER'] = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $GLOBALS['DB_PASSWORD'] = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
    $GLOBALS['DB_NAME'] = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
 
    
    break;
}

class Database {
    public $con;
    public $host;
    public $user;
    public $pwrd;
    public $database;

    function __construct($host, $user, $pwrd, $database){
        $this->host = $host;
        $this->user = $user;
        $this->pwrd = $pwrd;
        $this->database = $database;
    }


    public function open(){
        $connected = true;
        $this->con=mysql_connect($this->host, $this->user, $this->pwrd, $this->database);

        if (mysql_error()) {
            $connected = false;
            echo "Failed to connect to MySQL: " . mysql_connect_error();
        }

        return $connected;
    }
    
    public function close() {
        mysql_close($this->con);
    }

    public function getData($sql){
        $myArr = array();

        mysql_select_db($this->database);

        $result = mysql_query($sql);

        if($result){
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $myArr[] = $row;
            }
            mysql_free_result($result);
        }

        return $myArr;
    }

    public function insertData($sql){
        mysql_select_db($this->database);
        return $result = mysql_query($sql);
    }
    
    public function execute($sql){
        mysql_select_db($this->database);
        return $result = mysql_query($sql);
    }
}

function getDatabase(){
    $data = new Database($GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD'], $GLOBALS['DB_NAME']);
    return  $data;
}
?>