define(['smartOA', 'ui_dialog'], function(smart, ui) {
	/* 验证数据类型*/
	function validate(data, datatype) {
		if (datatype.indexOf("|") > 0) {
			tmp = datatype.split("|");
			datatype = tmp[0];
			data2 = tmp[1];
		}
		switch (datatype) {
		case "require":
			return smart.verify.require(data);
		case "email":
			return smart.verify.email(data);
		case "number":
			return smart.verify.number(data);
		case "eqt":
			data2 = $("#" + data2).val();
			return data >= data2;
			break;
		case "positive":
			return data == "" || data >= 0;
			break;
		case "tel_noneed":
			return data == "" || smart.verify.mobile(data);
		case "tel_need":
			return smart.verify.mobile(data);
		}
	}

	function check_form(form_id) {
		var check_flag = true;
		$.each($("#" + form_id + " :input"), function() {
			if ($(this).attr("check")) {
				if (!validate($(this).val(), $(this).attr("check"))) {
					ui.error($(this).attr("msg"));
					$(this).focus();
					check_flag = false;
					return check_flag;
				}
			}
		});
		return check_flag;
	}

	/* ajax提交*/
	function send_ajax(url, vars, callback) {
		return $.ajax({
			type : "POST",
			url : url,
			data : vars + "&ajax=1",
			dataType : "json",
			success : callback
		});
	}

	/*提交表单*/
	function send_form(formId, post_url, return_url) {
		if ($("#ajax").val() == 1) {
			var vars = $("#" + formId).serialize();
			sendAjax(post_url, vars, function(data) {
				if (data.status) {
					ui_alert(data.info, function() {
						if (return_url) {
							location.href = return_url;
						}
					});
				} else {
					ui.error(data.info);
				}
			});
		} else {
			$("#" + formId).attr("action", post_url);
			if (return_url) {
				smart.cookie.set('return_url', return_url);
			}
			$("#" + formId).submit();
		}
	}

	return {
		check_form : check_form,
		send_ajax : send_ajax,
		send_form : send_form
	};
}); 