$(function () {
    //sidebar高度设置
    $(document).ready(function () {
        var sidebar_height = $('.content-wrap').height();
        $('.sidebar').height(sidebar_height);
    })
    //一级导航事件
    $('.menu li').click(function(){
        if($(this).hasClass('current-side-nav')){
            $(this).removeClass('current-side-nav');
            return;
        }
        $(this).addClass('current-side-nav').siblings().removeClass('current-side-nav');
    })
    //二级导航事件
    $('.menu-list a').click(function(){
        event.stopPropagation();
        $(this).addClass('current-list-nav').siblings().removeClass('current-list-nav');
    })
});