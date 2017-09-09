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
		$jsoninfo = getToken();
		$access_token = $jsoninfo['access_token'];
		$menu_data = '{
			"button":[
			{
				"name":"组队查询",
				"type":"view",
				"url":"https://120.27.104.254//LoveLD",
			},
			{
				"name":"队伍相关",
				"sub_button":[
				{
					"name":"我的申请",
					"type":"view",
					"url":"https://120.27.104.254//LoveLD",
				},
				{
					"name":"我的创建",
					"type":"view",
					"url":"https://120.27.104.254//LoveLD",
				}]

			},
			{
				"name":"组队查询",
				"type":"view",
				"url":"https://120.27.104.254//LoveLD",
			}
			]
		}';
		$url_result = " https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
		$output_result = http_request($url_result, $menu_data);
		echo $output_result;
		echo $menu_data;
	}

	//This is for wechat
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

	public function valid() {
		$echoStr = $_GET["echostr"];
		if ($this->checkSignature()) {
			header('content-type:text');
			echo $echoStr;
			exit;
		}
	}

	private function getToken() {
		$getUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxaeaf64d64e6d989f&secret=8995779993c5b2f4448ec4451d5a3e5d";
		$output = https_request($getUrl);
		$jsoninfo = json_decode($output, true);
		return $jsoninfo;
	}

}