<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>简易登录界面 - bjyadmin</title>
        <meta http-equiv="Cache-Control" content="no-transform" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="/Public/statics/bootstrap-3.3.5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/Public/statics/bootstrap-3.3.5/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="/Public/statics/font-awesome-4.4.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/tpl/Public/css/base.css" />
    <link rel="stylesheet" href="/Public/statics/webuploader-0.1.5/xb-webuploader.css">
<script src="/Public/statics/js/jquery-1.10.2.min.js"></script>
    <style type="text/css">
        .box {
            margin-top: 50px;
            padding: 5px;
            width: 600px;
            border: 1px solid red;
        }

        h4 {
            padding: 5px 0;
        }
    </style>
</head>
<body><p> 当前状态：<?php echo ($data); ?></p>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/67">thinkphp整合系列之rbac的升级版auth权限管理系统demo</a>
</h4> 超级管理员登录：
    <form method="post"> 用户名：<input type="text" name="username" value="admin"> 密码：<input type="text" name="password"
                                                                                         value="123456"> <input
            type="submit" value="登录"></form>
    <br/> 文章管理员登录：
    <form method="post"> 用户名：<input type="text" name="username" value="admin2"> 密码：<input type="text" name="password"
                                                                                          value="123456"> <input
            type="submit" value="登录"></form>
    <br/> <a href="<?php echo U('Home/Index/logout');?>">退出登录</a></div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/71">thinkphp整合系列之phpqrcode生成二维码</a></h4>
    <form action="<?php echo U('Home/Index/qrcode');?>" method="post"> 输入连接：http://<input type="text" name="url"
                                                                               value="baijunyao.com"> <input
            type="submit" value="生成二维码"></form>
</div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/69">thinkphp整合系列之phpmailer批量发送邮件</a></h4>
    <form action="<?php echo U('Home/Index/send_email');?>" method="post"> 输入邮箱：<input type="text" name="email"
                                                                            value="baijunyao@baijunyao.com"> <input
            type="submit" value="发送邮件"></form>
</div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/75">thinkphp整合系列之支付宝MD5加密方式扫码支付</a></h4>
    <form action="<?php echo U('Home/Index/alipay');?>" method="post"> 输入金额：<input type="text" name="price" value="1"> <input
            type="submit" value="生成支付宝订单"></form>
</div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/73">thinkphp整合系列之tcpdf生成pdf文件</a></h4>
    <form action="<?php echo U('Home/Index/pdf');?>" method="post"> 输入内容：<textarea name="content" cols="30" rows="10"><h1
            style="color:red">hello word</h1></textarea> <input type="submit" value="生成pdf"></form>
</div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/72">thinkphp整合系列之融云即时通讯在线聊天</a></h4>
    请使用2个不同的浏览器分别打开着两个链接即可聊天：<a href="<?php echo U('Home/Index/user1');?>" target="_blank">用户1</a>&emsp;<a
            href="<?php echo U('Home/Index/user2');?>">用户2</a></div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/74">thinkphp整合系列之phpexcel生成生成excel文件</a></h4>
    <form action="<?php echo U('Home/Index/xls');?>" method="post">
        <table border="1">
            <tr>
                <td><input style="width: 25px;" type="text" name="data[1][1]" value="11"></td>
                <td><input style="width: 25px;" type="text" name="data[1][2]" value="12"></td>
                <td><input style="width: 25px;" type="text" name="data[1][3]" value="13"></td>
            </tr>
            <tr>
                <td><input style="width: 25px;" type="text" name="data[2][1]" value="21"></td>
                <td><input style="width: 25px;" type="text" name="data[2][2]" value="22"></td>
                <td><input style="width: 25px;" type="text" name="data[2][3]" value="23"></td>
            </tr>
            <tr>
                <td><input style="width: 25px;" type="text" name="data[3][1]" value="31"></td>
                <td><input style="width: 25px;" type="text" name="data[3][2]" value="32"></td>
                <td><input style="width: 25px;" type="text" name="data[3][3]" value="33"></td>
            </tr>
        </table>
        <input type="submit" value="生成xls格式表格"></form>
