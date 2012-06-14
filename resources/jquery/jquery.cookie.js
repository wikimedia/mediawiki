/**
 * jQuery cookie plugin
 *
 * Copyright (c) Hck, 2011/10
 * Licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

(function($){
    /**
     * Function for setting cookie value and additional attributes
     *
     * @param name - name of the cookie
     * @param val - value of the cookie
     * @param duration - time in seconds that cookie will be aviliable
     * @param domain - cookie domain
     * @param path - cookie path
     * @param secure - allow transport cookie only through https
     */
    $.cookie = function(name, val, duration, domain, path, secure) {
        var trim = function(str){
            return str.replace(/(^\s+)|(\s+$)/g, '');
        };

        var cookies = {}, src = document.cookie.split(';');
        for(var k in src) {
            src[k] = trim(src[k]);
            if (src[k]) {
                var cookie = src[k].split('=');
                for(var j in cookie) cookie[j] = trim(cookie[j]);

                if(cookie[0])
                    cookies[cookie[0]] = cookie[1] ? cookie[1] : null;
            } else src.splice(k, 1);
        }

        if(!name) return cookies;

        var attrs = duration ? '; expires=' + new Date(new Date().getTime() + duration * 1000).toGMTString() : '';
        attrs += domain ? '; domain=' + domain : '';
        attrs += path ? '; path=' + path : '';
        attrs += secure ? '; secure' : '';

        if(val) document.cookie = name + '=' + encodeURIComponent(val) + attrs;
        else return cookies[name];

        return $;
    };

    /**
     * Function for remove cookies by their names or all cookies at once
     *
     * @param name - name of the cookie that will be removed
     */
    $.removeCookie = function(name) {
        var cookies;
        if(name) {
            cookies = {};
            cookies[name] = null;
        }
        else cookies = $.cookie();

        for(var k in cookies)
            document.cookie = k + '=; expires=' + new Date + ';';
    };
})(jQuery);