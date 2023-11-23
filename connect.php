<?php

class Connect
{
    private $localhost = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db_name = 'db_api_crud_campus';

    protected $mysqli;
    protected $stmt;
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        $this->mysqli = new mysqli($this->localhost, $this->username, $this->password, $this->db_name);

        if ($this->mysqli->connect_error) {
            die('Opps!, Connection Failed');
        }
    }
}

new Connect();
