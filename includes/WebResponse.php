<?php

/* 
 * Allow programs to request this object from WebRequest::response() and handle all outputting (or lack of outputting) via it.
 */

class WebResponse {
	function header($string, $replace=true) {
		header($string,$replace);
	}
	
	function setcookie($name, $value, $expire) {
		global $wgCookiePath, $wgCookieDomain, $wgCookieSecure;
		setcookie($name,$value,$expire, $wgCookiePath, $wgCookieDomain, $wgCookieSecure);
	}
}

?>