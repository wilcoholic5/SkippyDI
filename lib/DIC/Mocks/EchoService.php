<?php
namespace DIC\Mocks;

class EchoService extends Service
{
    protected $blank;
    protected $blankTwo;
    protected $name;

    public function __construct($blank = null, $blankTwo = null, $name = null)
    {
        if (func_get_args()) {
            $args = func_get_args();
            $arr = $args[0];
            $this->blank = $arr['Blank'];
            $this->blankTwo = $arr['BlankTwo'];
            $this->name = $arr['name'];
        }
    }

    public function getBlank()
    {
        return $this->blank;
    }

    public function getBlankTwo()
    {
        return $this->blankTwo;
    }

    public function getName()
    {
        return $this->name;
    }
}
