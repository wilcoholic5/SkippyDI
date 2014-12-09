<?php
namespace DIC\Mocks;

class Service {
    protected $service;

    public function __construct()
    {

    }

    public function setService($service)
    {
        $this->service = $service;
    }
}
