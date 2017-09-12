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

	public function logintemp() {
	}

	//个人中心菜单
	//@@ 如果绑定用户直接跳转至userdisplay页面。
	public function login() {
		if (!session('?wxusername')) {
			$acc_code = $_GET["code"];

			$acc_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx09aaef70a0a8e448&secret=b5a7e32676db8bc0bdcd18f3402fa487&code=" . $acc_code . "&grant_type=authorization_code";
			//echo $acc_url;
			$main = new MyChat();
			$result = $main->wxRequest($acc_url);
			$resultinfo = json_decode($result, true);
			$get_openid = $resultinfo['openid'];
			//var_dump($resultinfo);
			session('wxusername', $get_openid);
		} else {
			$get_openid = session('wxusername'); //得到了openid
			//echo "openid:" . $get_openid;
		}
		$se = M('wxuser');
		$wxse = $se->where('wx="' . $get_openid . '" AND state = 1')->find();
		if ($wxse == null || $wxse == false) {
			$this->display();
		} else {
			$this->success('已绑定用户', U('userdisplay'), 0);
		}
	}

	//未绑定用户需要绑定
	public function userbind() {
		if (!isset($_POST['userid']) || !isset($_POST['username'])) {
			$this->error("输入学号和姓名");
		}
		$post_userid = $_POST['userid'];
		$post_username = $_POST['username'];
		$database = M("users");
		$search_result1 = $database->where('id=' . $post_userid)->find();
		if (!session('?wxusername')) {
			echo "error:no session";
			exit;
		}
		if ($search_result1 == false || $search_result1 == null) {

			$newuser['id'] = $post_userid;
			$newuser['name'] = $post_username;
			$newuser['sex'] = 0;
			$newuser['mobile'] = '12345678910';
			$add_user_result = $database->add($newuser);
			if ($add_user_result) {
				$bind = M('wxuser');
				$newbind['wx'] = session('wxusername');
				$newbind['userid'] = $post_userid;
				$newbind['createtime'] = time();
				$newbind['state'] = 1;
				$bind_result = $bind->add($newbind);
				if ($bind_result) {
					$this->success("绑定成功，欢迎新用户", U('userdisplay'));

				} else {
					$this->error("绑定错误");
				}
			} else {
				$this->error("服务器错误");
			}

		} else {
			$search_result2 = $database->where('id=' . $post_userid . ' AND name="' . $post_username . '"')->find();
			if ($search_result2 == null || $search_result2 == false) {
				$this->error("学号姓名不匹配");
			} else {
				$bind = M('wxuser');
				$newbind['wx'] = session('wxusername');
				$newbind['userid'] = $post_userid;
				$newbind['createtime'] = time();
				$newbind['state'] = 1;
				$bind_result = $bind->add($newbind);
				if ($bind_result) {
					$this->success("绑定成功，欢迎回来", U('userdisplay'));

				} else {
					$this->error("绑定错误");
				}
			}
		}
	}
	//用户显示，如果没有就跳转至绑定页面
	public function userdisplay() {
		if (session("?wxusername")) {
			$get_openid = session("wxusername");
			$se = M('wxuser');
			$wxse = $se->where('wx="' . $get_openid . '" AND state = 1')->find();
			if ($wxse == null || $wxse == false) {
				$this->success('未绑定用户', U('login'), 0);

			} else {
				$database = M('users');
				$userdata = $database->where("id=" . $wxse['userid'])->find();
				if ($userdata) {
					$this->assign('userid', $userdata['id']);
					$this->assign('username', $userdata['name']);
					$sex = $userdata['sex'];
					if ($sex == '0') {
						$this->assign('sex', '女');
					} else {
						$this->assign('sex', '男');
					}

					$this->assign('mobile', $userdata['mobile']);
					$this->display();
				} else {
					echo "error :userid error";
					exit;
				}
			}
		} else {
			echo "error :no session";
			exit;
		}
	}
	//用户解绑
	public function removebind() {
		if (session("?wxusername")) {
			$get_openid = session("wxusername");
			$se = M('wxuser');
			$wxse = $se->where('wx="' . $get_openid . '" AND state = 1')->find();
			if ($wxse == null || $wxse == false) {
				$this->success('未绑定用户', U('login'), 0);
				exit;
			} else {
				$wxse['state'] = 0;
				$result = $se->where('wx="' . $get_openid . '" AND state = 1')->save($wxse);
				if ($result == flase) {
					echo "error :update error";
					exit;
				} else {
					$this->success('解绑成功', U('login'), 0);
					exit;
				}
			}
		} else {
			echo "error :no session";
			exit;
		}
	}

	public function myapp() {
		if (!session('?wxusername')) {
			$acc_code = $_GET["code"];

			$acc_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx09aaef70a0a8e448&secret=b5a7e32676db8bc0bdcd18f3402fa487&code=" . $acc_code . "&grant_type=authorization_code";
			//echo $acc_url;
			$main = new MyChat();
			$result = $main->wxRequest($acc_url);
			$resultinfo = json_decode($result, true);
			$get_openid = $resultinfo['openid'];
			//var_dump($resultinfo);
			session('wxusername', $get_openid);
		} else {
			$get_openid = session('wxusername'); //得到了openid
			//echo "openid:" . $get_openid;
		}
		$se = M('wxuser');
		$wxse = $se->where('wx="' . $get_openid . '" AND state = 1')->find();
		if ($wxse == null || $wxse == false) {

			$this->display();
		}
	}

	//组队申请
	public function partyapp() {
		$get_openid = session('wxusername');
		$partyid = $_GET['pid'];
		$p_db = M('party');
		$a_db = M('application');
		$se = M('wxuser');
		$wxse = $se->where('wx="' . $get_openid . '" AND state = 1')->find();
		if ($wxse == null || $wxse == false) {
			$this->success("请选择绑定一个账户", U('login'), 0);
			exit;
		}
		$nowparty = $p_db->where("id=" . $partyid . " AND state =1")->find();
		if ($nowparty == null || $nowparty == false) {
			$this->success("该活动不存在了", U('queryparty'), 0);
			exit;
		}
		if ($nowparty['userid'] == $wxse['userid']) {
			$this->success("不可以申请自己的活动", U('queryparty'), 0);
			exit;
		}
		$result = $a_db->where('userid="' . $wxse['userid'] . '" AND state=1 AND partyid=' . $nowparty['id'])->find();
		if ($result == null) {
			$newapp['userid'] = $wxse['userid'];
			$newapp['partyid'] = $nowparty['id'];
			$newapp['state'] = 1;
			$newapp['createtime'] = time();
			$a_db->add($newapp);
			$p_db = M('party');
			$a_db = M('application');
			//检查是否完成组队
			$applist = $a_db->where('partyid=' . $nowparty['id'] . ' AND state!=0')->select();

			$nowpartycout = intval($nowparty['need']);
			if ($nowpartycout == count($applist)) {
				//达成目标
				foreach ($applist as $key => $value) {
					# code...
					$data['state'] = 2;
					$a_db->where('id=' . $value['id'])->save($data);
				}
				$data['state'] = 2;
				$p_db->where('id=' . $nowparty['id'])->save($data);
			}
			$this->success("申请成功", U('queryparty'), 0);
			exit;
		} else {
			$this->success("你已经申请了", U('queryparty'), 0);
			exit;
		}

	}

	//组队查询
	public function queryparty() {
		if (!session('?wxusername')) {
			$acc_code = $_GET["code"];

			$acc_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx09aaef70a0a8e448&secret=b5a7e32676db8bc0bdcd18f3402fa487&code=" . $acc_code . "&grant_type=authorization_code";
			//echo $acc_url;
			$main = new MyChat();
			$result = $main->wxRequest($acc_url);
			$resultinfo = json_decode($result, true);
			$get_openid = $resultinfo['openid'];
			//var_dump($resultinfo);
			session('wxusername', $get_openid);
		} else {
			$get_openid = session('wxusername'); //得到了openid
			//echo "openid:" . $get_openid;
		}
		$partylist = M('party');
		$app = M('application');
		$list_res = $partylist->where('  state =1')->order('createtime desc')->select();
		foreach ($list_res as $key => $list_temp) {
			$list_res[$key]['datestr'] = date("Y-m-d H:i:s", $list_temp['createtime']);
			$appsearchlist = $app->where('partyid=' . $list_temp['id'] . ' AND state != 0')->select();
			$list_res[$key]['nowcount'] = count($appsearchlist);
			switch ($list_temp['ptype']) {
			case '1':
				$list_res[$key]['typetext'] = "比赛拼队";
				break;
			case '2':$list_res[$key]['typetext'] = "外卖拼团";
				break;
			case '3':$list_res[$key]['typetext'] = "出行拼车";
				break;
			default:
				break;
			}
		}
		$this->assign('list', $list_res);
		$this->display();
	}
	//组队详情
	public function partydetail() {
		$pid = $_GET['pid'];
		$par_db = M('party');
		$app_db = M('application');
		$user = M('users');
		$par = $par_db->where('id=' . $pid . ' AND state!=0')->find();
		if ($par == null || $par == false) {$this->success('组队信息不存在', U('queryparty'), 0);exit;}
		$this->assign('title', $par['title']);
		$this->assign('datastr', Date('Y-m-d H:i:s', $par['createtime']));
		switch ($par['ptype']) {
		case '1':
			$this->assign('typetext', "比赛拼队");
			break;
		case '2':$this->assign('typetext', "外卖拼团");
			break;
		case '3':$this->assign('typetext', "出行拼车");
			break;
		default:
			break;
		}
		$this->assign('information', $par['information']);
		$userdata = $user->where('id="' . $par["userid"] . '"')->find();
		$this->assign('username', $userdata['name']);
		$this->assign('need', $par['need']);

		$app_res = $app_db->where('partyid=' . $par['id'] . ' AND state != 0')->select();
		$this->assign('nowcount', count($app_res));
		foreach ($app_res as $key => $app_temp) {
			$userdata = $user->where('id="' . $app_temp["userid"] . '"')->find();
			$app_res[$key]['name'] = $userdata['name'];
			$app_res[$key]['mobile'] = $userdata['mobile'];
		}
		$this->assign("list", $app_res);
		$this->display();

	}

	//我的创建页面
	public function mycreateparty() {
		if (!session('?wxusername')) {
			$acc_code = $_GET["code"];

			$acc_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx09aaef70a0a8e448&secret=b5a7e32676db8bc0bdcd18f3402fa487&code=" . $acc_code . "&grant_type=authorization_code";
			//echo $acc_url;
			$main = new MyChat();
			$result = $main->wxRequest($acc_url);
			$resultinfo = json_decode($result, true);
			$get_openid = $resultinfo['openid'];
			//var_dump($resultinfo);
			session('wxusername', $get_openid);
		} else {
			$get_openid = session('wxusername'); //得到了openid
			//echo "openid:" . $get_openid;
		}
		$db_helper = new DB_Helper();
		$se = M('wxuser');
		$app = M('application');
		$wxse = $se->where('wx="' . $get_openid . '" AND state = 1')->find();
		if ($wxse == null || $wxse == false) {
			$this->success("请选择绑定一个账户", U('login'), 0);
		} else {
			$partylist = M('party');
			$list_res = $partylist->where('userid="' . $wxse['userid'] . '" AND state =1')->order('createtime desc')->select();
			foreach ($list_res as $key => $list_temp) {
				$list_res[$key]['datestr'] = date("Y-m-d H:i:s", $list_temp['createtime']);
				$appsearchlist = $app->where('partyid=' . $list_temp['id'] . ' AND state != 0')->select();
				$list_res[$key]['nowcount'] = count($appsearchlist);
				switch ($list_temp['ptype']) {
				case '1':
					$list_res[$key]['typetext'] = "比赛拼队";
					break;
				case '2':$list_res[$key]['typetext'] = "外卖拼团";
					break;
				case '3':$list_res[$key]['typetext'] = "出行拼车";
					break;
				default:
					break;
				}
			}
			$this->assign('list', $list_res);
			$this->display();
		}
	}

