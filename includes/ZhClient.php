<?php
/**
 * Client for querying zhdaemon
 *
 * @package MediaWiki
 * @version $Id$
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
		list($infoline, $data) = explode('|', $result);
		$info = explode(";", $infoline);
		$ret = array();
		$i=0;
		foreach($info as $code => $len) {
			$ret[strtolower($code)] = substr($data, $i, $len);
			$i+=$len+1;
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
		$this->zh2TW = $wgMemc->get($key1 = "$wgDBname:zhConvert:tw");
		$this->zh2CN = $wgMemc->get($key2 = "$wgDBname:zhConvert:cn");
		$this->zh2SG = $wgMemc->get($key3 = "$wgDBname:zhConvert:sg");
		$this->zh2HK = $wgMemc->get($key4 = "$wgDBname:zhConvert:hk");
		if(empty($this->zh2TW) || empty($this->zh2CN) || empty($this->zh2SG) || empty($this->zh2HK)) {
			require_once("includes/ZhConversion.php");
			global $zh2TW, $zh2CN, $zh2HK, $zh2SG;
			$this->zh2TW = $zh2TW;
			$this->zh2CN = $zh2CN;
			$this->zh2HK = $zh2HK;
			$this->zh2SG = $zh2SG;
			$wgMemc->set($key1, $this->zh2TW);
			$wgMemc->set($key2, $this->zh2CN);
			$wgMemc->set($key3, $this->zh2SG);
			$wgMemc->set($key4, $this->zh2HK);
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
		return strtr($text, $this->zh2TW);
	}

	/**
	 * Convert to zh-cn
	 *
	 * @access private
	 */
	function zh2cn($text) {
		return strtr($text, $this->zh2CN);
	}

	/**
	 * Convert to zh-sg
	 *
	 * @access private
	 */
	function zh2sg($text) {
		return strtr(strtr($text, $this->zh2CN), $this->zh2SG);
	}

	/**
	 * Convert to zh-hk
	 *
	 * @access private
	 */
	function zh2hk($text) {
		return strtr(strtr($text, $this->zh2TW), $this->zh2HK);
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
		/* copied from LanguageZh_cn.stripForSearch() */
		if( function_exists( 'mb_strtolower' ) ) {
			return preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"' U8' . bin2hex( \"$1\" )",
				mb_strtolower( $text ) );
		} else {
			global $wikiLowerChars;
			return preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"' U8' . bin2hex( strtr( \"\$1\", \$wikiLowerChars ) )",
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