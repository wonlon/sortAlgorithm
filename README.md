## 算法说明
### 备片算法
1、通过剩余时长、剩余次数、媒体项对应时长的数组，输出媒体的个数

### 均匀排序算法
1、通过媒体的个数，对媒体进行均匀排序

## 应用场景
1、排片时，剩余时间进行填充使用备片算法
2、对很多媒体项进行均匀的排序播放

##使用方式

###1、备片算法，计算备片数量
####传输参数
- $remainTime  剩余的时间（秒）  10000
- $prepareInfo  计算的信息
```
key是媒体ID，value是媒体时长（包含特效时长）
$prepareMedia = [
  "111" => "15",
  "222" => "7",
  "333" => "13",
  "444" => "2",
];
```
- $remainCount 剩余的次数  1254

####输出结果
- $prepareResult
```
key是媒体ID，value次数
$prepareMedia = [
  "111" => 2,
  "222" => 3,
  "333" => 1,
  "444" => 0,
];
```

####代码调用   
```
$prepareResult = $prepareAlgorithm->setData($remainTime, prepareInfo, $remainCount)->getAlgorithmAfterData();
```
         
          
###2、均匀排序算法

####传输参数
- $orderAllData
```
$orderAllData = [
    {
        'name'=>'A',
        'count'=>2
    },
    {
        'name'=>'B',
        'count'=>2
    },
    {
        'name'=>'C',
        'count'=>2
    },
];
```
     
####输出结果
- $sortResult
```
key是媒体ID，value次数
$sortResult = [
      "all" => [
        "A","B","C","A","B","C"
      ],
      "single" => [
        "A","B","C"
      ],
      "maxDivisor" => 52
];
```
####代码调用  
```
$sortResult = $sortAlgorithmData =  $sortAlgorithm->setData($orderAllData)->getAlgorithmAfterData();
```