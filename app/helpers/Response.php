<?php


class Response
{

    public $status, $code, $errors, $data;

    const STATUS_SUCCESS = 200;
    const STATUS_FORBIDDEN = 403;
    const STATUS_SERVER_ERROR = 500;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_CREATED = 201;
    
    const CODE_OK = 'ok';
    const CODE_ERROR = 'error';

    public function __construct()
    {
        $this->status = self::STATUS_SUCCESS;
        $this->code = self::CODE_OK;
    }

    public function toArray()
    {
        $data = [
            'status' => $this->status,
            'code' => $this->code,
            'data' => $this->data,
        ];
        if ($this->errors) {
            $data['errors'] = $this->errors;
        }
        return $data;
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function setException(Exception $e)
    {
        $this->status = self::STATUS_SERVER_ERROR;
        $this->code = self::CODE_ERROR;
        $this->addError($e->getMessage());
    }

}