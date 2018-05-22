<?php
namespace src\Factory;
use src\Algorithm\EvenlyMaxSortAlgorithm;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-3
 * Time: 15:57
 */
class EvenlyMaxFactory implements Factory {

    private $data;
    public function __construct( $data )
    {
        $this->data = $data;
    }
    function createAlgorithm()
    {
        // TODO: Implement createAlgorithm() method.
        return new EvenlyMaxSortAlgorithm($this->data);
    }

}