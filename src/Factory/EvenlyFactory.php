<?php
namespace src\Factory;
use src\Algorithm\EvenlySortAlgorithm;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-3
 * Time: 15:57
 */
class EvenlyFactory implements Factory
{
    private $data;
    public function __construct( $data )
    {
        $this->data = $data;
    }

    function createAlgorithm()
    {
        // TODO: Implement createAlgorithm() method.
        return new EvenlySortAlgorithm($this->data);
    }

}