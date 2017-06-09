$(function(){
    //layer样式引入
    layer.config({
        extend: [
            'skin/tui88.css'
        ]
    });

    //**************时间戳或者日期的互相转化*****************
    $.extend({
        myTime: {
            /**
             * 当前时间戳
             * @return <int>        unix时间戳(秒)
             */
            CurTime: function () {
                return Date.parse(new Date()) / 1000;
            },
            /**
             * 日期 转换为 Unix时间戳
             * @param <string> 2014-01-01 20:20:20  日期格式
             * @return <int>        unix时间戳(秒)
             */
            DateToUnix: function (string) {
                var f = string.split(' ', 2);
                var d = (f[0] ? f[0] : '').split('-', 3);
                var t = (f[1] ? f[1] : '').split(':', 3);
                return (new Date(
                        parseInt(d[0], 10) || null,
                        (parseInt(d[1], 10) || 1) - 1,
                        parseInt(d[2], 10) || null,
                        parseInt(t[0], 10) || null,
                        parseInt(t[1], 10) || null,
                        parseInt(t[2], 10) || null
                    )).getTime() / 1000;
            },
            /**
             * 时间戳转换日期
             * @param <int> unixTime    待时间戳(秒)
             * @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)
             * @param <int>  timeZone   时区
             */
            UnixToDate: function (unixTime, isFull, timeZone) {
                if (unixTime === '') {
                    return '';
                } else {
                    if (typeof (timeZone) == 'number') {
                        unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
                    }
                    var time = new Date(unixTime * 1000);
                    var ymdhis = "";
                    ymdhis += time.getUTCFullYear() + "-";
                    ymdhis += (time.getUTCMonth() + 1) + "-";
                    ymdhis += time.getUTCDate();
                    if (isFull === true) {
                        ymdhis += " " + time.getUTCHours() + ":";
                        ymdhis += time.getUTCMinutes() + ":";
                        ymdhis += time.getUTCSeconds();
                    }
                    return ymdhis;
                }
            }
        }
    });
//*******************************

    //~~~~~~~~~判断领取和执行时间~~~~~~~~~
    $(".publish").on("click",function(){
        var receive_start = $('.receive-time').find("input[name = 'receive_start_time']").val();
        var receive_end = $('.receive-time').find("input[name = 'receive_end_time']").val();
        var execute_start = $('.execute-time').find("input[name = 'execute_start_time']").val();
        var execute_end = $('.execute-time').find("input[name = 'execute_end_time']").val();

        if(receive_start != '' && receive_end != ''){
            var receive_start_time = $.myTime.DateToUnix(receive_start);
            var receive_end_time = $.myTime.DateToUnix(receive_end);
            if(receive_start_time >= receive_end_time){
                wom_alert.msg({
                    content:"请选择正确的领取时间",
                    delay_time:1500
                })
                return false;
            }
        }else{
            wom_alert.msg({
                content:"请选择领取时间",
                delay_time:1500
            })
            return false;
        }

        if(execute_start != '' && execute_end != ''){
            var execute_start_time = $.myTime.DateToUnix(execute_start);
            var execute_end_time = $.myTime.DateToUnix(execute_end);
            if(execute_start_time >= execute_end_time){
                wom_alert.msg({
                    content:"请选择正确的执行时间",
                    delay_time:1500
                })
                return false;
            }
        }else{
            wom_alert.msg({
                content:"请选择执行时间",
                delay_time:1500
            })
            return false;
        }
    })

    // 任务总额
    function countTotalMoney() {
        var task_amount = $('.customer-account input').val();
        var task_unit_price = $('.task-unit-price input').val();

        if(task_amount != "" && task_unit_price != ""){
            if(isNaN(task_amount)||isNaN(task_unit_price)){
                wom_alert.msg({
                    content:"数量/金额应为数字",
                    delay_time:1500
                })
                return false;
            }else{
                $('.total-price i').text(task_amount * task_unit_price);
            }
        }else{
            wom_alert.msg({
                content:"请填写任务数量/金额",
                delay_time:1500
            })
        }
    }

    $('.task-unit-price input').on('blur',function () {
        countTotalMoney();
    })

})

