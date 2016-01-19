<?php

use Phalcon\Mvc\Controller;

class RestController extends Controller
{

    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $this->setJsonContent($dispatcher->getReturnedValue());
    }

    public function beforeExecuteRoute()
    {
        if (($res = $this->secure()) !== null) {
            $this->setJsonContent($res);
            return false;
        }
    }

    private function secure()
    {
        $data = $this->request->getBasicAuth();
        if (!$data || !((isset($data['username']) && $data['username'] == $this->config->auth->username) &&
                (isset($data['password']) && $data['password'] == $this->config->auth->password))) {
            $response = new Response;
            $response->status = $response::STATUS_FORBIDDEN;
            $response->code = $response::CODE_ERROR;
            $response->addError('not right username and password');
            return $response->toArray();
        }
    }

    protected function setJsonContent($data)
    {
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setJsonContent($data);
        $this->response->send();
    }

}
