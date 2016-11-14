<?php

namespace Controllers;

use Repositories\UniversitiesRepository;

class UniversitiesController
{
    /**
     * @var UniversitiesRepository
     */
    private $repository;

    /**
     * @var \Twig_Loader_Filesystem
     */
    private $loader;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * UniversitiesController constructor.
     * @param array $connector
     */
    public function __construct($connector)
    {
        $this->repository = new UniversitiesRepository($connector);
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
        $universitiesData = $this->repository->findAll();

        return $this->twig->render('universities.html.twig', ['universities' => $universitiesData]);
    }

    /**
     * @return string
     */
    public function newAction()
    {
        if (isset($_POST['name'])) {
            $this->repository->insert(
                [
                    'name' => $_POST['name'],
                    'city'  => $_POST['city'],
                    'site'      => $_POST['site'],
                ]
            );
            return $this->indexAction();
        }
        return $this->twig->render('universities_form.html.twig',
            [
                'name' => '',
                'city' => '',
                'site' => '',
            ]
        );
    }

    /**
     * @return string
     */
    public function editAction()
    {
        if (isset($_POST['name'])) {
            $this->repository->update(
                [
                    'name' => $_POST['name'],
                    'city'  => $_POST['city'],
                    'site'      => $_POST['site'],
                    'id'         => (int) $_GET['id'],
                ]
            );
            return $this->indexAction();
        }
        $universityData = $this->repository->find((int) $_GET['id']);
        return $this->twig->render('universities_form.html.twig',
            [
                'name' => $universityData['name'],
                'city' => $universityData['city'],
                'site' => $universityData['site'],
            ]
        );
    }

    /**
     * @return string
     */
    public function deleteAction()
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $this->repository->remove(['id' => $id]);
            return $this->indexAction();
        }
        return $this->twig->render('universities_delete.html.twig', array('university_id' => $_GET['id']));
    }
}
