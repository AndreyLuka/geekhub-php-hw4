<?php

namespace Controllers;

use Repositories\HomeworksRepository;

class HomeworksController
{
    private $repository;

    private $loader;

    private $twig;

    public function __construct($connector)
    {
        $this->repository = new HomeworksRepository($connector);
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    public function indexAction()
    {
        $homeworksData = $this->repository->findAll();

        return $this->twig->render('homeworks.html.twig', ['homeworks' => $homeworksData]);
    }

    public function newAction()
    {
        if (isset($_POST['name'])) {
            $this->repository->insert(
                [
                    'name' => $_POST['name'],
                ]
            );
            return $this->indexAction();
        }
        return $this->twig->render('homeworks_form.html.twig',
            [
                'name' => '',
            ]
        );
    }

    public function editAction()
    {
        if (isset($_POST['name'])) {
            $this->repository->update(
                [
                    'name' => $_POST['name'],
                    'id'         => (int) $_GET['id'],
                ]
            );
            return $this->indexAction();
        }
        $homeworkData = $this->repository->find((int) $_GET['id']);
        return $this->twig->render('homeworks_form.html.twig',
            [
                'name' => $homeworkData['name'],
            ]
        );
    }

    public function deleteAction()
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $this->repository->remove(['id' => $id]);
            return $this->indexAction();
        }
        return $this->twig->render('homeworks_delete.html.twig', array('homework_id' => $_GET['id']));
    }
}
