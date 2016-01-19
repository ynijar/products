<?php

/**
 * Class ProductsService
 */
class ProductsService
{

    public static function getAll()
    {
        $response = new Response;
        try {
            $response->data = Products::find()->toArray();
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

    public static function findByName($name)
    {
        $response = new Response;
        if (!$name) {
            $response->status = $response::STATUS_BAD_REQUEST;
            $response->addError('NAME_IS_REQUIRED');
            return $response->toArray();
        }
        try {
            $response->data = Products::find([
                'conditions' => 'name LIKE :name:',
                'bind' => ['name' => '%' . $name . '%'],
                'order' => 'name DESC',
            ])->toArray();
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

    public static function findOneById($id)
    {
        $response = new Response;
        try {
            $response->data = Products::findFirst($id)->toArray();
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

    public static function delete($id)
    {
        $response = new Response;
        try {
            $response->data = Products::findFirst($id)->delete();
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

    public static function update($id, $data)
    {
        $response = new Response;
        try {
            $data = json_decode($data, true);
            $category = Categories::findFirst($data->categoryId);
            if($category !== FALSE){
                /** @var Products $products */
                $product = Products::findFirst($id);
                $product->setAttributes($data);
                $response->data = $product->update();
            }else{
                $response->status = $response::STATUS_BAD_REQUEST;
                $response->addError('CATEGORIES_NOT_FOUND');
            }
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

    public static function create($data)
    {
        $response = new Response;
        try {
            $data = json_decode($data, true);
            $category = Categories::findFirst($data->categoryId);
            if($category !== FALSE){
                /** @var Products $products */
                $product = new Products;
                $product->setAttributes($data);
                $response->data = $product->create();
                $response->status = $response::STATUS_CREATED;
            }else{
                $response->status = $response::STATUS_BAD_REQUEST;
                $response->addError('CATEGORIES_NOT_FOUND');
            }
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

}