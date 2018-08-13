<?php
/*
* 获取新浪新闻文章页的标题和内容
* 演示如何使用spider类
*/
require_once "./spider.php";
$config = [
    'url' =>  'http://news.sina.com.cn/gov/xlxw/2018-08-10/doc-ihhnunsq7130093.shtml',
    'input_encode' => 'UTF-8',
    'rules' => [
        'title' => [
            '.main-title',
            'text'
        ],
        'content' => [
            '#article',
            'html'
        ]
    ],
    'http_config' => []
];
$spider = new Spider($config);

$data = $spider->start();

var_dump($data);
