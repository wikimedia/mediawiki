<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesBr = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Dibar',
	NS_MAIN				=> '',
	NS_TALK				=> 'Kaozeal',
	NS_USER				=> 'Implijer',
	NS_USER_TALK		=> 'Kaozeadenn_Implijer',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Kaozeadenn_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Skeudenn',
	NS_IMAGE_TALK		=> 'Kaozeadenn_Skeudenn',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Kaozeadenn_MediaWiki',
	NS_TEMPLATE			=> 'Patrom',
	NS_TEMPLATE_TALK	=> 'Kaozeadenn_Patrom',
	NS_HELP				=> 'Skoazell',
	NS_HELP_TALK		=> 'Kaozeadenn_Skoazell',
	NS_CATEGORY			=> 'Rummad',
	NS_CATEGORY_TALK	=> 'Kaozeadenn_Rummad'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsBr = array(
	'Hini ebet', 'Kleiz', 'Dehou', 'War-neuñv a-gleiz'
);

/* private */ $wgSkinNamesBr = array(
	'standard'		=> 'Standard',
	'nostalgia'		=> 'Melkoni',
	'cologneblue'	=> 'Glaz Kologn',
	'smarty'		=> 'Paddington',
	'montparnasse'	=> 'Montparnasse',
	'davinci'		=> 'DaVinci',
	'mono'			=> 'Mono',
	'monobook'		=> 'MonoBook',
	'myskin'		=> 'MySkin'
);



/* private */ $wgBookstoreListBr = array(
	'Amazon.fr'		=> 'http://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'	=> 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'		=> 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com'	=> 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesBr.php');
}

class LanguageBr extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListBr ;
		return $wgBookstoreListBr ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesBr;
		return $wgNamespaceNamesBr;
	}

	function getDateFormats() {
		return false;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesBr, $wgSitename;

		foreach ( $wgNamespaceNamesBr as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( $wgSitename == "Wikipedia" ) {
			if( 0 == strcasecmp( "Discussion_Wikipedia", $text ) ) return 5;
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsBr;
		return $wgQuickbarSettingsBr;
	}

	function getSkinNames() {
		global $wgSkinNamesBr;
		return $wgSkinNamesBr;
	}


	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " da " . $this->time( $ts, $adj );
	}

	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

	function getMessage( $key ) {
		global $wgAllMessagesBr, $wgAllMessagesEn;
		if( isset( $wgAllMessagesBr[$key] ) ) {
			return $wgAllMessagesBr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
