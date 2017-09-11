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
        <h1 class="page__title">活动创建</h1>
        <p class="page__desc">输入活动信息</p>
    </div>
 	<form method="GET" class="weui-cells weui-cells_form" action="<?php echo U('userbind');?>">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">活动标题</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="ptitle" type="text"  placeholder="请输入标题">
                </div>
            </div>
 			<div class="weui-cell">
              	<div class="weui-cell__hd"><label class="weui-label">需要人数</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="need" type="number" pattern="[0-9]*" placeholder="请输入人数">
                </div>
            </div>
            <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">活动类型</label></div>
            <div class="weui-cell__bd">
                <select class="weui-select" name="ptype">
                        <option selected="" value="1">比赛拼队</option>
                        <option value="2">外卖拼团</option>
                        <option value="3">出行拼车</option>
                </select>
            </div>
            </div>
            <div class="weui-cell">
              	<div class="weui-cell__hd"><label class="weui-label">活动详情</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="username"  placeholder="请输入详细信息">
                </div>
            </div>
        		<button id="getbind" class="weui-btn weui-btn_primary">确认提交</button>
 	</form>

 	<script src="/pinpinpin/Public/js/zepto.min.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="https://res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>
    <script src="/pinpinpin/Public/js/example.js"></script>
 </body>
 </html>