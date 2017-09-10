<?php if (!defined('THINK_PATH')) exit();?>﻿ <!DOCTYPE html>
 <html>
 <head>
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>WeUI</title>
<link rel="stylesheet" href="/pinpinpin/Public/css/weui.css"/>
    <link rel="stylesheet" href="/pinpinpin/Public/css/example.css"/>
 </head>
 <body>
 	<div class="page__hd">
        <h1 class="page__title">您还没有绑定账户</h1>
        <p class="page__desc">用户绑定</p>
    </div>
 	<form method="POST" class="weui-cells weui-cells_form" action="<?php echo U('userbind');?>">
 			<div class="weui-cell">
              	<div class="weui-cell__hd"><label class="weui-label">学号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="userid" type="number" pattern="[0-9]*" placeholder="请输入学号">
                </div>
            </div>

            <div class="weui-cell">
              	<div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="username"  placeholder="请输入姓名">
                </div>
            </div>
        		<button id="getbind" class="weui-btn weui-btn_primary">确认绑定</button>
 	</form>

 	<script src="/pinpinpin/Public/js/zepto.min.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="https://res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>
    <script src="/pinpinpin/Public/js/example.js"></script>
 </body>
 </html>