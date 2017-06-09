/**
 ** wom弹框使用方法

 * 先引入layer.js,再引入wom-tool.js

 * ① 消息提示框：
 wom_alert.msg({
		icon:"",    	icon图标。有以下四种 "finish" / "error" / "info" / "warning" ,分别代表 完成(✔️),错误(×),询问(?),警告(!) 的icon.
		content:"",		这里输入提示消息的内容
		delay_time:     提示框自动消失时间
	})


 Example:

 $(".alert").on("click",function(){
		wom_alert.msg({
			icon:"warning",
			content:"消息提示框，最好别超过15个字哦",
			delay_time:2000
		})
	})



 * ② 消息确认框：
 wom_alert.alert({
		icon:"",		icon图标
		title:"", 		输入确认框标题（备用，可以不使用）
		content:""		输入需确认的内容
	})


 Example:

 $(".confirm").on("click",function(){
		wom_alert.alert({
			icon: "warning",
			content:"沃米alert确认框",
		})
	})



 * ③ 确定、取消操作框
 wom_alert.confirm({
            content:"确定删除该资源吗？"      确定取消的内容
        },function(){

            // write your code here...      自己的函数代码

            wom_alert.msg({
                icon:"",
                content:"",
                delay_time:
            })

        })


 Example:

 $(".confirm-cancle").on("click",function(){
        wom_alert.confirm({
            content:"确定删除该资源吗？"
        },function(){

            // write your code here...

            wom_alert.msg({
                icon:"finish",
                content:"删除成功!",
                delay_time:1000
            })

        })
	})
 *
 **/

var wom_alert = {
    msg:function(config){
        if(config.icon == "finish"){
            config.icon = 1;
        }
        if(config.icon == "error"){
            config.icon = 2;
        }
        if(config.icon == "info"){
            config.icon = 3;
        }
        if(config.icon == "warning"){
            config.icon = 7;
        }
        layer.msg(config.content,{
            icon:config.icon,
            time:config.delay_time,
        });
    },
    alert:function(config){
        if(config.icon == "finish"){
            config.icon = 1;
        }
        if(config.icon == "error"){
            config.icon = 2;
        }
        if(config.icon == "info"){
            config.icon = 3;
        }
        if(config.icon == "warning"){
            config.icon = 7;
        }
        layer.alert(config.content,{
            icon:config.icon,
            title:config.title
        });
    },
    confirm:function(config,func){
        layer.confirm(config.content, {
            btn: ['确定','取消'],
        }, func)
    }
};

/**
 ** 限制文本长度,超出部分以省略号的形式展示.
 * 使用方法: 给存放文本的标签添加class类名 class = "plain-text-length-limit" ,设置属性 data-limit = "num" , num为想要限制的字数,根据自己需要设置.
 **/
(function plainContentLengthLimit(){
    $('.plain-text-length-limit').each(function(){
        var content = $(this).text().trim();
        var length_limit = $(this).attr('data-limit');
        var content_length = content.length;

        if(length_limit == undefined){
            length_limit = 5;
        }

        if(content_length > length_limit){
            $(this).text(content.substr(0, length_limit) + '...');
        }
        $(this).attr('data-value', content);
    })
})();