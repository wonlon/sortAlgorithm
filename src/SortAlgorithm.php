<?php
namespace src;
use src\Factory\EvenlyFactory;
use src\Factory\EvenlyMaxFactory;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-3
 * Time: 11:06
 */
class SortAlgorithm{

    private $data;
    private $itemsObj;
    private $firstMax;
    public function __construct()
    {
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 获取排序后的数据
     * @return array|mixed
     */
    public function  getAlgorithmAfterData()
    {
        //前置数据的处理
        $this->dealItem();
        //通过工厂获取工厂实例
        $evenlyFactory = $this->firstMax?new EvenlyMaxFactory($this->itemsObj):new EvenlyFactory($this->itemsObj);
        //通过工厂实例创建具体的实例类
        $algorithm = $evenlyFactory->createAlgorithm();
        //进行排序并进行输出
        return $algorithm->sortItem()->outputOrder();
    }

    /**
     * 处理初始化数据
     */
    private function dealItem()
    {
        if(count($this->data) === 0)return;
        $itemCountArr = array_map(function ($item){
            return $item['count'];
        }, $this->data);
        //1. 计算数据的最大公约数
        $maxDivisor =   $this->maxCommonDivisor($itemCountArr)?:1;
        $ItemsDivisor = $this->data;

        //对所有项数量除以最大公约数;如果最大公约数为1就不用此操作
        //foreach ($items as $key=>$item)
        //{
        //  $items[$key]['count'] =  $item['count'] / $maxDivisor;
        //}
        if($maxDivisor != 1)
        {
            foreach ($ItemsDivisor as $key=>$item)
            {
                $ItemsDivisor[$key]['count'] =  $item['count'] / $maxDivisor;
            }
        }

        //按照count数量由大到小排序
        $ItemsDivisor = $this->arraySequence($ItemsDivisor, 'count');
        //源数据包进行排序
        $items = $this->arraySequence($this->data, 'count');

        $itemsArr = array_map(function ($item){
            return $item['count'];
        }, $items);

        $divideCountArr = array_map(function ($item){
            return $item['count'];
        }, $ItemsDivisor);


        $firstMax = false; //数组中最大的数比其余数之和大
        if($itemsArr && count($itemsArr) > 1){
            $otherCount = 0;
            foreach ($itemsArr as $key=>$itemCount)
            {
                if($key!==0)
                {
                    $otherCount = $otherCount + $itemCount;
                }
            }
            if($itemsArr[0]>$otherCount) {
                $firstMax = true;
            }
        }


        //exactDiv可被最大数整除的数组成的数组，notExact不可被最大数整除的数组成的数组
        $exactDiv = $notExact = $newItems = $firstItem = [];

        //如果数组长度大于0
        $ItemsDivisorCount = count($ItemsDivisor);
        if($ItemsDivisorCount > 0){
            $firstItem[] = $first = $ItemsDivisor[0];
            for($i = 1;$i < $ItemsDivisorCount;$i++){
                if($first['count'] % $ItemsDivisor[$i]['count'] === 0){
                    $exactDiv[] = $ItemsDivisor[$i];
                }
                else{
                    $notExact[] = $ItemsDivisor[$i];
                }
            }
            $exactDiv = $this->arraySequence($exactDiv, 'count');
            $notExact =  $this->arraySequence($notExact, 'count');
            //return array_merge(array_merge($firstItem, $exactDiv ), $notExact);
            $newItems = array_merge_recursive($firstItem, $exactDiv, $notExact);
        }

        $this->firstMax = $firstMax;
        $this->itemsObj = [
            "newItems"=>$newItems,
            "maxDivisor"=>$maxDivisor,
            "firstMax"=>$firstMax,
            "divideCountArr"=>$divideCountArr,
            "itemsArr"=>$itemsArr,
            "items"=>$items
        ];
    }

    /**
     * 求n个数的最大公约数,arr是一个整型数的数组
     * @param $arr
     * @return int|mixed|void
     */
    private function maxCommonDivisor($arr){
        //求最大公约数的数组长度为0，直接返回
        if($arr && count($arr) ===0){
            return ;
        }

        //求最大公约数的数组只有一个元素时，最大公约数为1
        if($arr && count($arr) === 1){
            return 1;
        }

        //求最大公约数的数组有两个以上元素时，求其最大公约数
        $n=$arr[0];
        $m=$arr[1];
        $d=$this->commonDivisor($n,$m);
        for ($i=2, $len = count($arr); $i<$len;$i++)
        {
            $d=$this->commonDivisor($d,$arr[$i]);
        }
        return $d;
    }

    /**
     * 求最大公约数
     * @param $a
     * @param $b
     * @return mixed
     */
    private function commonDivisor($a, $b){
        if($a < $b){
            $a = $b + $a;
            $b = $a - $b;
            $a = $a - $b;
        }
        if ($b == 0) return $a;
        return $this->commonDivisor($b,$a % $b);
    }

    /**
     * 对二维数组中的某个值进行排序
     * @param $array
     * @param $field
     * @param string $sort
     * @return mixed
     */
    private function arraySequence($array, $field, $sort = 'SORT_DESC')
    {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }
}