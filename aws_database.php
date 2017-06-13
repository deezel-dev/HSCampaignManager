<?php

class Database {


        //public $con;
        //public $host;
        //public $user;
        //public $pwrd;
        //public $database;
        //public $serverName;
        //public $connectionOptions;

        public $con;
        public $serverName;
        public $connectionOptions;
        public $host = '(local)';
        public $user = 'web_user';
        public $pwrd = 'h@rrisseeds2017';
        public $database = 'CampaignManagement';

        function __construct($host, $connectionOptions){
            //cut from signature - , $user, $pwrd, $database
            //$this->user = $user;
            //$this->pwrd = $pwrd;
            //$this->database = $database;
            $this->host = $host;
            $this->connectionOptions = $connectionOptions;

            ini_set('display_errors',1);
            ini_set('display_startup_errors',1);
            error_reporting(1);

        }

        public function close() {
        sqlsrv_close($this->con);
    }

    public function execute($sql){
        sqlsrv_select_db($this->database);
        return $result = sqlsrv_query($sql);
    }

    public function open(){

            $connected = true;
            $this->con=sqlsrv_connect($this->host, $this->connectionOptions);

            //mysql_connect
            if ($this->con==false) {
                $connected = false;
                echo "Failed to connect to SQL: ";

                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                    }
                }

            }

            return $connected;

        }




        public function getData($tsql) {

            $myArr[] = null;

            try  {
                $conn = $this->con;
                $getObjects = sqlsrv_query($conn, $tsql);
                if ($getObjects == FALSE){
                    if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                    }
                }
                }

                $objectCount = 0;

                while($row = sqlsrv_fetch_array($getObjects, SQLSRV_FETCH_ASSOC)){
                    $myArr[] = $row;
                }

                sqlsrv_free_stmt($getObjects);
                sqlsrv_close($conn);

            } catch(Exception $e) {
                echo("Error!");
            }

            return $myArr;
        }

        public function insertData($sql){


            $retVal = false;
            $conn = $this->con;

            $result = sqlsrv_query($this->con, $sql);

            if(!$result) {

                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                    }
                }

                return $result;
                exit;

            } else {
                $retVal = true;
            }

            sqlsrv_free_stmt($result);
            sqlsrv_close($conn);

            return $retVal;

        }

        public function updateData($sql){

        $retVal = false;
        $conn = $this->con;

            $result = sqlsrv_query($this->con, $sql);
            if(!$result) {

                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        $err .= "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        $err .= "code: ".$error[ 'code']."<br />";
                        $err .= "message: ".$error[ 'message']."<br />";
                    }
                }

                return $err;
                exit;

            } else {
                $retVal = true;
            }

            sqlsrv_free_stmt($result);
            sqlsrv_close($conn);

            return $retVal;

        }

}

    function getDatabase(){

      $host = '(local)';
      $user = 'web_user';
      $pwrd = 'h@rrisseeds2017';
      $database = 'CampaignManagement';

      $serverName = $host;
      $connectionOptions = array("Database"=>$database,
                                 "UID"=>$user, "PWD"=>$pwrd);
      $data = new Database($serverName, $connectionOptions);
      //$data = new Database('deezel-dev.cloudapp.net', 'webuser', 'P@ssw0rd928', 'OneWord');
      return  $data;
    }






?>
