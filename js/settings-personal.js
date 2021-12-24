window.addEventListener('DOMContentLoaded', function () {

    // 初期表示
    $(window).load(function () {
        var baseUrl = OC.generateUrl('/apps/rcconnect');

	$.ajax({
	    url: baseUrl + '/rc',
	    type: 'GET',
	    contentType: 'application/json',
	    dataType:"json",
	}).done(function (data) {
	    // handle success
            $('#username').val(data.username);
	}).fail(function (data, code) {
	    // handle failure
            OC.msg.finishedSaving('#error-msg',
                {
                    'status' : 'error',
                    'data' : {
                        'message' : t('rcconnect', 'Application Error. More details can be found in the server log or contact the server administrator.')
                    }
                }
            );
            return false;
	});

    });

    // 適用ボタンが押された場合
    $('#apply').click(function () {
        var baseUrl = OC.generateUrl('/apps/rcconnect');

        if ($('#username').val() !== '' && $('#password').val() !== '') {
            var post = {
                username: $('#username').val(),
                password: $('#password').val()
            };
        } else {
            OC.msg.finishedSaving('#error-msg',
                {
                    'status' : 'error',
                    'data' : {
                        'message' : t('rcconnect', 'Unable to setting user')
                    }
                }
            );
            return false;
        }

        $.ajax({
            url: baseUrl + '/rc',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(post)
        }).done(function (response) {
            // handle success
            $('#password').val('');
            OC.msg.finishedSaving('#error-msg',
                {
                    'status' : 'success',
                    'data' : {
                        'message' : t('rcconnect', 'Save')
                    }
                }
            );
            return true;

        }).fail(function (response, code) {
            // handle failure
            OC.msg.finishedSaving('#error-msg',
                {
                    'status' : 'error',
                    'data' : {
                        'message' : t('rcconnect', 'Application Error. More details can be found in the server log or contact the server administrator.')
                    }
                }
            );
            return false;
        });
    });

    // 削除ボタンが押された場合
    $('#delete').click(function () {
        var baseUrl = OC.generateUrl('/apps/rcconnect');

        if (window.confirm(t('rcconnect', 'Delete user?'))) {
	    $.ajax({
		url: baseUrl + '/rc',
		type: 'DELETE',
		contentType: 'application/json',
		data: JSON.stringify()
	    }).done(function (response) {
		// handle success
		$('#username').val('');
		$('#password').val('');
		OC.msg.finishedSaving('#error-msg',
		    {
			'status' : 'success',
			'data' : {
			    'message' : t('rcconnect', 'Deleted user.')
			}
		    }
		);
		return true;

	    }).fail(function (response, code) {
		// handle failure
		OC.msg.finishedSaving('#error-msg',
		    {
			'status' : 'error',
			'data' : {
			    'message' : t('rcconnect', 'Application Error. More details can be found in the server log or contact the server administrator.')
			}
		    }
		);
		return false;
	    });
        }
    });

});
