<?php

namespace models\SQLQueries;

//include '../../src/assets/settings.php';

class DBConnectionModel {

    private $server;
    private $username;
    private $password;
    private $database;
    private $connection;

    /**
     * Constructor
     */
    public function __construct() {
      
        /* Servidor de pruebas */
  
        $this->server = '192.168.72.19';
        $this->username = 'sga';
        $this->password = '123456';
        $this->database = 'DbSga';

        /*$this->server = 'LAPTOP-7S6KJVFG';
        $this->username = 'sga';
        $this->password = '123456';
        $this->database = 'DbSga';*/
  
		
       /* 
		$this->server = 'DESKTOP-RRU98MU\SQLEXPRESS';
		$this->username = 'agc';
		$this->password = '123456';
		$this->database = 'sga_db';
		*/

        /*
        $this->server = 'DESKTOP-CV8D4C6\SQLEXPRESS';
		$this->username = 'admin';
		$this->password = '123456';
		$this->database = 'demo_db';
        */
		
    }

    /**
     * Crea la conexion con SQLServer
     * 
     * @return $this
     */
    public function createConnection() {

        $connectionOptions = [
            "Database" => $this->database,
            "CharacterSet" => "UTF-8",
            "Uid" => $this->username,
            "PWD" => $this->password
        ];

        $this->connection = sqlsrv_connect($this->server, $connectionOptions);

        if (!$this->connection) {
            die(print_r(sqlsrv_errors(), true));
        }

        return $this;
    }

    /**
     * 
     * Para hacer queries
     * 
     * @return DBConnection
     */
    public function getConnection() {
        return $this->connection;
    }
}
