<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesFur = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciâl',
	NS_MAIN				=> '',
	NS_TALK				=> 'Discussion',
	NS_USER				=> 'Utent',
	NS_USER_TALK			=> 'Discussion_utent',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK			=> 'Discussion_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Figure',
	NS_IMAGE_TALK			=> 'Discussion_figure',
	NS_MEDIAWIKI			=> 'MediaWiki',
	NS_MEDIAWIKI_TALK		=> 'Discussion_MediaWiki',
	NS_TEMPLATE			=> 'Model',
	NS_TEMPLATE_TALK		=> 'Discussion_model',
	NS_HELP				=> 'Jutori',
	NS_HELP_TALK			=> 'Discussion_jutori',
	NS_CATEGORY			=> 'Categorie',
	NS_CATEGORY_TALK		=> 'Discussion_categorie'
) + $wgNamespaceNamesEn;



/* private */ $wgQuickbarSettingsFur = array(
	'Nissune', 'Fis a Çampe', 'Fis a Drete', 'Flutuant a çampe'
);

/* private */ $wgSkinNamesFur = array(
	'nostalgia'		=> 'Nostalgie',
) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesFur.php');
}

class LanguageFur extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesFur;
		return $wgNamespaceNamesFur;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFur;
		return $wgQuickbarSettingsFur;
	}

	function getSkinNames() {
		global $wgSkinNamesFur;
		return $wgSkinNamesFur;
	}


	function getDateFormats() {
		return false;
	}


	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " di " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " a lis " . $this->time( $ts, $adj );
	}

	var $digitTransTable = array(
		',' => "\xc2\xa0", // @bug 2749
		'.' => ','
	);

	function formatNum( $number, $year = false ) {
		return $year? $number : strtr($this->commafy($number), $this->digitTransTable);
	}


	function getMessage( $key ) {
		global $wgAllMessagesFur;
		if( isset( $wgAllMessagesFur[$key] ) ) {
			return $wgAllMessagesFur[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
