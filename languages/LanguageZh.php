<?php
require_once( "LanguageZh_cn.php");
require_once( "LanguageZh_tw.php");

/* caching the conversion tables */
$zhSimp2Trad = $wgMemc->get($key1 = "$wgDBname:zhConvert:s2t");
$zhTrad2Simp = $wgMemc->get($key2 = "$wgDBname:zhConvert:t2s");
if(empty($zhSimp2Trad) || empty($zhTrad2Simp)) {
    require_once("includes/ZhConversion.php");
    $wgMemc->set($key1, $zhSimp2Trad);
    $wgMemc->set($key2, $zhTrad2Simp);
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

	function simp2trad($text) {
		global $zhSimp2Trad;
		return strtr($text, $zhSimp2Trad);
	}

	function trad2simp($text) {
		global $zhTrad2Simp;
		return strtr($text, $zhTrad2Simp);
	}
	
	function autoConvert($text) {
        if($this->getPreferredVariant() == "zh-cn") {
            return $this->trad2simp($text);
        }
        else {
            return $this->simp2trad($text);
        }
    }

    function getVariants() {
        return array("zh-cn", "zh-tw");
    }
}
?>
