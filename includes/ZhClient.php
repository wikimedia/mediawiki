<?php
/**
 * Client for querying zhdaemon
 *
 * @package MediaWiki
 */

class ZhClient {
	var $mHost, $mPort, $mFP, $mConnected;

	/**
	 * Constructor
	 *
	 * @access private
	 */
	function ZhClient($host, $port) {
		$this->mHost = $host;
		$this->mPort = $port;
		$this->mConnected = $this->connect();
	}

	/**
	 * Check if connection to zhdaemon is successful
	 *
	 * @access public
	 */
	function isconnected() {
		return $this->mConnected;
	}

	/**
	 * Establish conncetion
	 *
	 * @access private
	 */
	function connect() {
		wfSuppressWarnings();
		$this->mFP = fsockopen($this->mHost, $this->mPort, $errno, $errstr, 30);
		wfRestoreWarnings();
		if(!$this->mFP) {
			return false;
		}
		return true;
	}

	/**
	 * Query the daemon and return the result
	 *
	 * @access private
	 */
	function query($request) {
		if(!$this->mConnected)
			return false;

		fwrite($this->mFP, $request);

		$result=fgets($this->mFP, 1024);

		list($status, $len) = explode(" ", $result);
		if($status == 'ERROR') {
			//$len is actually the error code...
			print "zhdaemon error $len<br />\n";
			return false;
		}
		$bytesread=0;
		$data='';
		while(!feof($this->mFP) && $bytesread<$len) {
			$str= fread($this->mFP, $len-$bytesread);
			$bytesread += strlen($str);
			$data .= $str;
		}
		//data should be of length $len. otherwise something is wrong
		if(strlen($data) != $len)
			return false;
		return $data;
	}

	/**
	 * Convert the input to a different language variant
	 *
	 * @param string $text input text
	 * @param string $tolang language variant
	 * @return string the converted text
	 * @access public
	 */
	function convert($text, $tolang) {
		$len = strlen($text);
		$q = "CONV $tolang $len\n$text";
		$result = $this->query($q);
		if(!$result)
			$result = $text;
		return $result;
	}

	/**
	 * Convert the input to all possible variants 
	 *
	 * @param string $text input text
	 * @return array langcode => converted_string
	 * @access public
	 */	
	function convertToAllVariants($text) {
		$len = strlen($text);
		$q = "CONV ALL $len\n$text";
		$result = $this->query($q);
		if(!$result)
			return false;
		list($infoline, $data) = explode('|', $result, 2);
		$info = explode(";", $infoline);
		$ret = array();
		$i=0;
		foreach($info as $variant) {
			list($code, $len) = explode(' ', $variant);
			$ret[strtolower($code)] = substr($data, $i, $len);
			$r = $ret[strtolower($code)];
			$i+=$len;
		}
		return $ret;
    }
	/**
	 * Perform word segmentation
	 *
	 * @param string $text input text
	 * @return string segmented text
	 * @access public
	 */
	function segment($text) {
		$len = strlen($text);
		$q = "SEG $len\n$text";
		$result = $this->query($q);
		if(!$result) {// fallback to character based segmentation
			$result = ZhClientFake::segment($text);
		}
		return $result;
	}

	/**
	 * Close the connection
	 *
	 * @access public
	 */
	function close() {
		fclose($this->mFP);
	}
}


class ZhClientFake {
	function ZhClientFake() {
		global $wgMemc, $wgDBname;
		$this->mZh2TW = $wgMemc->get($key1 = "$wgDBname:zhConvert:tw");
		$this->mZh2CN = $wgMemc->get($key2 = "$wgDBname:zhConvert:cn");
		$this->mZh2SG = $wgMemc->get($key3 = "$wgDBname:zhConvert:sg");
		$this->mZh2HK = $wgMemc->get($key4 = "$wgDBname:zhConvert:hk");
		if(empty($this->mZh2TW) || empty($this->mZh2CN) || empty($this->mZh2SG) || empty($this->mZh2HK)) {
			require("includes/ZhConversion.php");
			$this->mZh2TW = $zh2TW;
			$this->mZh2CN = $zh2CN;
			$this->mZh2HK = $zh2HK;
			$this->mZh2SG = $zh2SG;
			$wgMemc->set($key1, $this->mZh2TW);
			$wgMemc->set($key2, $this->mZh2CN);
			$wgMemc->set($key3, $this->mZh2SG);
			$wgMemc->set($key4, $this->mZh2HK);
		}
	}

	function isconnected() {
		return true;
	}

	/**
	 * Convert to zh-tw
	 *
	 * @access private
	 */
	function zh2tw($text) {
		return strtr($text, $this->mZh2TW);
	}

	/**
	 * Convert to zh-cn
	 *
	 * @access private
	 */
	function zh2cn($text) {
		return strtr($text, $this->mZh2CN);
	}

	/**
	 * Convert to zh-sg
	 *
	 * @access private
	 */
	function zh2sg($text) {
		return strtr(strtr($text, $this->mZh2CN), $this->mZh2SG);
	}

	/**
	 * Convert to zh-hk
	 *
	 * @access private
	 */
	function zh2hk($text) {
		return strtr(strtr($text, $this->mZh2TW), $this->mZh2HK);
	}

	/**
	 * Convert the input to a different language variant
	 *
	 * @param string $text input text
	 * @param string $tolang language variant
	 * @return string the converted text
	 * @access public
	 */
	function convert($text, $tolang) {
		$t = '';
		switch($tolang) {
        case 'zh-cn':
			$t = $this->zh2cn($text);
			break;
		case 'zh-tw':
			$t = $this->zh2tw($text);
			break;
		case 'zh-sg':
			$t = $this->zh2sg($text);
			break;
		case 'zh-hk':
			$t = $this->zh2hk($text);
			break;
		default:
			$t = $text;
		}
		return $t;
	}

	function convertToAllVariants($text) {
		$ret = array();
		$ret['zh-cn'] = $this->zh2cn($text);
		$ret['zh-tw'] = $this->zh2tw($text);
		$ret['zh-sg'] = $this->zh2sg($text);
		$ret['zh-hk'] = $this->zh2hk($text);
		return $ret;
	}

	/**
	 * Perform "fake" word segmentation, i.e. treating each character as a word
	 *
	 * @param string $text input text
	 * @return string segmented text
	 * @access public
	 */
	function segment($text) {
		/* adapted from LanguageZh_cn::stripForSearch()
			here we will first separate the single characters,
			and let the caller conver it to hex
        */
		if( function_exists( 'mb_strtolower' ) ) {
			return preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"' ' .\"$1\"",
				mb_strtolower( $text ) );
		} else {
			global $wikiLowerChars;
			return preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"' ' . strtr( \"\$1\", \$wikiLowerChars )",
				$text );
		}
	}

	/**
	 * Close the fake connection
	 *
	 * @access public
	 */
	function close() {	}
}

?>