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
}