<?php

namespace Repositories;

class UniversitiesRepository implements RepositoryInterface
{
    /**
     * @var array
     */
    private $connector;

    /**
     * UniversitiesRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $connector
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM universities');
        $statement->execute();
        return $this->fetchUniversityData($statement);
    }

    /**
     * @param $statement
     * @return array
     */
    private function fetchUniversityData($statement)
    {
        $results = [];
        while ($result = $statement->fetch()) {
            $results[] = [
                'id' => $result['id'],
                'name' => $result['name'],
                'city' => $result['city'],
                'site' => $result['site'],
            ];
        }

        return $results;
    }

    /**
     * @param array $universityData
     * @return mixed
     */
    public function insert(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO universities (name, city, site) VALUES(:name, :city, :site)');
        $statement->bindValue(':name', $universityData['name']);
        $statement->bindValue(':city', $universityData['city']);
        $statement->bindValue(':site', $universityData['site']);

        return $statement->execute();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM universities WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        $universitiesData = $this->fetchUniversityData($statement);

        return $universitiesData[0];

    }

    /**
     * @param array $universityData
     * @return mixed
     */
    public function update(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare("UPDATE universities SET name = :name, city = :city, site = :site WHERE id = :id");

        $statement->bindValue(':name', $universityData['name'], \PDO::PARAM_STR);
        $statement->bindValue(':city', $universityData['city'], \PDO::PARAM_STR);
        $statement->bindValue(':site', $universityData['site'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $universityData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * @param array $universityData
     * @return mixed
     */
    public function remove(array $universityData)
    {
        $statement = $this->connector->getPdo()->prepare("DELETE FROM universities WHERE id = :id");

        $statement->bindValue(':id', $universityData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}
