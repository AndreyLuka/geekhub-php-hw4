<?php

namespace Controllers;

use Repositories\Connector;

class ConfigController
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var \Twig_Loader_Filesystem
     */
    private $loader;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * ConfigController constructor.
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    /**
     * @return string
     */
    public function indexAction()
    {
        return $this->twig->render('config.html.twig');
    }

    /**
     * @return string
     */
    public function createAction() {
        $statement = $this->connector->getPdo()->prepare('
            CREATE TABLE `departments` (
              `id` int(11) NOT NULL,
              `name` char(80) DEFAULT NULL,
              `university_id` int(10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `departments` (`id`, `name`, `university_id`) VALUES
            (1, \'Department Name 1\', 1),
            (2, \'Department Name 2\', 1),
            (3, \'Department Name 3\', 2),
            (4, \'Department Name 4\', 2);
            
            CREATE TABLE `home_works` (
              `id` int(11) NOT NULL,
              `name` char(100) NOT NULL,
              `subject_id` int(10) NOT NULL,
              `passed` tinyint(1) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `home_works` (`id`, `name`, `subject_id`, `passed`) VALUES
            (1, \'Home Work 1\', 1, 1),
            (2, \'Home Work 2\', 2, 1),
            (3, \'Home Work 3\', 3, 1),
            (4, \'Home Work 4\', 4, 1),
            (5, \'Home Work 5\', 1, 1);
            
            CREATE TABLE `students` (
              `id` int(11) NOT NULL,
              `first_name` char(60) NOT NULL,
              `last_name` char(60) NOT NULL,
              `email` char(60) NOT NULL,
              `phone` char(60) NOT NULL,
              `department_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `phone`, `department_id`) VALUES
            (1, \'Student First Name 1\', \'Student Last Name 1\', \'student1@example.com\', \'063 000 00 01\', 1),
            (2, \'Student First Name 2\', \'Student Last Name 2\', \'student2@example.com\', \'063 000 00 02\', 2),
            (3, \'Student First Name 3\', \'Student Last Name 3\', \'student3@example.com\', \'063 000 00 03\', 3),
            (4, \'Student First Name 4\', \'Student Last Name 4\', \'student4@example.com\', \'063 000 00 04\', 4);
            
            CREATE TABLE `students_home_works` (
              `student_id` int(11) NOT NULL,
              `home_work_id` int(11) NOT NULL,
              `passed` tinyint(1) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `students_home_works` (`student_id`, `home_work_id`, `passed`) VALUES
            (1, 2, 1),
            (1, 1, 1),
            (2, 4, 1),
            (3, 4, 0);
            
            CREATE TABLE `subjects` (
              `id` int(11) NOT NULL,
              `name` char(80) NOT NULL,
              `department_id` int(10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `subjects` (`id`, `name`, `department_id`) VALUES
            (1, \'Subject Name 1\', 1),
            (2, \'Subject Name 2\', 2),
            (3, \'Subject Name 3\', 3),
            (4, \'Subject Name 4\', 4);
            
            CREATE TABLE `teachers` (
              `id` int(11) NOT NULL,
              `first_name` char(60) NOT NULL,
              `last_name` char(60) NOT NULL,
              `department_id` int(10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `teachers` (`id`, `first_name`, `last_name`, `department_id`) VALUES
            (1, \'Teacher First Name 1\', \'Teacher Last Name 1\', 1),
            (2, \'Teacher First Name 2\', \'Teacher Last Name 2\', 2),
            (3, \'Teacher First Name 3\', \'Teacher Last Name 3\', 3),
            (4, \'Teacher First Name 4\', \'Teacher Last Name 4\', 4);
            
            CREATE TABLE `universities` (
              `id` int(11) NOT NULL,
              `name` char(100) DEFAULT NULL,
              `city` char(60) DEFAULT NULL,
              `site` char(60) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
            INSERT INTO `universities` (`id`, `name`, `city`, `site`) VALUES
            (1, \'University Name 1\', \'University City 1\', \'http://university-link-1.ua/\'),
            (2, \'University Name 2\', \'University City 2\', \'http://university-link-2.ua/\');
            
            ALTER TABLE `departments`
              ADD PRIMARY KEY (`id`),
              ADD KEY `university_id` (`university_id`);
            
            ALTER TABLE `home_works`
              ADD PRIMARY KEY (`id`),
              ADD KEY `subject_id` (`subject_id`);
            
            ALTER TABLE `students`
              ADD PRIMARY KEY (`id`),
              ADD KEY `department_id` (`department_id`);
            
            ALTER TABLE `students_home_works`
              ADD KEY `home_work_id` (`home_work_id`),
              ADD KEY `student_id` (`student_id`) USING BTREE;
            
            ALTER TABLE `subjects`
              ADD PRIMARY KEY (`id`),
              ADD KEY `department_id` (`department_id`);
            
            ALTER TABLE `teachers`
              ADD PRIMARY KEY (`id`),
              ADD KEY `department_id` (`department_id`);
            
            ALTER TABLE `universities`
              ADD PRIMARY KEY (`id`);
            
            ALTER TABLE `departments`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
            
            ALTER TABLE `home_works`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
            
            ALTER TABLE `students`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
            
            ALTER TABLE `subjects`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
            
            ALTER TABLE `teachers`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
            
            ALTER TABLE `universities`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
            
            ALTER TABLE `departments`
              ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);
            
            ALTER TABLE `home_works`
              ADD CONSTRAINT `home_works_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE;
            
            ALTER TABLE `students`
              ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;
            
            ALTER TABLE `students_home_works`
              ADD CONSTRAINT `students_home_works_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
              ADD CONSTRAINT `students_home_works_ibfk_2` FOREIGN KEY (`home_work_id`) REFERENCES `home_works` (`id`);
            
            ALTER TABLE `subjects`
              ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;
            
            ALTER TABLE `teachers`
              ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;
        ');
        $statement->execute();
        if ($statement) {
            return $this->twig->render('config.html.twig', ['statement' => $statement]);
        }
    }
}
