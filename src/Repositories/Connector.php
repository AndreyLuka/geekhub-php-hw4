<?php

namespace Repositories;

class Connector
{
    private $pdo;

    /**
     * StudentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $dbname
     * @param $user
     * @param $pass
     */
    public function __construct($dbname, $user, $pass)
    {
        $this->pdo = new \PDO('mysql:host=localhost;dbname=' . $dbname . ';charset=UTF8', $user, $pass);
        if (!$this->pdo) {
            return false;
        }

    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
