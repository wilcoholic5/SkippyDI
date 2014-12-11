<?php
namespace DIC\Mocks;

class EchoService extends Service{
    protected $val1;
    protected $val2;

    public function __construct()
    {
        if (func_get_args()) {
            $args = func_get_args();
            $arr = $args[0];
            $this->val1 = $arr['Blank'];
        }
    }

    public function getVal1()
    {
        return $this->val1;
    }
}
