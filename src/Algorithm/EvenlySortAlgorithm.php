<?php
namespace src\Algorithm;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-3
 * Time: 11:05
 */
class EvenlySortAlgorithm extends Algorithm
{

    private $res;
    private $itemsObj;

    public function __construct($itemsObj)
    {
        $this->itemsObj = $itemsObj;
    }

    /**
     * 最大公约数进行排序
     * @return $this
     */
    public function sortItem()
    {
        // TODO: Implement sortItem() method.
        $newItems = $this->itemsObj['newItems'];
        //res为一次排序结果
        $res = $this->perch($newItems, false);

        //若有相邻的重新进行排序, 不等于-1说明有相邻的，需要进行打乱处理
        if($this->singleChild($res)!== -1){
            $res = $this->perch($newItems,true);
        }


        if(count($newItems) == 1)
        {
            $this->itemsObj['maxDivisor'] = count($res);
            $res = [$res[0]];
        }
        $this->res = $res;
        return $this;
    }

    /**
     * 输出最终排序结果
     * @return mixed
     */
    public function outputOrder()
    {
        $res = $this->res;
        $maxDivisor = $this->itemsObj['maxDivisor'];
        // TODO: Implement outputOrder() method.

        $single = [];
        foreach ($res as $key=>$items)
        {
            foreach ($items as $ikey=>$value)
            {
                $single[] = $value['name'];
            }
        }
        $arr = [];
        //根据最大公约数遍历，求最终输出结果
        for($i = 0, $len = $maxDivisor; $i<$len; $i++ ){
            $arr = array_merge($res, $arr);
        }

        $all = [];
        foreach ($arr as $key=>$aitems)
        {
            foreach ($aitems as $ikey=>$value)
            {
                $all[] = $value['name'];
            }
        }
        $result['single'] = $single;
        $result['all'] = $all;
        $result['maxDivisor'] = $maxDivisor;
        return $result;
    }

    /**
     * 用媒体数最多的媒体进行占位
     * @param $list
     * @param $iscover
     * @return array
     */
    private function perch($list, $iscover)
    {
        $perch = [];
        $first = $list[0]; //因为数组降序了，第一个为最大的
        //占位完毕
        for($i = 0; $i < $first['count']; $i++){
            $perch[] = [$first];
        }
        $flag = 0;
        $countList = count($list);
        for($i = 1; $i < $countList; $i++){
            /*如果插入的序列大于排序数组长度，则从零开始计算*/
            $perchCount = count($perch);

            if($flag >=$perchCount){
                $flag = $flag - $perchCount;
            }

            $this->special($list[$i],$perch,$flag,$iscover);
            $flag++;
        }
        return $perch;
    }

    /**
     * 插入数据
     * @param $item
     * @param $perch
     * @param $flag
     * @param $iscover
     */
    private function special($item, &$perch, $flag, $iscover)
    {
        $firstLen = count($perch);
        for($i = 1; $i <= $item['count']; $i++){
            $index = floor($firstLen/$item['count']*($i-1))+$flag;
            if($index >= $firstLen){
                $index = $index - $firstLen;
            }
            if($iscover){
                //主要针对前几个进行特殊处理
                $singleindex = $this->singleChild($perch);
                //如果坑序列中有空缺并且当前序列在坑中已有其他元素填补
                if($singleindex !== -1 && count($perch[$index]) > 1)	{
                    $index = $singleindex;
                }
            }
            $this->exists($index, $item, $perch);
        }
    }

    /**
     * 检测小数组中是否存在当前这一项
     * @param $index
     * @param $item
     * @param $perch
     */
    private function exists($index, $item, &$perch)
    {
        $firstLen = count($perch);
        if($index >= $firstLen){
            $index = $index - $firstLen;
        }
        $itemSame = false;
        foreach ($perch[$index] as $pkey=>$pvalue)
        {
            foreach ($pvalue as $ppkey=>$ppvalue) {
                if ($ppvalue['name'] == $item['name'])
                {
                    $itemSame = true;
                    break;
                }
            }
        }

        if($itemSame){
            $this->exists($index+1,$item,$perch);
        }else{
            $perch[$index][] = $item;
        }
    }

    /**
     * 检测坑中从左到右哪些坑没有填充
     * @param $perch
     * @return int|mixed
     */
    private function singleChild($perch)
    {
        $index = -1; //默认所有坑都填充了
        $indexArr = [];
        $perchLength = count($perch);
        for($i = 0; $i < $perchLength; $i++){
            if(count($perch[$i]) < 2){
                $indexArr[] = $i;
            }
        }
        if(count($indexArr) === 0){
            $index = -1;
        }
        else{
            // var item = Math.floor((Math.random()*indexArr.length));
            // index = indexArr[item];
            $index = $indexArr[0]; //填充到第一个没有填充的坑 comer  2018-04-26
        }
        return $index;
    }
}