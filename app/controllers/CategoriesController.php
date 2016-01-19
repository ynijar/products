<?php

/**
 * Class CategoriesController
 */
class CategoriesController extends RestController
{

    public function listAction()
    {
        return CategoriesService::getAll();
    }

    public function searchAction($name)
    {
        return CategoriesService::findByName($name);
    }

    public function findOneByIdAction($id)
    {
        return CategoriesService::findOneById($id);
    }

    public function deleteAction($id)
    {
        return CategoriesService::delete($id);
    }

    public function updateAction($id)
    {
        return CategoriesService::update($id, $this->request->getRawBody());
    }

    public function createAction()
    {
        return CategoriesService::create($this->request->getRawBody());
    }

}

