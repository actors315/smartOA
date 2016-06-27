require.config({
	baseUrl : 'https://static.lingyin99.cn/Static/js/',
	paths : {
		smartOA : "lib/smartOA",
		jquery : "lib/jquery/jquery.2.1.1.min",
		bootstrap : "lib/bootstrap/bootstrap.min",
		toastr : "plugins/toastr/toastr.min",
		bootbox : 'plugins/bootbox/bootbox.min',
		ui_dialog : "plugins/ui_dialog",
		socketio : "lib/socket.io-client/socket.io",
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

require(['socketio', 'smartOA'], function(io, smart) {
	var uid = smart.cookie.get('token');
	if(uid == undefined){
		return;
	}
	
	var socket = io('http://' + document.domain + ':2120');	
	// 当socket连接后发送登录请求
	socket.on('connect', function() {
		socket.emit('login', uid);
	});
	// 当服务端推送来消息时触发，这里简单的aler出来，用户可做成自己的展示效果
	socket.on('new_msg', function(msg) {
		console.log(msg);
	});
	//在线人数
	socket.on('update_online_count', function(msg) {
		console.log(msg);
	});
}); 