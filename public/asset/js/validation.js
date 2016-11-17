$(function() {
    initForm();
});

function initForm() {
    //表單驗證
    var form = $('#modify_form');
    var prevUrl = $('[name="prev_url"]').val();

    form.ajaxForm({
        dataType: 'json',
        success: function(response) {
            if (response.status === 'fail') {
                $.alert({
                    title: false,
                    content: response.message,
                    confirmButton: 'OK',
                    theme: 'material',
                });
            } else {
                if (response.data) {
                    data = response.data;
                } else {
                    data = response.status;
                }
                $.alert({
                    title:false,
                    content:data,
                    theme: 'material',
                    onClose:function(){
                        window.location = prevUrl;
                    }
                });

            }
        }
    });
}
