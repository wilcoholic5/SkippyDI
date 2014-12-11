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
        if (!@array_key_exists($service, $this->params)) {
            return array();
        }

        return $this->params[$service];
    }

    /**
     * @param string $service
     * @param object $val
     * @param $params array
     * @return mixed
     */
    public function add($service, &$val, $params = array())
    {
        $this->services[$service] = $val;
    }

    /**
     * @param $service string
     * @param $params array
     * @return $this object
     */
    public function addLazy($service, $params = array())
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

    public function newLazy($service, $params = array())
    {
        return function() use ($service, $params)
        {
            if ($params) {
                foreach ($params as $param=>$val) {
                    if ($val instanceof \Closure) {
                        $class = '\\DIC\\Mocks\\'.$param;
                        $val = new $class($this->params[$service]);
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
        $this->initDependencies($service, $this->getServiceParams($service));
        if ($this->services[$service] instanceof \Closure) {
            //var_dump(array_values($this->getServiceParams($service)));
            $this->services[$service] = call_user_func_array($this->services[$service], array_values($this->getServiceParams($service)));
        }

        return $this->services[$service];
    }

    /**
     * @param string $service
     * @param array $params
     */
    public function initDependencies($service, $params)
    {

        foreach ($params as $key=>$param) {
            if ($params[$key] instanceof \Closure) {
                $this->setParam($service, $key, call_user_func_array($params[$key], array_values($this->getServiceParams($key))));
            }
        }
    }

    public function getServices()
    {
        return $this->services;
    }
}
