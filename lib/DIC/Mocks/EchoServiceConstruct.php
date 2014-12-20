<?php
namespace DIC\Mocks;

class EchoServiceConstruct extends Service
{
    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }
} 