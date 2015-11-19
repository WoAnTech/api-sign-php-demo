<?php


/*
 * 需要开启curl_init
 * 具体配置信息：http://jingyan.baidu.com/article/3052f5a1de691d97f31f86c0.html
 * 调用task_list 获取当前应用的直播列表
 * 必备参数：
 *	$hostUrl  http://c.zhiboyun.com
 *  $timestamp 当前时间戳
 * $service_code 应用服务码
 * $key 服务码对应的共享密钥
 * $apiUrl 请求api。 "/api/20140928/task_list";
 * 返回 值：
 * ret:0 即为调用api成功
 */
function getMillisecond() {
	list ($s1, $s2) = explode(' ', microtime());
	return (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}
//获取当前时间
$timestamp = getMillisecond();
//服务码
$service_code = "TESTING";
//服务码对应的密钥
$key = "abc";
$apiUrl = "/api/20140928/task_list";
//需要签名的字符串
$signatureUrl = $apiUrl . "service_code=" . $service_code . $timestamp;
//计算签名
$xvs_signature = hash_hmac("sha256", utf8_encode($signatureUrl), utf8_encode($key));

//需要发送请求的api url
$url = "http://c.zhiboyun.com" . $apiUrl . "?service_code=" . $service_code;
//初始化curl
 $ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'xvs-timestamp:' .$timestamp, 
    'xvs-signature:' .$xvs_signature)
);
//返回是一个json格式字符串
$result = curl_exec($ch);
echo $result;
?>
