$(function () {
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', '點擊此階層可以收合');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', '點擊此階層可以收合');
        } else {
            children.show('fast');
            $(this).attr('title', '點擊此階層可以收合');
        }
        e.stopPropagation();
    });
    $("#checkAll").click(function(){
        if($(this).prop('checked')){//如果全選按鈕有被選擇的話（被選擇是true）
            $("input[name='permissions[]']").each(function(){
                $(this).prop('checked',true);////把所有的核取方框的property都變成勾選
            });
        } else {
            $("input[name='permissions[]']").each(function(){
                $(this).prop('checked',false);
            });
        }
    });
});