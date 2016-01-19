<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use \Phalcon\Http\Response;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    $dbConfig = $config->database->toArray();
    $adapter = $dbConfig['adapter'];
    unset($dbConfig['adapter']);

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

    return new $class($dbConfig);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

$di->setShared(
    "view",
    function () {
        $view = new View;
        $view->disable();
        return $view;
    }
);

$di->setShared(
    "router",
    function () {
        $router = new \Phalcon\Mvc\Router(false);
        $router->removeExtraSlashes(true);
        $router->setDefaultController('products');
        $router->addGet('/v1/api/products', ['controller' => 'products', 'action' => 'list']);
        $router->addGet('/v1/api/products/{id:[0-9]+}', ['controller' => 'products', 'action' => 'findOneById']);
        $router->addGet('/v1/api/products/search/{name}', ['controller' => 'products', 'action' => 'search']);
        $router->addPost('/v1/api/products', ['controller' => 'products', 'action' => 'create']);
        $router->addPut('/v1/api/products/{id}', ['controller' => 'products', 'action' => 'update']);
        $router->addDelete('/v1/api/products/{id}', ['controller' => 'products', 'action' => 'delete']);
        $router->addGet('/v1/api/categories', ['controller' => 'categories', 'action' => 'list']);
        $router->addGet('/v1/api/categories/{id:[0-9]+}', ['controller' => 'categories', 'action' => 'findOneById']);
        $router->addGet('/v1/api/categories/search/{name}', ['controller' => 'categories', 'action' => 'search']);
        $router->addPost('/v1/api/categories', ['controller' => 'categories', 'action' => 'create']);
        $router->addPut('/v1/api/categories/{id}', ['controller' => 'categories', 'action' => 'update']);
        $router->addDelete('/v1/api/categories/{id}', ['controller' => 'categories', 'action' => 'delete']);
        return $router;
    }
);

$di->set('config', $config);