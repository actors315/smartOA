define(['toastr', 'bootbox'], function(toastr, bootbox) {
	function ui_error(msg) {
		toastr.options = {
			"closeButton" : true,
			"debug" : true,
			"positionClass" : "toast-bottom-right"
		};
		toastr.error('', msg);
	}

	function ui_info(msg) {
		toastr.options = {
			"closeButton" : true,
			"debug" : true,
			"positionClass" : "toast-bottom-right"
		};
		toastr.info(msg);
	}

	function ui_alert(msg, callback) {
		bootbox.dialog({
			message : "<h5>" + msg + "<h5>",
			buttons : {
				danger : {
					label : "确定",
					className : "btn-primary",
					callback : function() {
						if (callback != undefined) {
							callback();
						}
					}
				}
			}
		});
	}

	function ui_confirm(msg, confirmcallback, cancelcallback) {
		bootbox.dialog({
			message : "<h5>" + msg + "<h5>",
			buttons : {
				main : {
					label : "取消",
					className : "btn-default",
					callback : function() {
						if (cancelcallback != undefined) {
							cancelcallback();
						}
					}
				},
				danger : {
					label : "确定",
					className : "btn-primary",
					callback : function() {
						if (confirmcallback != undefined) {
							confirmcallback();
						}
					}
				}
			}
		});
	}

	return {
		error : ui_error,
		info : ui_info,
		alert : ui_alert,
		confirm : ui_confirm
	};
});
