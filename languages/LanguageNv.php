<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

# Navajo language file
# No messages at the moment, just all the other stuff

require_once( "LanguageUtf8.php" );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesNv = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN	            => '',
	NS_TALK	            => 'Naaltsoos_baa_yinísht\'į́',
	NS_USER             => 'Choinish\'įįhí',
	NS_USER_TALK        => 'Choinish\'įįhí_baa_yinísht\'į́',
	NS_PROJECT          => 'Wikiibíídiiya',
	NS_PROJECT_TALK     => 'Wikiibíídiiya_baa_yinísht\'į́',
	NS_IMAGE            => 'E\'elyaaígíí',
	NS_IMAGE_TALK       => 'E\'elyaaígíí_baa_yinísht\'į́',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_baa_yinísht\'į́',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Aná\'álwo\'',
	NS_HELP_TALK        => 'Aná\'álwo\'_baa_yinísht\'į́',
	NS_CATEGORY         => 'T\'ááłáhági_át\'éego',
	NS_CATEGORY_TALK    => 'T\'ááłáhági_át\'éego_baa_yinísht\'į́'
);

/* private */ $wgQuickbarSettingsNv = array(
	'None', 'Fixed left', 'Fixed right', 'Floating left'
);

/* private */ $wgSkinNamesNv = array(
	'mono' => 'Łáa\'ígíí',
	'monobook' => 'NaaltsoosŁáa\'ígíí'
) + $wgSkinNamesEn;


/* private */ $wgDateFormatsNv = array(
#	'No preference',
);

/* private */ $wgWeekdayNamesNv = array(
	'Damóogo', 'Damóo biiskání', 'Damóodóó naakiską́o', 'Damóodóó tágí jį́', 'Damóodóó dį́į́\' yiską́o',
	'Nda\'iiníísh', 'Yiską́ damóo'
);

/* private */ $wgMonthNamesNv = array(
	'Yas Niłt\'ees', 'Atsá Biyáázh', 'Wóózhch\'į́į́d', 'T\'ą́ą́chil', 'T\'ą́ą́tsoh', 'Ya\'iishjááshchilí',
	'Ya\'iishjáástsoh', 'Bini\'ant\'ą́ą́ts\'ózí', 'Bini\'ant\'ą́ą́tsoh', 'Ghąąjį', 'Níłch\'its\'ósí',
	'Níłch\'itsoh'
);

/* private */ $wgMonthAbbreviationsNv = array(
	'Ynts', 'Atsb', 'Wozh', 'Tchi', 'Ttso', 'Yjsh', 'Yjts', 'Btsz',
	'Btsx', 'Ghąj', 'Ntss', 'Ntsx'
);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageNv extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesNv;
		return $wgNamespaceNamesNv;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsNv;
		return $wgQuickbarSettingsNv;
	}

	function getSkinNames() {
		global $wgSkinNamesNv;
		return $wgSkinNamesNv;
	}

	function getDateFormats() {
		global $wgDateFormatsNv;
		return $wgDateFormatsNv;
	}

	function getMonthName( $key ) {
		global $wgMonthNamesNv;
		return $wgMonthNamesNv[$key-1];
	}

	/* by default we just return base form */
	function getMonthNameGen( $key ) {
		return $this->getMonthName( $key );
	}

	function getMonthAbbreviation( $key ) {
		global $wgMonthAbbreviationsNv;
		return @$wgMonthAbbreviationsNv[$key-1];
	}

	function getWeekdayName( $key ) {
		global $wgWeekdayNamesNv;
		return $wgWeekdayNamesNv[$key-1];
	}


}

?>
