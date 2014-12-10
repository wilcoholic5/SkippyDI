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
        if (!isset($this->params[$service][$var])) {
            $this->params[$service][$var] = $val;
        }
    }

    /**
     * @param string $service
     * @param array $params
     * @return mixed|void
     */
    public function setParams($service, $params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                $this->setParam($service, $key, $val);
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
        if ($this->params[$service]) {
            if (array_key_exists($service, $this->params)) {
                return $this->params[$service];
            }
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
     * @return $this object
     */
    public function addLazy($service, $params = null)
    {
        if (!is_string($service)) {
            throw new \InvalidArgumentException();
        }

        if ($params) {
            $this->setParams($service, $params);
        }

        if (!$params) {
            if (!$this->getServiceParams($service)) {
                $params = $this->getServiceParams($service);
            }
        }
        $this->services[$service] = $this->newLazy($service, $params);

        return $this->services[$service];
    }

    public function newLazy($service, $params = null)
    {
        return function() use ($service, $params)
        {
            if ($params) {
                foreach ($params as $param=>$val) {
                    if ($val instanceof \Closure) {
                        $val = new $param($this->params[$service]);
                    }
                    $this->setParam($service, $param, $val);
                }
            }

            $class = '\\DIC\\Mocks\\'.$service;
            return new $class($this->params[$service]);
        };
    }

    /**
     * @param string $service
     * @return mixed
     */
    public function &get($service)
    {
        //$this->initDependencies($service, $this->getServiceParams($service));
        if ($this->services[$service] instanceof \Closure) {
            $this->services[$service] = call_user_func($this->services[$service], $this->params[$service]);
        }

        return $this->services[$service];
    }

//    public function initDependencies($service, $params)
//    {
//        foreach ($params as $param) {
//            $val = &$this->services[$service][$param];
//            if ($val instanceof \Closure) {
//                $val = call_user_func($val, $this->getServiceParams($param));
//            }
//        }
//    }

    public function getServices()
    {
        return $this->services;
    }
}
