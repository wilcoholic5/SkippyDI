<?php
namespace DIC\Mocks;

class Service {
    protected $service;

    public function __construct($service = null)
    {
        $this->service = $service;
    }

    public function setService($service)
    {
        $this->service = $service;
    }
}
