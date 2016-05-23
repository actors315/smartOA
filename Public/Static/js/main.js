require.config({
	baseUrl : '/Public/Static/js/',
	paths : {
		smartOA : "lib/smartOA",
		jquery : "lib/jquery/jquery.2.1.1.min",
		bootstrap : "lib/bootstrap/bootstrap.min",
		toastr : "plugins/toastr/toastr.min",
		bootbox : 'plugins/bootbox/bootbox.min',
		ui_dialog : "plugins/ui_dialog",
		a : "test/a",
	},
	map : {
		'*' : {
			'css' : 'plugins/require-css/css' // or whatever the path to require-css is
		}
	},
	shim : {
		bootstrap : {
			deps : ['jquery']
		},
		smartOA : {
			exports : "smart", //exports的值，需与非AMD规范js文件中暴露出的全局变量名称一致，比smartOA所有对象都在window.smart对象下
		},
		toastr : {
			deps : ['css!plugins/toastr/toastr.min']
		},
		bootbox : {
			exports : "bootbox",
			deps : ['bootstrap']
		}
	}
});
