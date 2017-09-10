<?php
namespace Home\Controller;

use Think\Controller;

define("WX_TOKEN", "blacat0214");
class IndexController extends Controller {

	//private $appid = "wxaeaf64d64e6d989f";
	//private $appsecret = "8995779993c5b2f4448ec4451d5a3e5d";
	private $appid_faker = "wx09aaef70a0a8e448";
	private $appsecret_faker = "b5a7e32676db8bc0bdcd18f3402fa487";

	public function index() {
		$main = new MyChat();
		if (!isset($_GET['echostr'])) {
			$main->recive_msg();
		} else {
			$main->valid();
		}
	}

	public function menu() {
		$this->show('<span style="font-size:20px;color:blue">哇，心态崩了</span>');
	}

	public function login() {
		$acc_code = $_GET["code"];
		$acc_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx09aaef70a0a8e448&secret=b5a7e32676db8bc0bdcd18f3402fa487&code=" . $acc_code . "&grant_type=authorization_code";
		$main = new MyChat();
		$result = $main->wxRequest($acc_url);
		$resultinfo = json_decode($result);
		echo $resultinfo['openid'];
	}

	public function register() {

	}

	public function createMenu() {
		$mc = new MyChat();
		$jsoninfo = $mc->getToken();
		$access_token = $jsoninfo['access_token'];
		echo "<br>jsoninfo:  ";
		var_dump($jsoninfo);
		echo "<br>access_token:  ";
		var_dump($access_token);
		$menu_data = '{
			"button":[
			{
				"name":"组队查询",
				"type":"view",
				"url":"https://recyclerblacat.top/LoveLD"
			},
			{
				"name":"队伍相关",
				"sub_button":[
				{
					"name":"我的申请",
					"type":"view",
					"url":"https://recyclerblacat.top/LoveLD"
				},
				{
					"name":"我的创建",
					"type":"view",
					"url":"https://recyclerblacat.top/LoveLD"
				}]

			},
			{
				"name":"个人中心",
				"type":"view",
				"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx09aaef70a0a8e448&redirect_uri=https://recyclerblacat.top/pinpinpin/index.php/Home/Index/login&response_type=code&scope=snsapi_bas
e&state=loveld#wechat_redirect "
			}
			]
		}';
		$url_result = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
		echo "<br>url_result:  ";
		var_dump($url_result);
		$output_result = $mc->wxRequest($url_result, $menu_data);
		$output_resultinfo = json_decode($output_result, true);
		echo "<br>output_result:  ";
		var_dump($output_resultinfo);
		//echo $menu_data;
	}

}

class MyChat {

	//This is for wechat
	//token验证（服务器用）
	private function checkSignature() {
		$sign = $_GET["signature"];
		$tist = $_GET["timestamp"];
		$none = $_GET["nonce"];
		$wx_token = "blacat0214";
		$tmpArr = array($wx_token, $tist, $none);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if ($tmpStr == $sign) {
			return true;
		} else {
			return false;
		}
	}

	//微信服务端验证
	public function valid() {
		$echoStr = $_GET["echostr"];
		if ($this->checkSignature()) {
			header('content-type:text');
			echo $echoStr;
			exit;
		}
	}

	//http请求
	public function wxRequest($url, $data = null) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}

	//获取acces_token
	//每次调用检查当前token，已存在生效的不需要重复获取，过期需要重新获取。
	public function getToken() {
		$tokenData = $this->readJson();
		var_dump($tokenData);
		$nowtimestamp = $tokenData['nowtimestamp'] + 0;
		$now_time = intval(time()) + 0;
		if ($nowtimestamp == null || $nowtimestamp <= $now_time) {

			$getUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx09aaef70a0a8e448&secret=b5a7e32676db8bc0bdcd18f3402fa487";
			$output = $this->wxRequest($getUrl);
			$jsoninfo = json_decode($output, true);
			$tokenData['access_token'] = $jsoninfo['access_token'];
			$tokenData['nowtimestamp'] = intval(time()) + 7200;
			$this->writeJson($tokenData);
			return $tokenData;
		}
		return $tokenData;
	}

	//写入新的access_token
	private function writeJson($data) {
		$json_string = json_encode($data);
		file_put_contents('access_token.json', $json_string);
	}

	//读取access_token
	private function readJson() {
		$json_string = file_get_contents('access_token.json');
		$jsoninfo = json_decode($json_string); //这是一个stdclass类型，无法直接使用，所以需要用下方转换为数组
		$arr = get_object_vars($jsoninfo);
		return $arr;
	}

	//消息获取
	public function recive_msg() {
		$rec_data = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($rec_data)) {
			$rec_object = simplexml_load_string($rec_data, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $rec_object->FromUserName;
			$toUsername = $rec_object->ToUserName;
			$time = $rec_object->CreateTime;
			$content = trim($rec_object->Content);
			$result = $this->response_msg($toUsername, $fromUsername, $content);
			echo $result;
		} else {
			echo "";
			exit;
		}
	}
	//消息回复
	public function response_msg($toUsername, $fromUsername, $content) {
		$text_temple = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";
		$resultStr = sprintf($text_temple, $fromUsername, $toUsername, time(), $content);
		file_put_contents('log', $resultStr);
		return $resultStr;
	}
}
