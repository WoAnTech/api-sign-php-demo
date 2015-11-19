<?php


/*
 * 调用直播提供的其他api接口
 * 例如：
 * 列出平台认证用户
 * 	对应的请求api 参数
 *   {
	    "function": "list_users",
	    "params": {
	        "service_code": "TESTING",
	        "fields": "id, service_code, user_name",
	        "page_index": 0,
	        "per_page": 5
	    }
	}
 *  必备参数：
 *	$hostUrl  http://c.zhiboyun.com
 *  $timestamp 当前时间戳
 * $service_code 应用服务码
 * $key 服务码对应的共享密钥
 * $apiUrl 请求api。 "/api/20140928/task_list";
 */
//获取当前时间的毫秒值
function getMillisecond() {
	list ($s1, $s2) = explode(' ', microtime());
	return (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}
 //获取当前时间
$timestamp=getMillisecond();
//服务码
$service_code="TESTING";
//服务码对应的密钥
$key="abc";
$apiUrl="/api/20140928/management";
$data= array("function" => "list_users", "params" => array("service_code" =>"TESTING","fields" =>"id,service_code,user_name","page_index" => 0,"per_page"=> 5));
$data_string=json_encode($data);
$signatureUrl = $apiUrl."service_code=".$service_code.$data_string.$timestamp;
//计算签名
$xvs_signature = hash_hmac("sha256", utf8_encode($signatureUrl), utf8_encode($key));
//需要发送请求的api url
$url = "http://c.zhiboyun.com" . $apiUrl . "?service_code=" . $service_code;
//初始化curl
 $ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'xvs-timestamp:' .$timestamp, 
    'xvs-signature:' .$xvs_signature,
    'Content-Length:'.strlen($data_string))
);
  
$result = curl_exec($ch);
echo $result;
?>
