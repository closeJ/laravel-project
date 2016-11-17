$(function() {
    $("#admin_add").click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: './admin/getId',
            data: { "id": id },
            type: "GET",
            success: function() {
                console.log("ok");
            }
        });
    });
    $('#insert_admin').click(function() {
        var admin_id = $('select[name="admin_name"]').val();
        var admin_txt = $('select[name="admin_name"] :selected').text();
        var userRoles = [];
        $(".users_role:checked").each(function() {
            userRoles.push($(this).val());
        });
        if (userRoles == '') {
            $.alert("請至少要勾選一個群組");
        } else {
            $.ajax({
                url: './admin/manage',
                data: { "admin_id": admin_id, "userRoles": userRoles },
                type: "POST",
                success: function(data) {
                    $.alert({
                        title: false,
                        content: '新增完畢',
                        confirmButton: 'OK',
                        onClose: function() {
                            window.location.reload();
                        }
                    })
                }
            });
        }
    });
});
