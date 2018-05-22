<?php
namespace src\Algorithm;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-3
 * Time: 11:05
 */
class EvenlyMaxSortAlgorithm extends Algorithm
{
    private $isTwoItem;
    private $res;
    private $maxDivisor;
    private $itemsObj;
    public function __construct($itemsObj)
    {
        $this->itemsObj = $itemsObj;
    }

    /**
     * 进行数据的排序
     * @return $this
     */
    public function sortItem()
    {
        // TODO: Implement sortItem() method.
        $itemsObj = $this->itemsObj;
        //如果只有两个元素，且最小值为1
        if(count($itemsObj['itemsArr']) == 2 && $itemsObj['itemsArr'][1] ==1){
            $this->isTwoItem = true;
            $this->sortTwoItem($itemsObj);
        }else{
            $this->isTwoItem = false;
            $this->sortMultiItem($itemsObj);
        }
        return $this;
    }

    /**
     * 输出排序内容
     * @return array
     */
    public function outputOrder()
    {
        // TODO: Implement outputOrder() method.
        if($this->isTwoItem)
        {
            $single = [];
            foreach ($this->res as $key=>$items)
            {
                $single[] = $items['name'];
            }

            return [
                "all" => $single,
                "single" => $single,
                "maxDivisor" => 1
            ];
        }
        else
        {
            $res = $this->res;
            $arr = $single = $all = [];
            foreach ($res as $key=>$items)
            {
                $single[] = $items['name'];
            }

            //根据最大公约数遍历，求最终输出结果
            for($i = 0, $len = $this->maxDivisor; $i<$len; $i++ ){
                $arr = array_merge($res, $arr);
            }

            foreach ($arr as $key=>$aitems)
            {
                $all[] = $aitems['name'];
            }

            return [
                "all" => $all,
                "single" => $single,
                "maxDivisor" => $this->maxDivisor
            ];
        }
    }

    /**
     * 如：4A 1B这种情况排序特殊处理  即：只有两个元素，且最小值为1
     * @param $itemsObj
     * @return array
     */
    private function sortTwoItem($itemsObj)
    {
        $arr = $itemsObj['itemsArr'];
        $newItems = $itemsObj['items'];
        $arrName = $perch =[];
        $total = $arr[0] + $arr[1];
        $n = floor($total/2);

        foreach ($newItems as $key=>$item)
        {
            $arrName[] = $item;
        }
        for($i=0; $i<$total; $i++){
            if($i == $n){
                $perch[] = $newItems[1];
            }else{
                $perch[] = $newItems[0];
            }
        }
        $this->res = $perch;
    }

    /**
     * 当数组中的最大值大于其余数之和
     * @param $itemsObj
     * @return array
     */
    private function sortMultiItem($itemsObj)
    {
        $divideCountArr = $itemsObj['divideCountArr'];
        $newItems = $itemsObj['newItems'];
        $this->maxDivisor = $itemsObj['maxDivisor'];
        $arrArr = [];
        $count = 0;

        foreach ($divideCountArr as $key=>$value)
        {
            $count = $count + $value;
        }
        //STEP1:根据所有项之和的长度，生成一个二维数组
        for($i = 0; $i<$count; $i++){
            $arrArr[] = [];
        }
        //STEP2: 遍历将每一项插入arrArr中
        $newItemsCount = count($newItems);

        for($i = 0, $len = $newItemsCount; $i<$len; $i++){
            $itemCount = $newItems[$i]['count'];
            $itemName = $newItems[$i];
            $nullArr = $this->nullArrNums($arrArr); //二维数组中空数组个数

            for($j=0; $j<$itemCount; $j++){
                $index = floor(($nullArr['count']/$itemCount)*$j);
                $arrArr[$nullArr['keyArr'][$index]][] = $itemName;
            }
        }

        //STEP3: 拼装数据，返回
        $res = [];
        foreach ($arrArr as $key=>$item)
        {
            $res[] = $item[0];
        }

        $this->res = $res;
    }

    /**
     * 求一个二维数组中，空数组的个数
     * @param $arrArr
     * @return array
     */
    private function nullArrNums($arrArr)
    {
        $count = 0;
        $keyArr = [];
        foreach ($arrArr as $key=>$value)
        {
            if(count($value) == 0)
            {
                $keyArr[$count] = $key;
                ++$count;
            }
        }
        return [
            'count' => $count,
            'keyArr' => $keyArr
        ];
    }
}