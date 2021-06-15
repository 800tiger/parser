<?php

namespace Parser\Parser;

class ParserHeader
{

    //new column for transaction validation
    public $validate;

    public $headers;

    public function __construct(array $headers, string $validate)
    {
        $this->headers = $headers;
        $this->setValidate($validate);
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getValidate()
    {
        return $this->validate;
    }
}
