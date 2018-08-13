# php_spider_tools
<<<<<<< HEAD
基于queryList编写的php爬虫工具类
=======
基于QueryList编写的爬虫工具类，实例化spider类后，调用start方法即可获取抓取的结果

# 参数说明
详细的rules规则可参考 https://doc.querylist.cc/site/index/doc/13

```
$config = [
    'url' =>  '',     //要采集的网页url
    'input_encode' => 'UTF-8',   //输入网页的编码
    'rules' => [
        //文章标题的规则
        'title' => [
            '.main-title',    //jquery选择器
            'text'   //要获取的内容属性，有text、html或者src等属性名
            '',    //标签过滤列表。可参考 https://doc.querylist.cc/site/index/doc/13
            ''    //回调函数
        ],
        //文章内容的规则
        'content' => [
            '#article',
            'html'
        ]
    ],
    'http_config' => []   //http配置参数。可参考CurlTools类
];
```
>>>>>>> init
