<?php

/**
 * Class ProductsController
 */
class ProductsController extends RestController
{

    public function listAction()
    {
        return ProductsService::getAll();
    }

    public function searchAction($name)
    {
        return ProductsService::findByName($name);
    }

    public function findOneByIdAction($id)
    {
        return ProductsService::findOneById($id);
    }

    public function deleteAction($id)
    {
        return ProductsService::delete($id);
    }

    public function updateAction($id)
    {
        return ProductsService::update($id, $this->request->getRawBody());
    }

    public function createAction()
    {
        return ProductsService::create($this->request->getRawBody());
    }

}

