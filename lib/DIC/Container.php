<?php
namespace DIC;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    protected $services;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param $service
     * @param $var
     * @param $val
     * @return mixed|void
     */
    public function setParam($service, $var, $val)
    {
        $this->params[$service][$var] = $val;
    }

    public function setParams($service, $params)
    {
        if (is_array($params)) {
            foreach ($params as $param) {
                // TODO: Stuff
            }
        }
    }

    /**
     * return defined parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $service
     * @return array
     */
    public function getServiceParams($service)
    {
        if (array_key_exists($service, $this->params)) {
            return $this->params[$service];
        }
    }

    /**
     * @param string $service
     * @param object $val
     * @param $params array
     * @return mixed
     */
    public function add($service, &$val, $params = null)
    {
        if (!is_object($val)) {
            print 'Something is not an object';
        }
        $this->services[$service] = $val;
    }

    /**
     * @param $service string
     * @param $params array
     */
    public function addLazy($service, $params = null)
    {
        if ($params) {
            $this->setParams($service, $params);
        }

        if (is_string($service)) {
            $this->services[$service] =
                function() use ($service, $params)
                {
                    $class = '\\DIC\\Mocks\\'.$service;
                    return new $class($params[0]);
                };
        }
    }

    /**
     * @param string $service
     * @return mixed
     */
    public function &get($service)
    {
        if ($this->services[$service] instanceof \Closure) {
            var_dump($this->getServiceParams('EchoService'));
            $this->services[$service] = call_user_func($this->services[$service], $this->params[$service]);
        }
        return $this->services[$service];
    }

    public function getServices()
    {
        return $this->services;
    }
}
