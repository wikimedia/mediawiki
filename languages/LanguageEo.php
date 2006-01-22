<?php
/** Esperanto (Esperanto)
  * @package MediaWiki
  * @subpackage Language
  */

/** */
require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesEo = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Speciala',
	NS_MAIN           => '',
	NS_TALK           => 'Diskuto',
	NS_USER           => 'Vikipediisto',
	NS_USER_TALK      => 'Vikipediista_diskuto',
	NS_PROJECT	      => $wgMetaNamespace, # FIXME: Generalize v-isto kaj v-io
	NS_PROJECT_TALK   => $wgMetaNamespace.'_diskuto', # FIXME
	NS_IMAGE          => 'Dosiero', #FIXME: Check the magic for Image: and Media:
	NS_IMAGE_TALK     => 'Dosiera_diskuto',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_diskuto',
	NS_TEMPLATE       => 'Ŝablono',
	NS_TEMPLATE_TALK  => 'Ŝablona_diskuto',
	NS_HELP           => 'Helpo',
	NS_HELP_TALK      => 'Helpa_diskuto',
	NS_CATEGORY       => 'Kategorio',
	NS_CATEGORY_TALK  => 'Kategoria_diskuto',

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsEo = array(
	'Nenia', 'Fiksiĝas maldekstre', 'Fiksiĝas dekstre', 'Ŝvebas maldekstre'
);

/* private */ $wgSkinNamesEo = array(
	'standard' => 'Klasika',
	'nostalgia' => 'Nostalgio',
	'cologneblue' => 'Kolonja Bluo',
	'mono' => 'Senkolora',
	'monobook' => 'Librejo',
	'chick' => 'Kokido',
) + $wgSkinNamesEn;



# Se eble, trovu Esperantajn libroservoj traserĉeblaj laŭ ISBN
# $wgBookstoreListEo = ..

if (!$wgCachedMessageArrays) {
	require_once('MessagesEo.php');
}

/** @package MediaWiki */
class LanguageEo extends LanguageUtf8 {

	function getDefaultUserOptions () {
		$opt = parent::getDefaultUserOptions();
		$opt['altencoding'] = 0;
		return $opt;
	}

	function getNamespaces() {
		global $wgNamespaceNamesEo;
		return $wgNamespaceNamesEo;
	}


	function getNsIndex( $text ) {
		global $wgNamespaceNamesEo;

		foreach ( $wgNamespaceNamesEo as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEo;
		return $wgQuickbarSettingsEo;
	}

	function getSkinNames() {
		global $wgSkinNamesEo;
		return $wgSkinNamesEo;
	}

	# La dato- kaj tempo-funkciojn oni povas precizigi laŭ lingvo
	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function formatDay( $day, $format ) {
		return parent::formatDay( $day, $format ) . '.';
	}

	function getMessage( $key ) {
		global $wgAllMessagesEo;
		if(array_key_exists($key, $wgAllMessagesEo))
			return $wgAllMessagesEo[$key];
		else
			return parent::getMessage($key);
	}

	function iconv( $in, $out, $string ) {
		# For most languages, this is a wrapper for iconv
		# Por multaj lingvoj, ĉi tiu nur voku la sisteman funkcion iconv()
		# Ni ankaŭ konvertu X-sistemajn surogotajn
		if( strcasecmp( $in, 'x' ) == 0 and strcasecmp( $out, 'utf-8' ) == 0) {
			$xu = array (
				'xx' => 'x' , 'xX' => 'x' ,
				'Xx' => 'X' , 'XX' => 'X' ,
				"Cx" => "\xc4\x88" , "CX" => "\xc4\x88" ,
				"cx" => "\xc4\x89" , "cX" => "\xc4\x89" ,
				"Gx" => "\xc4\x9c" , "GX" => "\xc4\x9c" ,
				"gx" => "\xc4\x9d" , "gX" => "\xc4\x9d" ,
				"Hx" => "\xc4\xa4" , "HX" => "\xc4\xa4" ,
				"hx" => "\xc4\xa5" , "hX" => "\xc4\xa5" ,
				"Jx" => "\xc4\xb4" , "JX" => "\xc4\xb4" ,
				"jx" => "\xc4\xb5" , "jX" => "\xc4\xb5" ,
				"Sx" => "\xc5\x9c" , "SX" => "\xc5\x9c" ,
				"sx" => "\xc5\x9d" , "sX" => "\xc5\x9d" ,
				"Ux" => "\xc5\xac" , "UX" => "\xc5\xac" ,
				"ux" => "\xc5\xad" , "uX" => "\xc5\xad"
				) ;
			return preg_replace ( '/([cghjsu]x?)((?:xx)*)(?!x)/ei',
			  'strtr( "$1", $xu ) . strtr( "$2", $xu )', $string );
		} else if( strcasecmp( $in, 'UTF-8' ) == 0 and strcasecmp( $out, 'x' ) == 0 ) {
			$ux = array (
				'x' => 'xx' , 'X' => 'Xx' ,
				"\xc4\x88" => "Cx" , "\xc4\x89" => "cx" ,
				"\xc4\x9c" => "Gx" , "\xc4\x9d" => "gx" ,
				"\xc4\xa4" => "Hx" , "\xc4\xa5" => "hx" ,
				"\xc4\xb4" => "Jx" , "\xc4\xb5" => "jx" ,
				"\xc5\x9c" => "Sx" , "\xc5\x9d" => "sx" ,
				"\xc5\xac" => "Ux" , "\xc5\xad" => "ux"
			) ;
			# Double Xs only if they follow cxapelutaj literoj.
			return preg_replace( '/((?:[cghjsu]|\xc4[\x88\x89\x9c\x9d\xa4\xa5\xb4\xb5]'.
			  '|\xc5[\x9c\x9d\xac\xad])x*)/ei', 'strtr( "$1", $ux )', $string );
		}
		return iconv( $in, $out, $string );
	}

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;

		# Check for X-system backwards-compatibility URLs
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
			'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );

		if($ishigh and !$isutf) {
			# Assume Latin1
			$s = utf8_encode( $s );
		} else {
			if( preg_match( '/(\xc4[\x88\x89\x9c\x9d\xa4\xa5\xb4\xb5]'.
				'|\xc5[\x9c\x9d\xac\xad])/', $s ) )
			return $s;
		}

		//if( preg_match( '/[cghjsu]x/i', $s ) )
		//	return $this->iconv( 'x', 'utf-8', $s );
		return $s;
	}

	function initEncoding() {
		global $wgEditEncoding, $wgInputEncoding, $wgOutputEncoding;
		$wgInputEncoding = 'utf-8';
		$wgOutputEncoding = 'utf-8';
		$wgEditEncoding = 'x';
	}

	function setAltEncoding() {
		global $wgEditEncoding, $wgInputEncoding, $wgOutputEncoding;
		$wgInputEncoding = 'utf-8';
		$wgOutputEncoding = 'x';
		$wgEditEncoding = '';
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ', ' );
	}
}

?>
