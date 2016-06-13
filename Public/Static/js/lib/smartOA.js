namespace = function() {
	var argus = arguments;
	for (var i = 0; i < argus.length; i++) {
		var objs = argus[i].split(".");
		var obj = window;
		for (var j = 0; j < objs.length; j++) {
			obj[objs[j]] = obj[objs[j]] || {};
			obj = obj[objs[j]];
		}
	}
	return obj;
};
/**
 * base
 */
namespace("smart.base");
(function() {
	smart.base.extend = function(destination, source) {
		if (destination == null) {
			destination = source;
		} else {
			for (var property in source) {
				if (getParamType(source[property]).toLowerCase() === "object" && getParamType(destination[property]).toLowerCase() === "object")
					extend(destination[property], source[property]);
				else
					destination[property] = source[property];
			}
		}
		return destination;
	};
	smart.base.extendLess = function(destination, source) {
		var newopt = source;
		for (var i in destination) {
			if (isObject(source) && typeof source[i] != "undefined") {
				destination[i] = newopt[i];
			}
		}
		return destination;
	};
	smart.base.extend(smart.base, {
		isUndefined : function(obj) {
			return obj === undefined && typeof obj == "undefined";
		},
		isArray : function(obj) {
			return getParamType(obj).toLowerCase() === "array";
		},
		isFunction : function(obj) {
			return getParamType(obj).toLowerCase() === "function";
		},
		isNumber : function(obj) {
			return getParamType(obj).toLowerCase() === "number";
		},
		isObject : function(obj) {
			return getParamType(obj).toLowerCase() === "object";
		},
		isString : function(obj) {
			return getParamType(obj).toLowerCase() === "string";
		},
		isDom : function(obj) {
			try {
				return typeof obj === "object" && obj.nodeType == 1 && typeof obj.nodeName == "string";
			} catch(e) {
				return false;
			}
		},
		isBoolean : function(obj) {
			return getParamType(obj).toLowerCase() === "boolean";
		},
		isDate : function(obj) {
			return getParamType(obj).toLowerCase() === "date";
		},
		getType : function(obj) {
			return getParamType(obj).toLowerCase();
		},
	});
	smart.base.extend(smart.base, {
		trim : function(str) {
			return str.replace(/(^\s*)|(\s*$)/g, "");
		},
		ltrim : function(str) {
			return str.replace(/(^\s*)/g, "");
		},
		rtrim : function(str) {
			return str.replace(/(\s*$)/g, "");
		},
		lpad : function(str, padchar, len) {
			var temp = "";
			for (var i = 0; i < len - str.length; i++) {
				temp += padchar;
			}
			return temp + str;
		},
		rpad : function(str, padchat, len) {
			var temp = "";
			for (var i = 0; i < len - str.length; i++) {
				temp += padchar;
			}
			return str + temp;
		}
	});
	function getParamType(obj) {
		return obj == null ? String(obj) : Object.prototype.toString.call(obj).replace(/\[object\s+(\w+)\]/i, "$1") || "object";
	}

})();
smart.base.extend(window, smart.base);
/**
 * cookie
 */
namespace("smart.cookie");
(function() {
	extend(smart.cookie, {
		set : function(sName, sValue, iExpireSec, sDomain, sPath, bSecure) {
			if (isUndefined(sName)) {
				return;
			}
			if (isUndefined(sValue)) {
				sValue = "";
			}
			var oCookieArray = [sName + "=" + escape(sValue)];
			if (!isNaN(iExpireSec)) {
				var oDate = new Date;
				oDate.setTime(oDate.getTime() + iExpireSec * 1e3);
				oCookieArray.push("expires=" + oDate.toGMTString());
			}
			if (!isUndefined(sDomain)) {
				oCookieArray.push("domain=" + sDomain);
			}
			if (!isUndefined(sPath)) {
				oCookieArray.push("path=" + sPath);
			}
			if (bSecure) {
				oCookieArray.push("secure");
			}
			document.cookie = oCookieArray.join("; ");
		},
		get : function(sName, sDefaultValue) {
			var sRE = "(?:; |^)" + sName + "=([^;]*);?";
			var oRE = new RegExp(sRE);
			if (oRE.test(document.cookie)) {
				return unescape(RegExp["$1"]);
			} else {
				return sDefaultValue || null;
			}
		},
		clear : function(sName, sDomain, sPath) {
			var oDate = new Date;
			smart.cookie.set(sName, "", -oDate.getTime() / 1e3, sDomain, sPath);
		}
	});
})();

/**
 * data validate
 */
namespace("smart.verify");
(function() {
	extend(smart.verify, {
		require : function(data) {
			return !(trim(data) == "");
		},
		email : function(data) {
			return /^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/.test(data);
		},
		number : function(data) {
			return /^[0-9]+\.{0,1}[0-9]{0,}$/.test(data);
		},
		mobile : function(data) {//手机号
			return /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/.test(data);
		},
		tel : function(data) {//国内固定电话,支持加或不加区号,支持最长5位数分机
			return /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,5})?$/.test(data);
		},
	});
})();

/**
 * device detect
 * 摘自 https://github.com/binnng/device.js.git
 * 感谢 binnng
 */
