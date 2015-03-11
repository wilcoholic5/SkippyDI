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
     * set a single parameter
     *
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
     * set a services parameters and the parameter's parameters
     *
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
     * return parameters for given service
     *
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
     * load a new object instantly
     *
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
     * Adds the lazy to the array of services
     *
     * @param $service string
     * @param $params array
     * @return $this object
     */
    public function addLazy($service, $params = array())
    {
        if ($params) {
            $this->setParams($service, $params);
        }

        if (!$params) {
            if ($this->getServiceParams($service)) {
                $params = $this->getServiceParams($service);
            }
        }

        $this->services[$service] = $this->newLazy($service, $params);

        return $this->services[$service];
    }

    /**
     * Actually creates the new lazy. This is a callable that knows how to correctly
     * instantiate the class.
     *
     * @param $service
     * @param array $params
     * @return callable
     */
    public function newLazy($service, $params = array())
    {
        return function() use ($service, $params)
        {
            if ($params) {
                foreach ($params as $param=>$val) {
                    if ($val instanceof \Closure) {
                        $class = $param;
                        $val = new $class($this->params[$class]);
                    }
                    $this->setParam($service, $param, $val);
                }
            }
            $reflect = new \ReflectionClass($service);
            if($reflect->hasMethod("__construct")) {
                $args = $this->sortArgs($reflect);
                return $reflect->newInstanceArgs($args);
            } else {
                return new $service($this->params[$service]);
            }
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
     * @param $mock
     * @return array
     */
    public function sortArgs($mock)
    {
        $params = $mock->getConstructor()->getParameters();
        $sortedArgs = array();
        foreach ($params as $param) {
            var_dump($this->params);
            array_push($sortedArgs, $this->getServiceParams($mock->getName())[$param->name]);
        }

        return $sortedArgs;
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
