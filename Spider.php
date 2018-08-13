<?php
/*
* 基于QueryList编写的爬虫工具类，实例化spider类后，调用start方法即可获取抓取的结果
* Author:Hao
* Date:2018-8-10
*/
use QL\QueryList;
require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/libs/CurlTools.php";

class Spider
{
    public $config = [
        'url' =>  '',
        'input_encode' => '',
        'rules' => [],
        'http_config' => []
    ];

    public $queryList;   //用于存储querylist对象实例

    /*
    * 构造函数
    * @param array $config  详细说明见文档
    */
    public function __construct($config)
    {
        $this->queryList = new QueryList();   //实例化querylist对象
        $this->config = $config;   //将传递的参数保存至属性
    }

    /*
     * 开始采集数据
     */
    public function start()
    {
        $url = $this->config['url'];
        $input_encode = $this->config['input_encode'];
        $rules = $this->config['rules'];
        $http_config = $this->config['http_config'];

        if($input_encode == "" || $url == "" || $rules == "")
        {
            throw new Exception('参数错误！');
            return false;
        }

        $page_content = CurlTools::getData($url,$input_encode,$http_config);  //获取网页数据

        //按照传入的参数，提取网页中的数据
        $data = $this->queryList->html($page_content)
                                ->rules($rules)
                                ->query()
                                ->getData();
        $data = $data->all();  //将对象转换为数组
        $this->queryList->destruct();  //释放querylist DOM资源
        return $data;
    }
}