namespace("smart.device");
(function() {
	var WIN = window;
	var NA = WIN.navigator;
	var UA = NA.userAgent.toLowerCase();
	function test(needle) {
		return needle.test(UA);
	}

	var IsTouch = "ontouchend" in WIN;
	var IsAndroid = test(/android|htc/) || /linux/i.test(NA.platform + "");
	var IsIPad = !IsAndroid && test(/ipad/);
	var IsIPhone = !IsAndroid && test(/ipod|iphone/);
	var IsIOS = IsIPad || IsIPhone;
	var IsWinPhone = test(/windows phone/);
	var IsWebapp = !!NA["standalone"];
	var IsXiaoMi = IsAndroid && test(/mi\s+/);
	var IsUC = test(/ucbrowser/);
	var IsWeixin = test(/micromessenger/);
	var IsBaiduBrowser = test(/baidubrowser/);
	var IsChrome = !!WIN["chrome"];
	var IsBaiduBox = test(/baiduboxapp/);
	var IsPC = !IsAndroid && !IsIOS && !IsWinPhone;
	var IsHTC = IsAndroid && test(/htc\s+/);
	var IsBaiduWallet = test(/baiduwallet/);

	extend(smart.device, {
		isTouch : IsTouch,
		isAndroid : IsAndroid,
		isIPad : IsIPad,
		isIPhone : IsIPhone,
		isIOS : IsIOS,
		isWinPhone : IsWinPhone,
		isWebapp : IsWebapp,
		isXiaoMi : IsXiaoMi,
		isUC : IsUC,
		isWeixin : IsWeixin,
		isBaiduBox : IsBaiduBox,
		isBaiduBrowser : IsBaiduBrowser,
		isChrome : IsChrome,
		isPC : IsPC,
		isHTC : IsHTC,
		isBaiduWallet : IsBaiduWallet
	});
})();

/**
 * Object处理 
 */
namespace("smart.object");
(function() {
	extend(smart.object, {
		serialize : function(jsonObj) {
			var newJsonObj = null;
			if ( typeof jsonObj == "undefined" || typeof jsonObj == "function")
				newJsonObj = "";
			if ( typeof jsonObj == "number")
				newJsonObj = jsonObj.toString();
			if ( typeof jsonObj == "boolean")
				newJsonObj = jsonObj ? "1" : "0";
			if ( typeof jsonObj == "object") {
				if (!jsonObj)
					newJsonObj = "";
				if ( jsonObj instanceof RegExp)
					newJsonObj = jsonObj.toString();
			}
			if ( typeof jsonObj == "string")
				newJsonObj = jsonObj;
			if ( typeof newJsonObj == "string")
				return encodeURIComponent(newJsonObj);
			var ret = [];
			if ( jsonObj instanceof Array) {
				for (var i = 0; i < jsonObj.length; i++) {
					if ( typeof jsonObj[i] == "undefined")
						continue;
					ret.push( typeof jsonObj[i] == "object" ? "" : smart.object.serialize(jsonObj[i]));
				}
				return ret.join("|");
			} else {
				for (var i in jsonObj) {
					if ( typeof jsonObj[i] == "undefined")
						continue;
					newJsonObj = null;
					if ( typeof jsonObj[i] == "object") {
						if (jsonObj[i] instanceof Array) {
							newJsonObj = jsonObj[i];
							ret.push(i + "=" + smart.object.serialize(newJsonObj));
						} else {
							ret.push(i + "=");
						}
					} else {
						newJsonObj = jsonObj[i];
						ret.push(i + "=" + smart.object.serialize(newJsonObj));
					}
				}
				return ret.join("&");
			}
		},
		unSerialize : function(jsonStr, de) {
			de = de || 0;
			jsonStr = jsonStr.toString();
			if (!jsonStr)
				return {};
			var retObj = {},
			    obj1Ret = jsonStr.split("&");
			if (obj1Ret.length == 0)
				return retObj;
			for (var i = 0; i < obj1Ret.length; i++) {
				if (!obj1Ret[i])
					continue;
				var ret2 = obj1Ret[i].split("=");
				if (ret2.length >= 2) {
					var ret0 = obj1Ret[i].substr(0, obj1Ret[i].indexOf("=")),
					    ret1 = obj1Ret[i].substr(obj1Ret[i].indexOf("=") + 1);
					if (!ret1)
						ret1 = "";
					if (ret1.indexOf("#") > -1) {
						ret1 = ret1.split("#")[0];
					}
					if (ret0)
						retObj[ret0] = de == 0 ? decodeURIComponent(ret1) : ret1;
				}
			}
			return retObj;
		},
		decode : function(newopt) {
			if ( typeof newopt == "string") {
				try {
					return decodeURIComponent(newopt);
				} catch(e) {
				}
				return newopt;
			}
			if ( typeof newopt == "object") {
				if (newopt == null) {
					return null;
				}
				if ( newopt instanceof Array) {
					for (var i = 0; i < newopt.length; i++) {
						newopt[i] = smart.object.decode(newopt[i]);
					}
					return newopt;
				} else if ( newopt instanceof RegExp) {
					return newopt;
				} else {
					for (var i in newopt) {
						newopt[i] = smart.object.decode(newopt[i]);
					}
					return newopt;
				}
			}
			return newopt;
		}
	});
})();