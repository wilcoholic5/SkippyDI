<?php
namespace DIC;

class Container {
    /**
     * @var array
     */
    protected $services;

    /**
     * @var array
     */
    protected $params;

    public function __construct()
    {

    }

    public function setParams($service, $val)
    {
        $services[$service] = $val;
    }

}
