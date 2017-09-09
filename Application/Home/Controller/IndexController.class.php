<?php
namespace Home\Controller;

use Think\Controller;

define("WX_TOKEN", "blacat0214");
class IndexController extends Controller {

	private $appid = "wxaeaf64d64e6d989f";
	private $appsecret = "8995779993c5b2f4448ec4451d5a3e5d";

	public function index() {
		$this->display();

	}

	public function menu() {
		$this->show('<span style="font-size:20px;color:blue">哇，心态崩了</span>');
	}

	public function login() {

	}

	public function register() {

	}

	public function createMenu() {
		$jsoninfo = $this->getToken();
		$access_token = $jsoninfo['access_token'];
		$menu_data = '{
			"button":[
			{
				"name":"组队查询",
				"type":"view",
				"url":"https://120.27.104.254//LoveLD"
			},
			{
				"name":"队伍相关",
				"sub_button":[
				{
					"name":"我的申请",
					"type":"view",
					"url":"https://120.27.104.254//LoveLD"
				},
				{
					"name":"我的创建",
					"type":"view",
					"url":"https://120.27.104.254//LoveLD"
				}]

			},
			{
				"name":"组队查询",
				"type":"view",
				"url":"https://120.27.104.254//LoveLD"
			}
			]
		}';
		$url_result = " https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
		$output_result = $this->wxRequest($url_result, $menu_data);
		echo $output_result;
		//echo $menu_data;
	}

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

	//获取acces_token
	private function getToken() {
		$getUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxaeaf64d64e6d989f&secret=8995779993c5b2f4448ec4451d5a3e5d";
		$output = $this->wxRequest($getUrl);
		$jsoninfo = json_decode($output, true);
		return $jsoninfo;
	}

	//http请求
	private function wxRequest($url, $data = null) {
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

}