</div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/79">生成csv格式表格</a></h4>
    <form action="<?php echo U('Home/Index/csv');?>" method="post">
        <table border="1">
            <tr>
                <td><input style="width: 25px;" type="text" name="data[1][1]" value="11"></td>
                <td><input style="width: 25px;" type="text" name="data[1][2]" value="12"></td>
                <td><input style="width: 25px;" type="text" name="data[1][3]" value="13"></td>
            </tr>
            <tr>
                <td><input style="width: 25px;" type="text" name="data[2][1]" value="21"></td>
                <td><input style="width: 25px;" type="text" name="data[2][2]" value="22"></td>
                <td><input style="width: 25px;" type="text" name="data[2][3]" value="23"></td>
            </tr>
            <tr>
                <td><input style="width: 25px;" type="text" name="data[3][1]" value="31"></td>
                <td><input style="width: 25px;" type="text" name="data[3][2]" value="32"></td>
                <td><input style="width: 25px;" type="text" name="data[3][3]" value="33"></td>
            </tr>
        </table>
        <input type="submit" value="生成csv格式表格"></form>
</div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/82">thinkphp整合系列之phpexcel导入excel数据</a></h4>
    <a href="<?php echo U('Home/Index/import_xls');?>" target="_blank">导入xls格式文件</a> &emsp; <a href="<?php echo U('Home/Index/import_csv');?>"
                                                                                    target="_blank">导入csv格式文件</a></div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/77">thinkphp整合系列之微信扫码支付</a></h4> <a
        href="<?php echo U('Home/Index/weixinpay_qrcode');?>" target="_blank">点击生成一个1分钱的微信支付二维码</a><br/></div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/78">thinkphp整合系列之微信公众号支付</a></h4> <a
        href="<?php echo U('Home/Index/weixinpay_js');?>" target="_blank">在微信中点击生成一个1分钱的公众号支付订单</a></div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/79">thinkphp整合系列之极验滑动验证码</a></h4>
    <form id="geetest" action="<?php echo U('Home/Index/geetest_submit_check');?>" method="post"> 账号：<input type="text"
                                                                                                 name="username"
                                                                                                 value="admin">
        密码：<input type="text" name="password" value="123456"> <input type="button" value="异步验证登录"
                                                                     onclick="check_verify()"> <input type="submit"
                                                                                                      value="post提交登录">
        <div id="captcha"></div>
    </form>
</div>
<div class="box"><h4><a target="_blank" href="http://baijunyao.com/article/80">thinkphp整合系列之webuploader异步预览上传</a></h4>
    <form id="geetest" action="<?php echo U('Home/Index/webuploader');?>" method="post">
        <div id="upload-5a1296f315836" class="xb-uploader">
    <div class="queueList">
        <div class="placeholder">
            <div class="filePicker"></div>
            <p>或将照片拖到这里，单次最多可选300张</p>
        </div>
    </div>
    <div class="statusBar" style="display:none;">
        <div class="progress">
            <span class="text">0%</span>
            <span class="percentage"></span>
        </div>
        <div class="info"></div>
        <div class="btns">
            <div class="webuploader-container filePicker2">
                <div class="webuploader-pick">继续添加</div>
                <div style="position: absolute; top: 0px; left: 0px; width: 1px; height: 1px; overflow: hidden;" id="rt_rt_1armv2159g1o1i9c2a313hadij6">
                </div>
            </div>
            <div class="uploadBtn">开始上传</div>
        </div>
    </div>
