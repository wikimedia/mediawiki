<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesEn = array(
  NS_MEDIA            => 'Medija',
  NS_SPECIAL          => 'Specialus',
  NS_MAIN	          => '',
  NS_TALK	          => 'Aptarimas',
  NS_USER             => 'Naudotojas',
  NS_USER_TALK        => 'Naudotojo_aptarimas',
  NS_PROJECT          => $wgMetaNamespace,
  NS_PROJECT_TALK     => $wgMetaNamespace.'_aptarimas',
  NS_IMAGE            => 'Vaizdas',
  NS_IMAGE_TALK       => 'Vaizdo_aptarimas',
  NS_MEDIAWIKI        => 'MediaWiki',
  NS_MEDIAWIKI_TALK   => 'MediaWiki_aptarimas',
  NS_TEMPLATE         => 'Šablonas',
  NS_TEMPLATE_TALK    => 'Šablono_aptarimas',
  NS_HELP             => 'Pagalba',
  NS_HELP_TALK        => 'Pagalbos_aptarimas',
  NS_CATEGORY         => 'Kategorija',
  NS_CATEGORY_TALK    => 'Kategorijos_aptarimas',
  );

/* private */ $wgQuickbarSettingsLt = array(
	"Nerodyti", "Fiksuoti kairėje", "Fiksuoti dešinėje", "Plaukiojantis kairėje"
);

/* private */ $wgSkinNamesLt = array(
	'standard' => 'Standartinė',
	'nostalgia' => 'Nostalgija',
	'cologneblue' => 'Kiolno Mėlyna',
	'davinci' => 'Da Vinči',
	'mono' => 'Mono',
	'monobook' => 'MonoBook',
	'myskin' => 'MySkin',
	'chick' => 'Chick'
) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesLt.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageLt extends LanguageUtf8  {
	# Inherent default user options unless customization is desired

	function getQuickbarSettings() {
		global $wgQuickbarSettingsLt;
		return $wgQuickbarSettingsLt;
	}

	function getSkinNames() {
		global $wgSkinNamesLt;
		return $wgSkinNamesLt;
	}

	function fallback8bitEncoding() {
		return "windows-1257";
	}

	function getMessage( $key ) {
		global $wgAllMessagesLt;

		if(array_key_exists($key, $wgAllMessagesLt))
			return $wgAllMessagesLt[$key];
		else
			return parent::getMessage($key);
	}

	function getAllMessages() {
		global $wgAllMessagesLt;
		return $wgAllMessagesLt;
	}

	function separatorTransformTable() {
		return array(',' => ' ', '.' => ',' );
	}
	
	/* Word forms (with examples):
		1 - vienas (1) lapas
		2 - trys (3) lapai
		3 - penkiolika (15) lapų
	*/	
	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		$count = str_replace (' ', '', $count);
		if ($count%10==1 && $count%100!=11) return $wordform1;
		if ($count%10>=2 && ($count%100<10 || $count%100>=20)) return $wordform2;
		return $wordform3;
	}
}
?>