//取消组队的处理接口
	public function partydelete() {
		if (session("?wxusername")) {
			if (session("?ihadpost")) {
				session("ihadpost", null);
				$this->success('请勿重复提交表单', U('mycreateparty'), 0);
				exit;
			}
			$get_openid = session('wxusername'); //得到了openid
			$se = M('wxuser');
			$wxse = $se->where('wx="' . $get_openid . '" AND state = 1')->find();
			if ($wxse == null || $wxse == false) {
				$this->success("请选择绑定一个账户", U('login'), 0);
				exit;
			}

			$userid = $wxse['userid'];
			$app = M('party');
			$appid = $_GET['appid'];
			$res = $app->where('id=' . $appid . ' AND state = 1')->find();
			if ($res == null || $res == false) {
				$this->success('该活动不存在了', U('mycreateparty'), 0);
				exit;
			}
			if ($res['userid'] != $userid) {
				$this->success('休想删除别人的活动', U('mycreateparty'), 0);
				exit;
			}
			$data['state'] = 0;
			$result = $app->where('id=' . $appid)->save($data);
			if ($result == 0) {
				$this->success('删除失败', U('mycreateparty'), 0);
				exit;
			}
			$this->success('删除成功', U('mycreateparty'), 0);
			session("ihadpost", '1');
			exit;
		} else {
			echo "error :no session";
			exit;
		}
	}

	//组队创建
	public function partycreate() {
		$this->display();
	}

	//组队创建响应
	public function pcreate() {
		if (session("?wxusername")) {
			if (session("?ihadpost")) {
				session("ihadpost", null);
				$this->success('请勿重复提交表单', U('mycreateparty'), 0);
				exit;
			}
			$get_openid = session("wxusername");
			$sqldb = new DB_Helper();
			$se = M('wxuser');
			$wxse = $se->where('wx="' . $get_openid . '" AND state = 1')->find();
			if ($wxse == null || $wxse == false) {
				$this->success("请选择绑定一个账户", U('login'), 0);
			}
			$userid = $wxse['userid'];
			$pneed = $_GET['need'];
			$ptitle = $_GET['ptitle'];
			$ptype = $_GET['ptype'];
			$pinformation = $_GET['information'];
			$maindb = M('party');
			$newparty['userid'] = $userid;
			$newparty['need'] = $pneed;
			$newparty['createtime'] = time();
			$newparty['information'] = $pinformation;
			$newparty['state'] = 1;
			$newparty['ptype'] = $ptype;
			$newparty['title'] = $ptitle;
			$maindb->add($newparty);
			session('ihadpost', '1');
			$this->success('创建成功', U('mycreateparty'), 0);
			exit;
		} else {
			echo "error :no session";
			exit;
		}
	}

	//菜单创建
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
				"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx09aaef70a0a8e448&redirect_uri=https%3A%2F%2Frecyclerblacat.top%2Fpinpinpin%2Findex.php%2FHome%2FIndex%2Fqueryparty&response_type=code&scope=snsapi_bas
