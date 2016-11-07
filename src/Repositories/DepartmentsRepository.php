<?php

namespace Repositories;

class DepartmentsRepository implements RepositoryInterface
{
    private $connector;

    /**
     * DepartmentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $connector
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
    }

    public function findAll()
    {
        $statement = $this->connector->getPdo()->prepare('
            SELECT departments.id, departments.name AS departmentName,
            universities.name AS universityName FROM departments
            JOIN universities ON departments.university_id = universities.id
        ');
        $statement->execute();
        return $this->fetchUniversityData($statement);
    }

    private function fetchUniversityData($statement)
    {
        $results = [];
        while ($result = $statement->fetch()) {
            $results[] = [
                'id' => $result['id'],
                'departmentName' => $result['departmentName'],
                'universityName' => $result['universityName'],
            ];
        }

        return $results;
    }

    public function insert(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO departments (name) VALUES(:name)');
        $statement->bindValue(':name', $universityData['name']);

        return $statement->execute();
    }

    public function find($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM departments WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        $departmentsData = $this->fetchUniversityData($statement);

        return $departmentsData[0];

    }

    public function update(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare("UPDATE departments SET name = :name WHERE id = :id");

        $statement->bindValue(':name', $universityData['name'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $universityData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare("DELETE FROM departments WHERE id = :id");

        $statement->bindValue(':id', $universityData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}
