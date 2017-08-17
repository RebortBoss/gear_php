/**
 * Created by Yuri2 on 2016/12/9.
 */



//原型丰富

Date.prototype.format = function (fmt) { //author: meizz
    if (!fmt) {
        fmt = 'yyyy-MM-dd hh:mm:ss';
    }
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};

Array.unique = function (arr) {
    var newArray = [];
    var oldArray = arr;
    if (oldArray.length <= 1) {
        return oldArray;
    }
    for (var i = 0; oldArray.length > 0; i++) {
        //要一直把oldArray pop完为止.所以长度会一直变短。所以不能用i < oldArray.length的形式来判断是否完成.
        newArray.push(oldArray.shift()); //oldArray从最前面开始移出数组元素，这样新数组的顺序不会变。
        for (var j = 0; j < oldArray.length; j++) {
            if (newArray[i] === oldArray[j]) {
                oldArray.splice(j, 1);//删除重复的元素
                j--;
            }
        }
    }
    return newArray;
};

Array.in_array = function (needle, arrayToSearch) {
    for (s = 0; s < arrayToSearch.length; s++) {
        thisEntry = arrayToSearch[s].toString();
        if (thisEntry === needle) {
            return true;
        }
    }
    return false;
};

/**
 *
 *  Base64 encode / decode
 *
 *  @author haitao.tu
 *  @date   2010-04-26
 *  @email  tuhaitao@foxmail.com
 *
 */
function Base64() {

    // private property
    _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

    // public method for encoding
    this.encode = function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = _utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
                _keyStr.charAt(enc1) + _keyStr.charAt(enc2) +
                _keyStr.charAt(enc3) + _keyStr.charAt(enc4);
        }
        return output;
    };

    // public method for decoding
    this.decode = function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = _keyStr.indexOf(input.charAt(i++));
            enc2 = _keyStr.indexOf(input.charAt(i++));
            enc3 = _keyStr.indexOf(input.charAt(i++));
            enc4 = _keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 !== 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 !== 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = _utf8_decode(output);
        return output;
    };

    // private method for UTF-8 encoding
    _utf8_encode = function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }
        return utftext;
    };

    // private method for UTF-8 decoding
    _utf8_decode = function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}