</div>
<script>
jQuery(function() {
    var $ = jQuery,    // just in case. Make sure it's not an other libaray.

        $wrap = $("#upload-5a1296f315836"),

        // 图片容器
        $queue = $('<ul class="filelist"></ul>')
            .appendTo( $wrap.find('.queueList') ),

        // 状态栏，包括进度和控制按钮
        $statusBar = $wrap.find('.statusBar'),

        // 文件总体选择信息。
        $info = $statusBar.find('.info'),

        // 上传按钮
        $upload = $wrap.find('.uploadBtn'),

        // 没选择文件之前的内容。
        $placeHolder = $wrap.find('.placeholder'),

        // 总体进度条
        $progress = $statusBar.find('.progress').hide(),

        // 添加的文件数量
        fileCount = 0,

        // 添加的文件总大小
        fileSize = 0,

        // 优化retina, 在retina下这个值是2
        ratio = window.devicePixelRatio || 1,

        // 缩略图大小
        thumbnailWidth = 110 * ratio,
        thumbnailHeight = 110 * ratio,

        // 可能有pedding, ready, uploading, confirm, done.
        state = 'pedding',

        // 所有文件的进度信息，key为file id
        percentages = {},

        supportTransition = (function(){
            var s = document.createElement('p').style,
                r = 'transition' in s ||
                      'WebkitTransition' in s ||
                      'MozTransition' in s ||
                      'msTransition' in s ||
                      'OTransition' in s;
            s = null;
            return r;
        })(),
        thisSuccess,
        // WebUploader实例
        uploader;

    if ( !WebUploader.Uploader.support() ) {
        alert( 'Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器');
        throw new Error( 'WebUploader does not support the browser you are using.' );
    }

    // 实例化
    uploader = WebUploader.create({
        pick: {
            id: "#upload-5a1296f315836 .filePicker",
            label: '点击选择文件',
            multiple : true
        },
        dnd: "#upload-5a1296f315836 .queueList",
        paste: document.body,
        // accept: {
        //     title: 'Images',
        //     extensions: 'gif,jpg,jpeg,bmp,png',
        //     mimeTypes: 'image/*'
        // },

        // swf文件路径
        swf: BASE_URL + '/Uploader.swf',

        disableGlobalDnd: true,

        chunked: true,
        server: "/index.php/Home/Index/ajax_upload",
        fileNumLimit: 300,
        fileSizeLimit: 200 * 1024 * 1024,    // 200 M
        fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
    });

    // 添加“添加文件”的按钮，
    uploader.addButton({
       id: "#upload-5a1296f315836 .filePicker2",
       label: '继续添加'
    });

    // 当有文件添加进来时执行，负责view的创建
    function addFile( file ) {
        var $li = $( '<li id="' + file.id + '">' +
                '<p class="title">' + file.name + '</p>' +
                '<p class="imgWrap"></p>'+
                '<p class="progress"><span></span></p>' +
                '<input class="bjy-filename" type="hidden" name="image[]">'+
                '</li>' ),

            $btns = $('<div class="file-panel">' +
                '<span class="cancel">删除</span>' +
                '<span class="rotateRight">向右旋转</span>' +
                '<span class="rotateLeft">向左旋转</span></div>').appendTo( $li ),
            $prgress = $li.find('p.progress span'),
            $wrap = $li.find( 'p.imgWrap' ),
            $info = $('<p class="error"></p>'),

            showError = function( code ) {
                switch( code ) {
                    case 'exceed_size':
                        text = '文件大小超出';
                        break;

                    case 'interrupt':
                        text = '上传暂停';
                        break;

                    default:
                        text = '上传失败，请重试';
                        break;
                }

                $info.text( text ).appendTo( $li );
            };

        if ( file.getStatus() === 'invalid' ) {
            showError( file.statusText );
        } else {
            // @todo lazyload
            $wrap.text( '预览中' );
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    $wrap.text( '不能预览' );
                    return;
                }

                var img = $('<img src="'+src+'">');
                $wrap.empty().append( img );
            }, thumbnailWidth, thumbnailHeight );

            percentages[ file.id ] = [ file.size, 0 ];
            file.rotation = 0;
        }

        file.on('statuschange', function( cur, prev ) {
            if ( prev === 'progress' ) {
                $prgress.hide().width(0);
            } else if ( prev === 'queued' ) {
                $li.off( 'mouseenter mouseleave' );
                $btns.remove();
            }

            // 成功
            if ( cur === 'error' || cur === 'invalid' ) {
                showError( file.statusText );
                percentages[ file.id ][ 1 ] = 1;
            } else if ( cur === 'interrupt' ) {
                showError( 'interrupt' );
            } else if ( cur === 'queued' ) {
                percentages[ file.id ][ 1 ] = 0;
            } else if ( cur === 'progress' ) {
                $info.remove();
                $prgress.css('display', 'block');
            } else if ( cur === 'complete' ) {
                $li.append( '<span class="success"></span>' );
            }

            $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
        });

        $li.on( 'mouseenter', function() {
            $btns.stop().animate({height: 30});
        });

        $li.on( 'mouseleave', function() {
            $btns.stop().animate({height: 0});
        });

        $btns.on( 'click', 'span', function() {
            var index = $(this).index(),
                deg;

            switch ( index ) {
                case 0:
                    uploader.removeFile( file );
                    return;

                case 1:
                    file.rotation += 90;
                    break;

                case 2:
                    file.rotation -= 90;
                    break;
            }

            if ( supportTransition ) {
                deg = 'rotate(' + file.rotation + 'deg)';
                $wrap.css({
                    '-webkit-transform': deg,
                    '-mos-transform': deg,
                    '-o-transform': deg,
                    'transform': deg
                });
            } else {
                $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                // use jquery animate to rotation
                // $({
                //     rotation: rotation
                // }).animate({
                //     rotation: file.rotation
                // }, {
                //     easing: 'linear',
                //     step: function( now ) {
                //         now = now * Math.PI / 180;

                //         var cos = Math.cos( now ),
                //             sin = Math.sin( now );

                //         $wrap.css( 'filter', "progid:DXImageTransform.Microsoft.Matrix(M11=" + cos + ",M12=" + (-sin) + ",M21=" + sin + ",M22=" + cos + ",SizingMethod='auto expand')");
                //     }
                // });
            }


        });

        $li.appendTo( $queue );
    }

    // 负责view的销毁
    function removeFile( file ) {
        var $li = $('#'+file.id);

        delete percentages[ file.id ];
        updateTotalProgress();
        $li.off().find('.file-panel').off().end().remove();
    }

    function updateTotalProgress() {
        var loaded = 0,
            total = 0,
            spans = $progress.children(),
            percent;

        $.each( percentages, function( k, v ) {
            total += v[ 0 ];
            loaded += v[ 0 ] * v[ 1 ];
        } );

        percent = total ? loaded / total : 0;

        spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
        spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
        updateStatus();
    }

    function updateStatus() {
        var text = '', stats;

        if ( state === 'ready' ) {
            text = '选中' + fileCount + '个文件，共' +
                    WebUploader.formatSize( fileSize ) + '。';
        } else if ( state === 'confirm' ) {
            stats = uploader.getStats();
            if ( stats.uploadFailNum ) {
                text = '已成功上传' + stats.successNum+ '个文件，'+
                    stats.uploadFailNum + '个上传失败，<a class="retry" href="#">重新上传</a>失败文件或<a class="ignore" href="#">忽略</a>'
            }

        } else {
            stats = uploader.getStats();
            text = '共' + fileCount + '个（' +
                    WebUploader.formatSize( fileSize )  +
                    '），已上传' + stats.successNum + '个';

            if ( stats.uploadFailNum ) {
                text += '，失败' + stats.uploadFailNum + '个';
            }
            if (fileCount==stats.successNum && stats.successNum!=0) {
                $('#upload-5a1296f315836 .webuploader-element-invisible').remove();
            }
        }

        $info.html( text );
    }

    uploader.onUploadAccept=function(object ,ret){
        if(ret.error_info){
            fileError=ret.error_info;
            return false;
        }
    }

    uploader.onUploadSuccess=function(file ,response){
        $('#'+file.id +' .bjy-filename').val(response.name)
    }
    uploader.onUploadError=function(file){
        alert(fileError);
    }

    function setState( val ) {
        var file, stats;
        if ( val === state ) {
            return;
        }

        $upload.removeClass( 'state-' + state );
        $upload.addClass( 'state-' + val );
        state = val;

        switch ( state ) {
            case 'pedding':
                $placeHolder.removeClass( 'element-invisible' );
                $queue.parent().removeClass('filled');
                $queue.hide();
                $statusBar.addClass( 'element-invisible' );
                uploader.refresh();
                break;

            case 'ready':
                $placeHolder.addClass( 'element-invisible' );
                $( "#upload-5a1296f315836 .filePicker2" ).removeClass( 'element-invisible');
                $queue.parent().addClass('filled');
                $queue.show();
                $statusBar.removeClass('element-invisible');
                uploader.refresh();
                break;

            case 'uploading':
                $( "#upload-5a1296f315836 .filePicker2" ).addClass( 'element-invisible' );
                $progress.show();
                $upload.text( '暂停上传' );
                break;

            case 'paused':
                $progress.show();
                $upload.text( '继续上传' );
                break;

            case 'confirm':
                $progress.hide();
                $upload.text( '开始上传' ).addClass( 'disabled' );

                stats = uploader.getStats();
                if ( stats.successNum && !stats.uploadFailNum ) {
                    setState( 'finish' );
                    return;
                }
                break;
            case 'finish':
                stats = uploader.getStats();
                if ( stats.successNum ) {
                    
                } else {
                    // 没有成功的图片，重设
                    state = 'done';
                    location.reload();
                }
                break;
        }
        updateStatus();
    }

    uploader.onUploadProgress = function( file, percentage ) {
        var $li = $('#'+file.id),
            $percent = $li.find('.progress span');

        $percent.css( 'width', percentage * 100 + '%' );
        percentages[ file.id ][ 1 ] = percentage;
        updateTotalProgress();
    };

    uploader.onFileQueued = function( file ) {
        fileCount++;
        fileSize += file.size;

        if ( fileCount === 1 ) {
            $placeHolder.addClass( 'element-invisible' );
            $statusBar.show();
        }

        addFile( file );
        setState( 'ready' );
        updateTotalProgress();
    };

    uploader.onFileDequeued = function( file ) {
        fileCount--;
        fileSize -= file.size;

        if ( !fileCount ) {
            setState( 'pedding' );
        }

        removeFile( file );
        updateTotalProgress();

    };

    uploader.on( 'all', function( type ) {
        var stats;
        switch( type ) {
            case 'uploadFinished':
                setState( 'confirm' );
                break;

            case 'startUpload':
                setState( 'uploading' );
                break;

            case 'stopUpload':
                setState( 'paused' );
                break;

        }
    });

    uploader.onError = function( code ) {
        alert( 'Eroor: ' + code );
    };

    $upload.on('click', function() {
        if ( $(this).hasClass( 'disabled' ) ) {
            return false;
        }

        if ( state === 'ready' ) {
            uploader.upload();
        } else if ( state === 'paused' ) {
            uploader.upload();
        } else if ( state === 'uploading' ) {
            uploader.stop();
        }
    });

    $info.on( 'click', '.retry', function() {
        uploader.retry();
    } );

    $info.on( 'click', '.ignore', function() {
        alert( 'todo' );
    } );

    $upload.addClass( 'state-' + state );
    updateTotalProgress();
});
</script>
        自定义附加字段：<input type="text" name="extend" value="上传的文件描述"> <input type="submit" value="提交"></form>
    <a href="<?php echo U('Home/Index/webuploader');?>" target="_blank">点击在新页面中打开一个上传demo</a></div>
