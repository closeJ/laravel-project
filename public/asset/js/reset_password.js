$(function(){
	$("input[name='check'],input[name='autoPass']").prop('checked',true);
	$("input[name='check'],input[name='autoPass']").prop('disabled',true);
	$(".hidegame").hide();
	$('input[name="autoPass"]').click(function(){
		if($(this).is(':checked') === true){
			$('#password,#password-confirm').prop('disabled',true);
		} else {
			$('#password,#password-confirm').prop('disabled',false);
		}
	});

	if($("input[name='check']").is(" :checked") === true) {
		var resetId = $("#reset_id").val();
		$.ajax({
				url:'./distributor/auto_email',
				type:'GET',
				data:{"resetId":resetId},
				success:function(data){
					$("input[name='auto_email']").val(data);
				}
		});
	}

	var toggle = 0;
	$(".countRow").click(function(){
		if (toggle == 0) {
			$(".hidegame").fadeIn('fast');
			toggle = 1;
		} else {
			$(".hidegame").fadeOut('fast');
			toggle = 0;
		}
	});
});