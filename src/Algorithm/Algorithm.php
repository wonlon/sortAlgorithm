<?php
namespace src\Algorithm;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-3
 * Time: 11:06
 */
abstract class Algorithm{

    /**
     * 对数据进行排序
     * @return mixed
     */
    abstract function sortItem();

    /**
     * 输出最终的数据
     * @return mixed
     */
    abstract function outputOrder();
}