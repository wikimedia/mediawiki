<?php
require_once( "LanguageZh_cn.php");
require_once( "LanguageZh_tw.php");
require_once( "LanguageZh_sg.php");
require_once( "LanguageZh_hk.php");
/* caching the conversion tables */
$zh2TW = $wgMemc->get($key1 = "$wgDBname:zhConvert:tw");
$zh2CN = $wgMemc->get($key2 = "$wgDBname:zhConvert:cn");
$zh2SG = $wgMemc->get($key3 = "$wgDBname:zhConvert:sg");
$zh2HK = $wgMemc->get($key4 = "$wgDBname:zhConvert:hk");
if(empty($zhSimp2Trad) || empty($zhTrad2Simp)) {
	require_once("includes/ZhConversion.php");
	$wgMemc->set($key1, $zh2TW);
	$wgMemc->set($key2, $zh2CN);
	$wgMemc->set($key3, $zh2SG);
	$wgMemc->set($key4, $zh2HK);
}

/* class that handles both Traditional and Simplified Chinese
   right now it only distinguish zh_cn and zh_tw (actuall, zh_cn and
   non-zh_cn), will add support for zh_sg, zh_hk, etc, later.
*/
class LanguageZh extends LanguageZh_cn {
	
	var $mZhLanguageCode=false;
	
	function LanguageZh() {
		$this->mZhLanguageCode = $this->getPreferredVariant();
	}
	
	/* 
		get preferred language variants. eventually this will check the
		user's preference setting as well, once the language option in
		the setting pages is finalized.
	*/
	function getPreferredVariant() {
		global $wgUser;
		
		if($this->mZhLanguageCode)
			return $this->mZhLanguageCode;
		
		// get language variant preference for logged in users 
		if($wgUser->getID()!=0) {
			$this->mZhLanguageCode = $wgUser->getOption('variant');
		}
		else {
			// see if some zh- variant is set in the http header,
			$this->mZhLanguageCode="zh-cn";
			$header = str_replace( '_', '-', strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]));
			$zh = strstr($header, 'zh-');
			if($zh) {
				$this->mZhLanguageCode = substr($zh,0,5);
			}
		}
		return $this->mZhLanguageCode;
	}
	
	
  /* the Simplified/Traditional conversion stuff */

	function zh2tw($text) {
		global $zh2TW;
		return strtr($text, $zh2TW);
	}

	function zh2cn($text) {
		global $zh2CN;
		return strtr($text, $zh2CN);
	}

	function zh2sg($text) {
		global $zh2SG, $zh2CN;
		return strtr(strtr($text, $zh2CN), $zh2SG);
	}

	function zh2hk($text) {
		global $zh2HK, $zh2TW;
		return strtr(strtr($text, $zh2TW), $zh2HK);
	}
	
	function autoConvert($text, $toVariant=false) {
		if(!$toVariant) 
			$toVariant = $this->getPreferredVariant();
		$fname="zhconvert";
		wfProfileIn( $fname );
		$t = $text;
		switch($toVariant) {
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
		}
		wfProfileOut( $fname );
		return $t;
	}

	function getVariants() {
		return array("zh-cn", "zh-tw", "zh-sg", "zh-hk");
	}

	function getVariantFallback($v) {
		switch ($v) {
		case 'zh-cn': return 'zh-sg'; break;
		case 'zh-sg': return 'zh-cn'; break;
		case 'zh-tw': return 'zh-hk'; break;
		case 'zh-hk': return 'zh-tw'; break;
		}
		return false;
	}
}
?>
