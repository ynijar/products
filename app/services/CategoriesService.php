<?php
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

/**
 * Class CategoriesService
 */
class CategoriesService
{

    public static function getAll()
    {
        $response = new Response;
        try {
            $response->data = Categories::find()->toArray();
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

    public static function findByName($name)
    {
        $response = new Categories;
        if (!$name) {
            $response->status = $response::STATUS_BAD_REQUEST;
            $response->addError('NAME_IS_REQUIRED');
            return $response->toArray();
        }
        try {
            $response->data = Categories::find([
                'conditions' => 'name LIKE :name:',
                'bind' => ['name' => '%' . $name . '%'],
                'order' => 'name ASC',
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
            $response->data = Categories::findFirst($id)->toArray();
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

    public static function delete($id)
    {
        $response = new Response;
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();
        try {
            $products = Products::findByCategoryId($id);
            $products->setTransaction($transaction);
            $products->delete();
            $response->data = Categories::findFirst($id)->delete();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback("Can't delete");
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
            /** @var Categories $categories */
            $category = Categories::findFirst($id);
            $category->setAttributes($data);
            $response->data = $category->update();
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
            /** @var Products $products */
            $category = new Categories;
            $category->setAttributes($data);
            $response->data = $category->create();
            $response->status = $response::STATUS_CREATED;
        } catch (Exception $e) {
            $response->setException($e);
        } finally {
            return $response->toArray();
        }
    }

}