<?php
require_once( "includes/ZhClient.php" );
require_once( "LanguageZh_cn.php");
require_once( "LanguageZh_tw.php");
require_once( "LanguageZh_sg.php");
require_once( "LanguageZh_hk.php");

/* class that handles both Traditional and Simplified Chinese
   right now it only distinguish zh_cn and zh_tw (actuall, zh_cn and
   non-zh_cn), will add support for zh_sg, zh_hk, etc, later.
*/
class LanguageZh extends LanguageZh_cn {
	
	var $mZhLanguageCode=false;
	var $mZhClient=false;	
	function LanguageZh() {
		global $wgUseZhdaemon, $wgZhdaemonHost, $wgZhdaemonPort;
		global $wgDisableLangConversion;

		$this->mZhLanguageCode = $this->getPreferredVariant();
		if($wgUseZhdaemon) {
			$this->mZhClient=new ZhClient($wgZhdaemonHost, $wgZhdaemonPort);
			if(!$this->mZhClient->isconnected())
				$this->mZhClient = false;
		}
		// fallback to fake client
		if($this->mZhClient == false)
			$this->mZhClient=new ZhClientFake();
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
	
	
	
	function autoConvert($text, $toVariant=false) {
		if(!$toVariant) 
			$toVariant = $this->getPreferredVariant();
		$fname="zhautoConvert";
		wfProfileIn( $fname );
		$t = $this->mZhClient->convert($text, $toVariant);
		wfProfileOut( $fname );
		return $t;
	}
    
	function autoConvertToAllVariants($text) {
		$fname="zhautoConvertToAll";
		wfProfileIn( $fname );
		$ret = $this->mZhClient->convertToAllVariants($text);
		if($ret == false) {//fall back...
			$ret = Language::autoConvertToAllVariants($text);
		}
		wfProfileOut( $fname );
		return $ret;
	}
    
	# only convert titles having more than one character
	function convertTitle($text) {
		$len=0;
		if( function_exists( 'mb_strlen' ) )
			$len = mb_strlen($text);
		else
			$len = strlen($text)/3;
		if($len>1)
			return $this->autoConvert( $text);
		return $text;
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

	// word segmentation through ZhClient
	function stripForSearch( $string ) {
		$fname="zhsegment";
		wfProfileIn( $fname );
        //always convert to zh-cn before indexing. it should be
		//better to use zh-cn for search, since conversion from 
		//Traditional to Simplified is less ambiguous than the
		//other way around
        $string = $this->autoConvert($string, 'zh-cn');
		$t = $this->mZhClient->segment($string);
		$t = LanguageUtf8::stripForSearch( $t );
		wfProfileOut( $fname );
		return $t;

	}

	function convertForSearchResult( $termsArray ) {
		$terms = implode( '|', $termsArray );
		$terms = $this->autoConvert( $terms, 'zh-cn' );
		$ret = array_unique( explode('|', $termsArray.'|'.$terms) );
		return $ret;
	}
}
?>
