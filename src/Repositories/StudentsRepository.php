<?php

namespace Repositories;

class StudentsRepository implements RepositoryInterface
{
    private $connector;

    /**
     * StudentsRepository constructor.
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
            SELECT students.id, students.first_name, students.last_name, students.email,
            students.phone, departments.name AS departmentName,
            universities.name AS universityName FROM students
            JOIN departments ON students.department_id = departments.id
            JOIN universities ON departments.university_id = universities.id
        ');
        $statement->execute();
        return $this->fetchStudentData($statement);
    }

    private function fetchStudentData($statement)
    {
        $results = [];
        while ($result = $statement->fetch()) {
            $results[] = [
                'id' => $result['id'],
                'firstName' => $result['first_name'],
                'lastName' => $result['last_name'],
                'email' => $result['email'],
                'phone' => $result['phone'],
                'departmentName' => $result['departmentName'],
                'universityName' => $result['universityName'],
            ];
        }

        return $results;
    }

    public function insert(array $studentData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO students (first_name, last_name, email) VALUES(:firstName, :lastName, :email)');
        $statement->bindValue(':firstName', $studentData['first_name']);
        $statement->bindValue(':lastName', $studentData['last_name']);
        $statement->bindValue(':email', $studentData['email']);

        return $statement->execute();
    }

    public function find($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM students WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        $studentsData = $this->fetchStudentData($statement);

        return $studentsData[0];

    }

    public function update(array $studentData)
    {
        $statement = $this->connector->getPdo()->prepare("UPDATE students SET first_name = :firstName, last_name = :lastName, email = :email WHERE id = :id");

        $statement->bindValue(':firstName', $studentData['first_name'], \PDO::PARAM_STR);
        $statement->bindValue(':lastName', $studentData['last_name'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $studentData['email'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $studentData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(array $studentData)
    {
        $statement = $this->connector->getPdo()->prepare("DELETE FROM students WHERE id = :id");

        $statement->bindValue(':id', $studentData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}