<?php
require_once( "LanguageUtf8.php" );
require_once( "LanguageZh_cn.php");
require_once( "LanguageZh_tw.php");
require_once( "ZhConversion.php");

/* class that handles both Traditional and Simplified Chinese
   right now it only distinguish zh_cn and zh_tw (actuall, zh_cn and
   non-zh_cn), will add support for zh_sg, zh_hk, etc, later.
*/
class LanguageZh extends LanguageUtf8 {
    
    var $mZhLang=false, $mZhLanguageCode=false;

    function LanguageZh() {
        $this->mZhLanguageCode = $this->getPreferredVariant();
        if($this->mZhLanguageCode == "cn") {
            $this->mZhLang = new LanguageZh_cn();
        }
        else {
            $this->mZhLang = new LanguageZh_tw();
        }
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

        /* get language variant preference for logged in users */
        if($wgUser->getID()!=0) {
            $this->mZhLanguageCode = $wgUser->getOption('variant');
        }
        else { // see if it is in the http header, otherwise default to zh_cn
            $this->mZhLanguageCode="zh-cn";
            $value = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
            $zh = explode("zh-", $value);
            array_shift($zh);
            $l = array_shift($zh);
            if($l != NULL) {
                $this->mZhLanguageCode = "zh-".strtolower(substr($l,0,2));
            }
            // also set the variant option of anons
            $wgUser->setOption('variant', $this->mZhLanguageCode);
        }
        return $this->mZhLanguageCode;
    }
    
    
  /* the Simplified/Traditional conversion stuff */

	function simp2trad($text) {
		global $wgZhSimp2Trad;
		return strtr($text, $wgZhSimp2Trad);
	}

	function trad2simp($text) {
		global $wgZhTrad2Simp;
		return strtr($text, $wgZhTrad2Simp);
	}
	
	function convert($text) {

		// no conversion if redirecting
		if(substr($text,0,9) == "#REDIRECT") {
			return $text;
		}
        
		// determine the preferred language from the request header
		$tolang = $this->getPreferredVariant();
	
		$ltext = explode("-{", $text);
		$lfirst = array_shift($ltext);
		
		if($tolang == "zh-cn") {
			$text = $this->trad2simp($lfirst);
		}
		else {
			$text = $this->simp2trad($lfirst);
		}
		
		foreach ($ltext as $txt) {
			$a = explode("}-", $txt);
			$b = explode("zh-", $a{0});
			if($b{1}==NULL) {
				$text = $text.$b{0};
			}
			else {
				foreach ($b as $lang) {
					if(substr($lang,0,2) == substr($tolang,-2)) {
						$text = $text.substr($lang, 2);
						break;
					}
				}
			}
			if($tolang == "zh-cn") {
				$text = $text.$this->trad2simp($a{1});
			}
			else {
				$text = $text.$this->simp2trad($a{1});
			}
		}

		return $text;
	}
	
    function getVariants() {
        return array("zh_cn", "zh_tw");
    }


    /* these just calls the method of the corresponding class */
    
    function getDefaultUserOptions () {
        return $this->mZhLang->getDefaultUserOptions();
    }

	function getBookstoreList () {
		return $this->mZhLang->getBookstoreList() ;
	}

	function getNamespaces() {
		return $this->mZhLang->getNamespaces();
	}

	function getNsText( $index ) {
        return $this->mZhLang->getNsText($index);
	}

	function getNsIndex( $text ) {
        return $this->mZhLang->getNsIndex($text);
	}

	function getQuickbarSettings() {
		return $this->mZhLang->getQuickbarSettings();
	}

	function getSkinNames() {
		return $this->mZhLang->getSkinNames();
	}

	function date( $ts, $adj = false )
	{
        return $this->mZhLang->date($ts,$adj);
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->mZhLang->timeanddate($ts, $adj);
	}

	function getValidSpecialPages()
	{
		return $this->mZhLang->getValidSpecialPages();
	}

	function getSysopSpecialPages()
	{
		return $this->mZhLang->getSysopSpecialPages();
	}

	function getDeveloperSpecialPages()
	{
		return $this->mZhLang->getDeveloperSpecialPages();

	}

	function getMessage( $key )
	{
        return $this->mZhLang->getMessage($key);
	}

	function stripForSearch( $string ) {
        return $this->mZhLang->stripForSearch($string);
	}

    
}


?>
