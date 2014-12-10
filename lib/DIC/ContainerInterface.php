<?php
namespace DIC;

interface ContainerInterface {

    /**
     * @param $service
     * @param $val
     * @return mixed
     */
    public function add($service, &$val);

    /**
     * @param string $service
     * @param array $params
     * @return mixed
     */
    public function setParams($service, $params);

    /**
     * @param string $service
     * @param string $var
     * @param mixed $val
     * @return mixed
     */
    public function setParam($service, $var, $val);
}
