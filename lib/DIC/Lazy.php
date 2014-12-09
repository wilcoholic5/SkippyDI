<?php
namespace DIC;

class Lazy
{

    /**
     * @var callable
     */
    protected $service;

    /**
     * @var array
     */
    protected $params;

    public function __construct($service, $params = array())
    {
        $this->callable = $service;
        $this->params = $params;
    }

    /**
     * This magic method makes Lazy a callable.
     */
    public function __invoke()
    {
        return call_user_func($this->callable, $this->params);
    }
}
