<?php
/*
* 利用curl抓取网页内容
* @info 配置好参数，直接调用getData获取信息即可，如果获取到的是false，则可以通过getError获取错误信息
* @author Hao
* @date 2018-5-16
*/
class CurlTools
{
	protected static $config = [
		//http请求头信息，数组格式
		'header'  => [
			'Accept-Language' => '',
			'Connection' =>  'keep-alive',
			'User-Agent' =>  'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36',
			'Accept'  => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'
		],
		'timeout' => 100,  //设置curl允许执行的最大时间，单位秒
		'proxy_ip' => '',   //代理服务器ip
		'proxy_port' => '',  //代理服务器端口
		'proxy_user' => '',  //代理服务器用户名
		'proxy_password' => '', //代理服务器密码
	];

	protected static $error='';  //用于保存错误信息

	/*
     * 获取错误信息
     * @return string 返回错误信息
     */
	public static function getError()
	{
		return self::$error;
	}

	/*
    * 获取网页内容
    * @param string $url 要采集的url
    * @param string $input_encode  待采集网页的编码
    * @param array $http_config http配置参数
    * @return string 返回采集到的数据
    */
	public static function getData($url,$input_encode,$http_config=[])
	{
		self::$config = array_merge(self::$config,$http_config);  //合并配置参数
		$header = self::$config['header'];   //http请求头
		$timeout = self::$config['timeout'];   //超时时间，单位秒
		$proxy_ip = self::$config['proxy_ip'];   //代理服务器ip
		$proxy_port = self::$config['proxy_port'];  //代理服务器端口
		$proxy_user = self::$config['proxy_user'];  //代理服务器用户名
		$proxy_password = self::$config['proxy_password'];   //代理服务器密码

		try{
			//初始化curl
			$ch = curl_init();

		    curl_setopt($ch, CURLOPT_URL, $url);   //设置要访问的url

		    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);    //设置http请求头，参数为数组格式

		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //curl返回的信息不直接输出

			// https请求不验证证书和hosts
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		    //如果存在代理信息，则设置代理
		    if($proxy_ip != '' && $proxy_port != '')
		    {
		    	//代理服务器地址
			    curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
			    //代理服务器端口
				curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);

				//如果代理服务器存在用户名和密码，则设置
				if($proxy_user != '' && $proxy_password != '')
				{
					curl_setopt($ch,CURLOPT_PROXYUSERPWD,$proxy_user.':'.$proxy_password);  //设置代理服务器用户名和密码
				}
		    }

		    //设置curl超时时间
		    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

		    //执行curl
		    $str = curl_exec($ch);

		    //关闭curl句柄
		    curl_close($ch);
		}catch(\Exception $e)
		{
			self::$error = $e->getMessage();   //将错误信息保存至属性
			return false;
		}

		try{
			//将采集到的字符格式统一转换为utf-8
	    	self::iconvUp($str,$input_encode);
		}catch(\Exception $e)
		{
			self::$error = $e ->getMessage();
			return false;
		}

	 	return $str;
	}

	/*
    * 转换字符串编码
    * @param string $str  待转换的字符串
    * @param string $input_encode 输入编码
    * @param string $output_encode 输出编码
    */
	protected static function iconvUp(&$str,$input_encode,$output_encode='UTF-8')
	{
        $input_encode = strtoupper($input_encode);  //将编码格式转换为大写
		$output_encode  = strtoupper($output_encode);
		//如果源编码和目标编码不一致，则进行转换
		if($input_encode != $output_encode)
		{
			$str = iconv($input_encode,$output.'//IGNORE',$str);  //转换编码
		}
	}

}
