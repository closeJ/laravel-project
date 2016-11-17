$(function() {
    $("#autoPass").prop('checked', true);
    $("#autoPass").prop('disabled', true);

    /*  新增總代理帳號 */
    $('#insert_proxy , #insert_platform').on('click', function() {
        if ($("#insert_proxy").length == 1) {
            var autoPass = $('#autoPass:checked').val();
            var name = $('input[name="name"]').val();
            var country = $('input[name="country"]').val();
            var phone = $('input[name="phone"]').val();
            var companyPhone = $('input[name="companyPhone"]').val();
            var address = $('input[name="address"]').val();
            var email = $('input[name="email"]').val();
            var current = $('input[name="current"]').val();
            var credit = $('input[name="credit"]').val();
            var comission = $('input[name="comission"]:checked').val();
            var comiddion_yes = $('input[name="comiddion_yes"]').val();
            var role = $('input[name="role"]').val();
            var data = {
                "autoPass": autoPass,
                "name": name,
                "country": country,
                "phone": phone,
                "companyPhone": companyPhone,
                "address": address,
                "email": email,
                "current": current,
                "credit": credit,
                "comission": comission,
                "comiddion_yes": comiddion_yes,
                "role": role,
            };
        } else if($("#insert_platform").length == 1) {
            var autoPass = $('#autoPass:checked').val();
            var current = $('input[name="current"]').val();
            var credit = $('input[name="credit"]').val();
            var comission = $('input[name="comission"]:checked').val();
            var comiddion_yes = $('input[name="comiddion_yes"]').val();
            var role = $('input[name="role"]').val();
            var platform = $('select[name="platform"]').val();
            var data = {
            "autoPass":autoPass,
            "current": current,
            "credit": credit,
            "comission": comission,
            "comiddion_yes": comiddion_yes,
            "role": role,
            "platform":platform
            };
        }
        if (name == '' || current == '' || email == '' || phone == '') {
            $.alert('請填寫完整資訊');
            return false;
        }
        $.ajax({
            url: './other/valian',
            type: 'POST',
            data: data,
            success: function(data) {
                var alertName = data.name.split("|");
                $.alert({
                    title: '用戶詳情',
                    content: alertName[0] + ' : ' + data.username + '<br>' + alertName[1] + ' : ' + data.password + '<br>信用餘額:' + data.credit + '<br>' + data.email,
                    confirmButton: '確定',
                    theme: 'material',
                    onClose: function() {
                        parent.window.location = window.location.href;
                    }
                })
            }
        });

    });

    /* 編輯封鎖 */
    $('#lock').click(function() {
                var id = $(this).data('lock');
                $.confirm({
                    title: false,
                    content: '確定是否封鎖?',
                    cancelButton: '取消',
                    confirmButton: '確定',
                    theme: 'material',
                    confirm: function() {
                        $.ajax({
                            url: './lock',
                            type: 'GET',
                            data: { 'id': id },
                            success: function(people) {
                            	 $.alert({
                                    title: false,
                                    content: people + '已被封鎖'
                                });
                            }
                        });
                    }
                });
            });

    /* 觀看詳細資料 */
    var toggle = 0;
    $(".distributorRow , .proxyRow, .platformRow , .playerRow").click(function() {
        var id = $(this).data('id');
        if ($(this).hasClass('distributorRow')){
           var detail_url = './distributor/proxyDetail';
           var edit_url = './distributor/getId';
           var credit_url = './distributor/update_credit';
           var detail_redirect = './distributor/proxyDetail?detail_id=';
           var edit_redirect = './distributor/getId?modify=';
        } else if($(this).hasClass('proxyRow')) {
           var detail_url = './proxy/detail';
           var edit_url = './proxy/getId';
           var credit_url = './proxy/update_credit';
           var detail_redirect = './proxy/detail?detail_id=';
           var edit_redirect = './proxy/getId?modify=';
        } else if($(this).hasClass('platformRow')){
            var detail_url = './platform/detail';
            var edit_url = './platform/getId';
            var credit_url = './platform/update_credit';
            var edit_redirect = './platform/getId?modify=';
            var detail_redirect = './platform/detail?detail_id=';
        } else if($(this).hasClass('playerRow')){
            var detail_url = './player/detail';
            var edit_url = './player/getId';
            var edit_redirect = './player/getId?modify=';
            var detail_redirect = './player/detail?detail_id=';
        }

        if (toggle == 0) {
            if ($(this).hasClass('platformRow')){
              $(this).after("<tr class= 'appendRow'><td align='center' colspan='4'><button data-detail= '" + id + "' class='detail btn btn-labeled btn-primary'><span class='btn-label'><i class='fa fa-file-text-o'></i></span>玩家資料</button>&nbsp;&nbsp;&nbsp;<button data-target='#edit' data-toggle='modal' data-modify='" + id + "' class='proxy_edit btn btn-labeled btn-warning'><span class='btn-label'><i class='fa fa-pencil-square-o'></i></span>編輯</button>&nbsp;&nbsp;&nbsp;<button data-lock='" + id + "' class='lock btn btn-labeled btn-danger'><span class='btn-label'><i class='fa fa-lock'></i></span>封鎖</button></td><td align='center' colspan='6'>更新信用餘額 : &nbsp;&nbsp;<input type='text' class='credit_edit' style='width: 215px;' placeholder='請用 + 或 - 來增減餘額(ex:+500)'> CNY &nbsp;&nbsp;<button data-credit='" + id + "' class='credit_sure btn btn-labeled btn-success'><span class='btn-label'><i class='fa fa-check'></i></span>更新</button><br><span style='color:red;'>請注意，未填寫 '+' 或 '-' ，一律不更新餘額</span></td></tr>");

            } else if ($(this).hasClass('playerRow')) {
              $(this).after("<tr class= 'appendRow'><td align='center' colspan='9'><button data-detail= '" + id + "' class='detail btn btn-labeled btn-primary'><span class='btn-label'><i class='fa fa-file-text-o'></i></span>玩家資料</button><span class='space'></span><button data-target='#edit' data-toggle='modal' data-modify='" + id + "' class='proxy_edit btn btn-labeled btn-warning'><span class='btn-label'><i class='fa fa-pencil-square-o'></i></span>編輯</button>&nbsp;&nbsp;&nbsp;<button data-lock='" + id + "' class='lock btn btn-labeled btn-danger'><span class='btn-label'><i class='fa fa-lock'></i></span>封鎖</button></td></tr>");

            } else {
              $(this).after("<tr class= 'appendRow'><td align='center' colspan='4'><button data-detail= '" + id + "' class='detail btn btn-labeled btn-primary'><span class='btn-label'><i class='fa fa-file-text-o'></i></span>詳細資料</button>&nbsp;&nbsp;&nbsp;<button data-target='#edit' data-toggle='modal' data-modify='" + id + "' class='proxy_edit btn btn-labeled btn-warning'><span class='btn-label'><i class='fa fa-pencil-square-o'></i></span>編輯</button>&nbsp;&nbsp;&nbsp;<button data-lock='" + id + "' class='lock btn btn-labeled btn-danger'><span class='btn-label'><i class='fa fa-lock'></i></span>封鎖</button></td><td align='center' colspan='6'>更新信用餘額 : &nbsp;&nbsp;<input type='text' class='credit_edit' style='width: 215px;' placeholder='請用 + 或 - 來增減餘額(ex:+500)'> CNY &nbsp;&nbsp;<button data-credit='" + id + "' class='credit_sure btn btn-labeled btn-success'><span class='btn-label'><i class='fa fa-check'></i></span>更新</button><br><span style='color:red;'>請注意，未填寫 '+' 或 '-' ，一律不更新餘額</span></td></tr>");

            }
            $('.detail').on('click', function() {
                var detail_id = $(this).data('detail');
                $.ajax({
                    url: detail_url,
                    type: 'GET',
                    data: { 'detail_id': detail_id },
                    success: function() {
                        document.location = detail_redirect + detail_id;
                    }
                });
            });

            /* 取得編輯資料id */
            $('.proxy_edit').on('click', function() {
                var modify = $(this).data('modify');
                $.ajax({
                    url: edit_url,
                    type: 'GET',
                    data: { 'modify': modify },
                    success: function() {
                        document.location = edit_redirect + modify;
                    }
                });
            });

            /* 更新信用額度 */
            $('.credit_sure').on('click', function() {
                var credit = $('.credit_edit').val();
                var id = $(this).data('credit');
                if (credit != '') {
                    $.confirm({
                        title: false,
                        content: '確定是否更新?',
                        cancelButton: '取消',
                        confirmButton: '確定',
                        theme: 'material',
                        confirm: function() {
                            $.ajax({
                                url: credit_url,
                                data: { 'id': id, 'credit': credit },
                                type: 'POST',
                                success: function(data) {
                                    $.alert({
                                        title: false,
                                        theme: 'material',
                                        content: '更新前餘額 : ' + data.credit_before + '<br>更新後餘額 : ' + data.credit_after,
                                        onClose: function() {
                                            window.location.reload();
                                        }
                                    });
                                }
                            });
                        },
                        cancel: function() {
                            window.location.reload();
                        }
                    });
                } else {
                    $.alert({
                        content: '請輸入要增減的信用額度',
                        title: false,
                        theme: 'material'
                    });
                }
            });
            $('.lock').click(function() {
                var id = $(this).data('lock');
                $.confirm({
                    title: false,
                    content: '確定是否封鎖?',
                    cancelButton: '取消',
                    confirmButton: '確定',
                    theme: 'material',
                    confirm: function() {
                        $.ajax({
                            url: './distributor/lock',
                            type: 'GET',
                            data: { 'id': id },
                            success: function(people) {
                            	 $.alert({
                                    title: false,
                                    content: people + '已被封鎖',
                                    onClose: function() {
                                       window.location.reload();
                                    }
                                });
                            }
                        });
                    },
                    cancel: function() {
                        window.location.reload();
                    }
                });
            });

            toggle = 1;
        } else {
            $(".appendRow").remove();
            toggle = 0;
        }
    });
});
