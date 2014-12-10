<?php
namespace DIC\Mocks;

class EchoService extends Service{
    protected $val1;
    protected $val2;

    public function __construct($val1 = null, $val2 = null)
    {
        $this->val1 = $val1;
        $this->val2 = $val2;
        if ($val1 instanceof Blank) {
            die('we got the correct instance.');
        }
    }

    public function getVal1()
    {
        return $this->val1;
    }
}
