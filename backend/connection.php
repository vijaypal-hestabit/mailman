<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $base_url = $_SERVER['HTTP_ORIGIN'].'/hbmail/';
    class dbConnection
    {
        private $host = 'localhost';
        private $user = 'root';
        private $pass = 'hestabit';
        private $dbname = 'hestamail';
        public $conn;

        public function __construct()
        {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

            if ($this->conn->connect_error) {
                die("<h1>Database Connection Failed</h1>");
            } else {
                // echo "Database Connected Successfully";
            }
        }
    }
} else {
    $base_url = $_SERVER['HTTP_ORIGIN'].'/tse/hbmail/';
    class dbConnection
    {
        private $host = 'localhost';
        private $user = 'tse';
        private $pass = 'bPmtHasjyTJ2SgZJ';
        private $dbname = 'vijay_pal';
        public $conn;
        
        public function __construct()
        {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            
            if ($this->conn->connect_error) {
                die("<h1>Database Connection Failed</h1>");
            } else {
                // echo "Database Connected Successfully";
            }
        }
    }
}
