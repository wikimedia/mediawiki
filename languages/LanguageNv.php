<?php
/** Navajo (Diné bizaad)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

class LanguageNv extends LanguageUtf8 {
	private $mMessagesNv, $mNamespaceNamesNv = null;

	private $mSkinNamesNv = array(
		'mono' => 'Łáa\'ígíí',
		'monobook' => 'NaaltsoosŁáa\'ígíí'
	);
	
	private $mWeekdayNamesNv = array(
		'Damóogo', 'Damóo biiskání', 'Damóodóó naakiską́o', 'Damóodóó tágí jį́', 'Damóodóó dį́į́\' yiską́o',
		'Nda\'iiníísh', 'Yiską́ damóo'
	);
	
	private $mMonthNamesNv = array(
		'Yas Niłt\'ees', 'Atsá Biyáázh', 'Wóózhch\'į́į́d', 'T\'ą́ą́chil', 'T\'ą́ą́tsoh', 'Ya\'iishjááshchilí',
		'Ya\'iishjáástsoh', 'Bini\'ant\'ą́ą́ts\'ózí', 'Bini\'ant\'ą́ą́tsoh', 'Ghąąjį', 'Níłch\'its\'ósí',
		'Níłch\'itsoh'
	);
	
	private $mMonthAbbreviationsNv = array(
		'Ynts', 'Atsb', 'Wozh', 'Tchi', 'Ttso', 'Yjsh', 'Yjts', 'Btsz',
		'Btsx', 'Ghąj', 'Ntss', 'Ntsx'
	);

	function __construct() {
		parent::__construct();

		global $wgMetaNamespace;
		$this->mNamespaceNamesNv = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Special',
			NS_MAIN	            => '',
			NS_TALK	            => 'Naaltsoos_baa_yinísht\'į́',
			NS_USER             => 'Choinish\'įįhí',
			NS_USER_TALK        => 'Choinish\'įįhí_baa_yinísht\'į́',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace.'_baa_yinísht\'į́',
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

	}

	function getNamespaces() {
		return $this->mNamespaceNamesNv + parent::getNamespaces();
	}

	function getSkinNames() {
		return $this->mSkinNamesNv;
	}

	function getDateFormats() {
		return false;
	}

	function getMonthName( $key ) {
		return $this->mMonthNamesNv[$key-1];
	}

	function getMonthAbbreviation( $key ) {
		return @$this->mMonthAbbreviationsNv[$key-1];
	}

	function getWeekdayName( $key ) {
		return $this->mWeekdayNamesNv[$key-1];
	}


}

?>