e&state=loveld#wechat_redirect"
			},
			{
				"name":"队伍相关",
				"sub_button":[
				{
					"name":"我的申请",
					"type":"view",
					"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx09aaef70a0a8e448&redirect_uri=https%3A%2F%2Frecyclerblacat.top%2Fpinpinpin%2Findex.php%2FHome%2FIndex%2Fmyapp&response_type=code&scope=snsapi_bas
e&state=loveld#wechat_redirect"
				},
				{
					"name":"我的创建",
					"type":"view",
					"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx09aaef70a0a8e448&redirect_uri=https%3A%2F%2Frecyclerblacat.top%2Fpinpinpin%2Findex.php%2FHome%2FIndex%2Fmycreateparty&response_type=code&scope=snsapi_bas
e&state=loveld#wechat_redirect"
				}]

			},
			{
				"name":"个人中心",
				"type":"view",
				"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx09aaef70a0a8e448&redirect_uri=https%3A%2F%2Frecyclerblacat.top%2Fpinpinpin%2Findex.php%2FHome%2FIndex%2Flogin&response_type=code&scope=snsapi_bas
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
			if (strstr($content, "刘丹")) {
				$result = $this->response_msg($toUsername, $fromUsername, "我爱你");
			} else {
				$result = $this->response_msg($toUsername, $fromUsername, $content);
			}echo $result;
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

class DB_Helper {
	//由openid获取绑定的用户id
	public function getYourID($openid) {
		$wxdb = M('wxuser');
		$result = $wxdb->where('wx="' . openid . '" AND state = 1')->find();
		if ($result == null || $result == flase) {
			return false;
		} else {
			return $result['userid'];
		}

	}
}