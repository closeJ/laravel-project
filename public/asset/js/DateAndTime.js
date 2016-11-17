$(function(){
	initdate();
	initime();
});

//選擇日期
function initdate()
{
    $('input[name="start_date"],input[name="end_date"]').bootstrapMaterialDatePicker({
        lang:'zh-tw',//日期顯示繁體中文
        weekStart :0,
        time:false,
        cancelText:'取消',
        okText:'確定',
        clearButton:false,
    });
}
//選擇日期+時間
function initime()
{
    $('input[name="start_time"],input[name="end_time"]').bootstrapMaterialDatePicker({
        lang:'zh-tw',//日期顯示繁體中文
        weekStart :0,
        format:'YYYY-MM-DD HH:mm:ss',
        minDate:new Date(),//預設顯示現在時間
        cancelText:'取消',
        okText:'確定',
        clearButton:false,
    });
}