<!-- 引入bootstrjs部分开始 -->
<script src="/Public/statics/js/jquery-1.10.2.min.js"></script>
<script src="/Public/statics/bootstrap-3.3.5/js/bootstrap.min.js"></script>
<script src="/tpl/Public/js/base.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script>
    var BASE_URL = '/Public/statics/webuploader-0.1.5';
</script>
<script src="//cdn.staticfile.org/webuploader/0.1.5/webuploader.min.js"></script>
<script>
    var handler = function (captchaObj) {
        // 将验证码加到id为captcha的元素里
        captchaObj.appendTo("#captcha");
    };
    // 获取验证码
    $.get("<?php echo U('Home/Index/geetest_show_verify');?>", function (data) {
        // 使用initGeetest接口
        // 参数1：配置参数，与创建Geetest实例时接受的参数一致
        // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
        initGeetest({
            gt: data.gt,
            challenge: data.challenge,
            product: "float", // 产品形式
            offline: !data.success
        }, handler);
    }, 'json');
    // 检测验证码
    function check_verify() {
        // 组合验证需要用的数据
        var test = $('.geetest_challenge').val();
        var postData = {
            geetest_challenge: $('.geetest_challenge').val(),
            geetest_validate: $('.geetest_validate').val(),
            geetest_seccode: $('.geetest_seccode').val()
        }
        // 验证是否通过
        $.post("<?php echo U('Home/Index/geetest_ajax_check');?>", postData, function (data) {
            if (data == 1) {
                alert('验证成功');
            } else {
                alert('验证失败');
            }
        });
    }
</script>
</body>
</html>