//Yuri2 助手
var Yuri2 = {
    log: function (content) {
        if (console && typeof (console.log) === 'function') {
            console.log(content)
        }
    },
    f5: function () {
        location.reload()
    },
    getArrayKeys: function (myhash) {
        var keys = [];
        for (key in myhash) {
            keys.push(key);
        }
        return keys;
    },
    submitForm: function (action, params) {
        var form = $("<form></form>");
        form.attr('action', action);
        form.attr('method', 'post');
        form.attr('target', '_self');
        for (param in params) {
            var input1 = $("<input type='hidden' name='" + param + "' />");
            input1.val(params[param]);
            form.append(input1);
        }
        form.appendTo("body");
        form.css('display', 'none');
        form.submit();
    },
    formValidator: function (data) {
        function check(e) {
            var reg = $(e)[0].gear_reg;
            var preg_rel = reg.test($(e).val());
            if (preg_rel) {
                $(e).css('color', 'green');
                $(e).css('border-color', 'rgba(80, 212, 84, 0.9)');
                $(e).removeClass('gear-valid-failed');
            } else {
                $(e).css('color', 'red');
                $(e).css('border-color', 'rgba(239, 104, 104, 0.51)');
                $(e).addClass('gear-valid-failed');
            }
        }

        for (field in data.fields) {
            var ipt = $(data.form + " input[name='" + field + "']");
            if (ipt.length === 0) {
                ipt = $(data.form + " select[name='" + field + "']")
            }
            if (ipt.length === 0) {
                ipt = $(data.form + " textarea[name='" + field + "']")
            }
            ipt[0].gear_reg = data.fields[field];
            ipt.bind('input propertychange change', function () {
                check(this)
            });
            check(ipt)
        }
        $(data.form).submit(function () {
            var valid_fails = $(this).find('.gear-valid-failed');
            var errors = valid_fails.length;
            if (errors > 0) {
                alert('验证失败，请检查输入。\r\nValidation failed, please check the input.');
                return false;
            }
        })
    },
    timestampToDate: function (timestamp, format) {
        format = format ? format : 'yyyy-MM-dd hh:mm:ss';
        var newDate = new Date();
        newDate.setTime(timestamp * 1000);
        return (newDate.format(format));
    },
    isPC: function () {
        var userAgentInfo = navigator.userAgent;
        var Agents = ["Android", "iPhone",
            "SymbianOS", "Windows Phone",
            "iPad", "iPod"];
        var flag = true;
        for (var v = 0; v < Agents.length; v++) {
            if (userAgentInfo.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    },
    isSmallScreen: function (size) {
        if (!size) {
            size = 768
        }
        var width = document.body.clientWidth;
        return width < size;
    },
    clone: function (obj) {
        var o;
        if (typeof obj === "object") {
            if (obj === null) {
                o = null;
            } else {
                if (obj instanceof Array) {
                    o = [];
                    for (var i = 0, len = obj.length; i < len; i++) {
                        o.push(Yuri2.clone(obj[i]));
                    }
                } else {
                    o = {};
                    for (var j in obj) {
                        o[j] = Yuri2.clone(obj[j]);
                    }
                }
            }
        } else {
            o = obj;
        }
        return o;
    },
    isSet: function (v) {
        {
            return !((typeof (v) === 'undefined') || (v === null));
        }
    },
    in_array: function (stringToSearch, arrayToSearch) {
        for (s = 0; s < arrayToSearch.length; s++) {
            thisEntry = arrayToSearch[s].toString();
            if (thisEntry === stringToSearch) {
                return true;
            }
        }
        return false;
    },
    /**
     * 合并两个json对象属性为一个对象
     * @param jsonObject1
     * @param jsonObject2
     * @param recursion (remain default)
     * @returns object resultJsonObject
     */
    jsonMerge: function (jsonObject1, jsonObject2, recursion) {
        var resultJsonObject = {};
        for (var attr in jsonObject1) {
            resultJsonObject[attr] = jsonObject1[attr];
        }
        for (var attr in jsonObject2) {
            resultJsonObject[attr] =
                recursion === true &&
                typeof ( resultJsonObject[attr]) === 'object' &&
                typeof ( jsonObject2[attr]) === 'object' ?
                    Yuri2.jsonMerge(resultJsonObject[attr], jsonObject2[attr], false) : jsonObject2[attr];
        }
        return resultJsonObject;
    },
    randInt: function (n, m) {
        var c = m - n + 1;
        return Math.floor(Math.random() * c + n);
    },
    shuffle: function (arr) {
        var i,
            j,
            temp;
        for (i = arr.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            temp = arr[i];
            arr[i] = arr[j];
            arr[j] = temp;
        }
        return arr;
    },
    htmlspecialchars: function (str) {
        str = str.replace(/&/g, '&amp;');
        str = str.replace(/</g, '&lt;');
        str = str.replace(/>/g, '&gt;');
        str = str.replace(/"/g, '&quot;');
        str = str.replace(/'/g, '&#039;');
        return str;
    },
    htmlspecialchars_decode: function (str) {
        str = str.replace(/&amp;/g, '&');
        str = str.replace(/&lt;/g, '<');
        str = str.replace(/&gt;/g, '>');
        str = str.replace(/&quot;/g, "''");
        str = str.replace(/&#039;/g, "'");
        return str;
    },
    textOverFlow: function (str, len) {
        return str.length > len ? str.substring(0, len) + "..." : str;
    },
    getLang: function () {
        return (navigator.language || navigator.browserLanguage).toLowerCase();
        //通常是 zh-cn
    },
    iframeOnClick: {
        resolution: 200,
        iframes: [],
        interval: null,
        Iframe: function () {
            this.element = arguments[0];
            this.cb = arguments[1];
            this.hasTracked = false;
        },
        track: function (element, cb) {
            this.iframes.push(new this.Iframe(element, cb));
            if (!this.interval) {
                var _this = this;
                this.interval = setInterval(function () {
                    _this.checkClick();
                }, this.resolution);
            }
        },
        checkClick: function () {
            if (document.activeElement) {
                var activeElement = document.activeElement;
                for (var i in this.iframes) {
                    if (activeElement === this.iframes[i].element) { // user is in this Iframe
                        if (this.iframes[i].hasTracked === false) {
                            this.iframes[i].cb.apply(window, []);
                            this.iframes[i].hasTracked = true;
                        }
                    } else {
                        this.iframes[i].hasTracked = false;
                    }
                }
            }
        }
    },
    loadScript: function (url, callback) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        if (typeof(callback) !== "undefined") {
            if (script.readyState) {
                script.onreadystatechange = function () {
                    if (script.readyState === "loaded" || script.readyState === "complete") {
                        script.onreadystatechange = null;
                        callback(script);
                    }
                };
            } else {
                script.onload = function () {
                    callback(script);
                };
            }
        }
        script.src = url;
        document.body.appendChild(script);
    },
    jsonp: function (url, data, callback) {
        var func_name = Math.random();
        if (!Yuri2.jsonp_funcs) {
            Yuri2.jsonp_funcs = {};
        }
        Yuri2.jsonp_funcs[func_name] = callback;
        var rel = '';
        rel += url;
        if (url.indexOf('?') < 0) {
            rel += '?';
        }
        if (url.indexOf('&') > 0) {
            rel += '&';
        }
        rel += 'callback=' + encodeURI('Yuri2.jsonp_funcs["' + func_name + '"]');
        if (data) {
            var data_str = JSON.stringify(data);
            data_str = encodeURI(data_str);
            rel += '&data=' + data_str;
        }
        Yuri2.loadScript(rel, function (script) {
            script.parentNode.removeChild(script);
        })

    }
};

