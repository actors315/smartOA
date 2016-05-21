require.config({
	baseUrl : '/Public/Static/js/',
	paths : {
		smartOA : "lib/smartOA",
		jquery : "lib/jquery.2.1.1.min",
		toastr : "plugins/toastr/toastr.min",
		a : "test/a",
	},
	map: {
	  '*': {
	    'css': 'plugins/require-css/css' // or whatever the path to require-css is
	  }
	},
	shim : {
		smartOA : {
			exports : "smart", //exports的值，需与非AMD规范js文件中暴露出的全局变量名称一致，比smartOA所有对象都在window.smart对象下
		},
		toastr : {
			deps : ['css!plugins/toastr/toastr.min']
		}
	}
});
