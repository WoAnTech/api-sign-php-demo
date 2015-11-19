<?php

/*
 * 创建一个直播任务的demo  create
 * 必备参数：
 *	$hostUrl  http://c.zhiboyun.com
 *  $timestamp 当前时间戳
 * $service_code 应用服务码
 * $key 服务码对应的共享密钥
 * $apiUrl 请求api。 " /api/20140928/create_task_with_conf";
 * 返回 值：
 * 
 * {"ret": 0, "task_id": "aws-cn_north_*********"}
 * ret:0 即为调用api成功
 */
//获取当前时间的毫秒值
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
//访问的api
$apiUrl = "/api/20140928/create_task_with_conf";
//转码的xml
/**
 * <url>http://42.120.52.18/mp4/lrx.mp4</url> 这个是视频源地址
 *  更多的xml 书写请联系 沃安客服。
 */
$xml = "<root> <task> <input> <url>http://42.120.52.18/mp4/lrx.mp4</url> </input> <output tag=\"tr1\"> <extension>flv</extension> <codec-v>h264</codec-v> <codec-a>copy</codec-a> <size>480x180</size> </output> </task> </root>";
//url 转码
$encodeXml = rawurlencode($xml);
//需要签名的字符串
$signatureUrl = $apiUrl . "service_code=" . $service_code . "&config=" . $xml . $timestamp;
//计算签名
$xvs_signature = hash_hmac("sha256", utf8_encode($signatureUrl), utf8_encode($key));
//需要发送请求的api url
$url = "http://c.zhiboyun.com" . $apiUrl . "?service_code=" . $service_code . "&config=" . $encodeXml;
//初始化curl
 $ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'xvs-timestamp:' .$timestamp, 
    'xvs-signature:' .$xvs_signature)
);

  
$result = curl_exec($ch);
echo $result;

?>
