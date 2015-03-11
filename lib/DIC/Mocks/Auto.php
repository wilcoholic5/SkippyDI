<?php
namespace DIC\Mocks;

class Auto
{
    public function __construct($echo)
    {
        print 'created new object';
    }
}
