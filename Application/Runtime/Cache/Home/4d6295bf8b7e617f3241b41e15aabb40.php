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
        <h1 class="page__title">用户中心</h1>
        <p class="page__desc">个人信息</p>
    </div>
 	<div class="weui-cells weui-cells_form">
 			<div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">学号</label></div>
                <div class="weui-cell__bd"><label class="weui-label"><?php echo ($userid); ?></label></div>
            </div>

            <div class="weui-cell">
              	<div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
                <div class="weui-cell__bd"><label class="weui-label"><?php echo ($username); ?></label></div>
            </div>
        	<div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">性别</label></div>
                <div class="weui-cell__bd"><label class="weui-label"><?php echo ($sex); ?></label></div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
                <div class="weui-cell__bd"><label class="weui-label"><?php echo ($mobile); ?></label></div>
            </div>
            <a href="<?php echo U('removebind');?>" class="weui-btn weui-btn_warn" id="showIOSDialog1">解除绑定</a>
 	</div>
    <div id = "dialogs">
    <div class="js_dialog" id="iosDialog1" style="opacity: 0; display: none;">
            <div class="weui-mask"></div>
            <div class="weui-dialog">
                <div class="weui-dialog__hd"><strong class="weui-dialog__title">解除绑定</strong></div>
                <div class="weui-dialog__bd">你真的要解除绑定吗</div>
                <div class="weui-dialog__ft">
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">我再想想</a>
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">我确认</a>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" class="dialog js_show">
    $(function(){
        var $iosDialog1 = $('#iosDialog1'),

        $('#dialogs').on('click', '.weui-dialog__btn', function(){
            $(this).parents('.js_dialog').fadeOut(200);
        });

        $('#showIOSDialog1').on('click', function(){
            $iosDialog1.fadeIn(200);
        });
    });</script>
 	<script src="/pinpinpin/Public/js/zepto.min.js"></script>
    <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="https://res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>
    <script src="/pinpinpin/Public/js/example.js"></script>
 </body>
 </html>