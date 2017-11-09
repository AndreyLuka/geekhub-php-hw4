<?php

namespace Repositories;

class Connector
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * StudentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param string $dbname
     * @param string $user
     * @param string $pass
     */
    public function __construct($dbname, $user, $pass)
    {
        $this->pdo = new \PDO('mysql:host=localhost;dbname=' . $dbname . ';charset=UTF8', $user, $pass);
        if (!$this->pdo) {
            return false;
        }

    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}
