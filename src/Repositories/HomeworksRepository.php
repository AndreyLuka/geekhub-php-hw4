<?php

namespace Repositories;

class HomeworksRepository implements RepositoryInterface
{
    private $connector;

    /**
     * HomeworksRepository constructor.
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
            SELECT home_works.id, home_works.name AS homeworkName, subjects.name AS subjectName FROM home_works
            LEFT JOIN subjects ON home_works.subject_id = subjects.id
        ');
        $statement->execute();
        return $this->fetchHomeworkData($statement);
    }

    private function fetchHomeworkData($statement)
    {
        $results = [];
        while ($result = $statement->fetch()) {
            $results[] = [
                'id' => $result['id'],
                'name' => $result['homeworkName'],
                'subjectName' => $result['subjectName'],
            ];
        }

        return $results;
    }

    public function insert(array $homeworkData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO home_works (name) VALUES(:name)');
        $statement->bindValue(':name', $homeworkData['name']);

        return $statement->execute();
    }

    public function find($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM home_works WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        $homeworksData = $this->fetchHomeworkData($statement);

        return $homeworksData[0];

    }

    public function update(array $homeworkData)
    {
        $statement = $this->connector->getPdo()->prepare("UPDATE home_works SET name = :name WHERE id = :id");

        $statement->bindValue(':name', $homeworkData['name'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $homeworkData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(array $homeworkData)
    {
        $statement = $this->connector->getPdo()->prepare("DELETE FROM home_works WHERE id = :id");

        $statement->bindValue(':id', $homeworkData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}
