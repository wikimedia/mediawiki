<?php
/** Yiddish (ייִדיש)
  *
  * @bug 3810
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once 'LanguageUtf8.php';

$wgNamespaceNamesYi = array(
	NS_MEDIA => 'מעדיע',
	NS_SPECIAL => 'באַזונדער',
	NS_MAIN => '',
	NS_TALK => 'רעדן',
	NS_USER => 'באַניצער',
	NS_USER_TALK => 'באַניצער_רעדן',
	NS_PROJECT => $wgMetaNamespace,
	NS_PROJECT_TALK => $wgMetaNamespace . '_רעדן',
	NS_IMAGE => 'בילד',
	NS_IMAGE_TALK => 'בילד_רעדן',
	NS_MEDIAWIKI => 'מעדיעװיקי',
	NS_MEDIAWIKI_TALK => 'מעדיעװיקי_רעדן',
	NS_TEMPLATE => 'מוסטער',
	NS_TEMPLATE_TALK => 'מוסטער_רעדן',
	NS_HELP => 'הילף',
	NS_HELP_TALK => 'הילף_רעדן',
	NS_CATEGORY => 'קאַטעגאָריע',
	NS_CATEGORY_TALK=> 'קאַטעגאָריע_רעדן'
);

class LanguageYi extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesYi;
		return $wgNamespaceNamesYi;
	}

	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();
		$opt['quickbar'] = 2; # Right-to-left
		return $opt;
	}

	# For right-to-left language support
	function isRTL() {
		return true;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesYi, $wgSitename;

		foreach ( $wgNamespaceNamesYi as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( $wgSitename == 'װיקיפּעדיע' ) {
			if( 0 == strcasecmp( 'וויקיפעדיע', $text ) ) return NS_PROJECT;
			if( 0 == strcasecmp( 'וויקיפעדיע_רעדן', $text ) ) return NS_PROJECT_TALK;
		}
		if( $wgSitename == 'װיקיביבליאָטעק' ) {
			if( 0 == strcasecmp( 'וויקיביבליאטעק', $text ) ) return NS_PROJECT;
			if( 0 == strcasecmp( 'וויקיביבליאטעק_רעדן', $text ) ) return NS_PROJECT_TALK;
		}
		if( $wgSitename == 'װיקיװערטערבוך' ) {
			if( 0 == strcasecmp( 'וויקיווערטערבוך', $text ) ) return NS_PROJECT;
			if( 0 == strcasecmp( 'וויקיווערטערבוך_רעדן', $text ) ) return NS_PROJECT_TALK;
		}
		if( $wgSitename == 'װיקינײַעס' ) {
			if( 0 == strcasecmp( 'וויקינייעס', $text ) ) return NS_PROJECT;
			if( 0 == strcasecmp( 'וויקינייעס_רעדן', $text ) ) return NS_PROJECT_TALK;
		}
		if( 0 == strcasecmp( 'באזונדער', $text ) ) return NS_SPECIAL;
		if( 0 == strcasecmp( 'באנוצער', $text ) ) return NS_USER;
		if( 0 == strcasecmp( 'באנוצער_רעדן', $text ) ) return NS_USER_TALK;
		if( 0 == strcasecmp( 'מעדיעוויקי', $text ) ) return NS_MEDIAWIKI;
		if( 0 == strcasecmp( 'מעדיעוויקי_רעדן', $text ) ) return NS_MEDIAWIKI_TALK;
		if( 0 == strcasecmp( 'קאטעגאריע', $text ) ) return NS_CATEGORY;
		if( 0 == strcasecmp( 'קאטעגאריע_רעדן', $text ) ) return NS_CATEGORY_TALK;
		return false;
	}
}

?>
