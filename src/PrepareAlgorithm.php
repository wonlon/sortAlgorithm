<?php
namespace src;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-3
 * Time: 11:06
 */
class PrepareAlgorithm{

    private $lockTime;
    private $prepareMedia;
    private $remainCount;
    public function __construct()
    {

    }

    public function setData($lockTime, $prepareMedia, $remainCount = 20000 )
    {
        $this->lockTime = $lockTime;
        $this->prepareMedia = $prepareMedia;
        $this->remainCount = $remainCount;
        return $this;
    }

    public function getAlgorithmAfterData()
    {
        $lockTime = $this->lockTime;
        $prepareMedia = $this->prepareMedia;
        $remainCount = $this->remainCount;
        /*
        //时长
        $lockTime = "100";
    //    key是媒体ID，value是媒体时长（包含特效时长）
        $prepareMedia = [
            "111" => "15",
            "222" => "7",
            "333" => "13",
            "444" => "2",
        ];*/
        $prepareMediaCount = [];
        if(is_array($prepareMedia))
        {
            //从大到小排序
            arsort($prepareMedia);
            //清除时长为0或者小于0的
            foreach($prepareMedia as $key=>$value)
            {
                $prepareMediaCount[$key] = 0;
                if($value<=0)
                {
                    unset($prepareMedia[$key]);
                }
            }
            //获取最后一个元素，即最小的
            $least = end($prepareMedia);
            $laveTime = $lockTime;
            $currentCount = 0;
            //只要剩余的时间大于等于最小的时间，就一直循环加备片
            while( !empty($prepareMedia)&&($laveTime>=$least)&&($currentCount<$remainCount) )
            {
                //循环备片媒体，key为mid,value为媒体时长（包含特效时长）
                foreach($prepareMedia as $key=>$value)
                {
                    //如果剩余时间大于媒体时长，则增加备片数量
                    if($laveTime>=$value)
                    {
                        //若之前没有加过备片则数量为1，若已经添加过则累加1
                        $prepareMediaCount[$key]++;
                        //剩余时间自减当前添加备片时间
                        $laveTime -= $value;
                        $currentCount++;
                    }
                }
            }
        }
        $totalTime = 0;
        foreach ($prepareMediaCount as $key=>$value)
        {
            $totalTime = $totalTime+$prepareMedia[$key]*$value;
        }
        $result['prepareMediaCount'] = $prepareMediaCount;
        $result['totalTime'] = $totalTime;
        return $result;
    }
}