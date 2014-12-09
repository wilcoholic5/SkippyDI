<?php
/**
 * Created by PhpStorm.
 * User: cesar
 * Date: 12/8/14
 * Time: 6:25 PM
 */

namespace DIC\Mocks;


class EchoServiceConstruct extends Service
{
    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }
} 