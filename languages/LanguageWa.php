<?php
/**
 * Walloon (Walon)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( "LanguageUtf8.php" );

if (!$wgCachedMessageArrays) {
	require_once('MessagesWa.php');
}

# NOTE: cweri après "NOTE:" po des racsegnes so des ratournaedjes
# k' i gn a.

define( 'MW_DATE_WLN_LONG',  MW_DATE_DMY );
define( 'MW_DATE_WLN_SHORT', '4' );

class LanguageWa extends LanguageUtf8 {
	private $mMessagesWa, $mNamespaceNamesWa = null;

	private $mQuickbarSettingsWa = array(
		"Nole bår", "Aclawêye a hintche", "Aclawêye a droete", "Flotante a hintche", "Flotante a droete"
	);

	# lists "no preferences", normall (long) walloon date,
	# short walloon date, and ISO format
	# MW_DATE_DMY is alias for long format, as it is dd mmmmm yyyy.
	# "4" is chosen as value for short format, as it is used in LanguageVi.php 
	private $mDateFormatsWa = array(
		MW_DATE_DEFAULT => 'Nole preferince',
		#MW_DATE_DMY => '16:12, 15 January 2001',
		#MW_DATE_MDY => '16:12, January 15, 2001',
		#MW_DATE_YMD => '16:12, 2001 January 15',
		MW_DATE_WLN_LONG => '15 di djanvî 2001 a 16:12',
		MW_DATE_WLN_SHORT => '15/01/2001 a 16:12',
		MW_DATE_ISO => '2001-01-15 16:12:34',
	);



	function __construct() {
		parent::__construct();

		global $wgAllMessagesWa;
		$this->mMessagesWa =& $wgAllMessagesWa;

		global $wgMetaNamespace;
		$this->mNamespaceNamesWa = array(
			NS_MEDIA          => "Media", /* Media */
			NS_SPECIAL        => "Sipeciås", /* Special */
			NS_MAIN           => "",
			NS_TALK           => "Copene", /* Talk */
			NS_USER	          => "Uzeu", /* User */
			NS_USER_TALK      => "Uzeu_copene", /* User_talk */
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . "_copene",
			NS_IMAGE          => "Imådje", /* Image */
			NS_IMAGE_TALK     => "Imådje_copene", /* Image_talk */
			NS_MEDIAWIKI      => "MediaWiki", /* MediaWiki */
			NS_MEDIAWIKI_TALK => "MediaWiki_copene", /* MediaWiki_talk */
			NS_TEMPLATE       => "Modele",
			NS_TEMPLATE_TALK  => "Modele_copene",
			NS_HELP           => "Aidance",
			NS_HELP_TALK      => "Aidance_copene",
			NS_CATEGORY       => "Categoreye",
			NS_CATEGORY_TALK  => "Categoreye_copene",
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesWa + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsWa;
	}

	function getDateFormats() {
		return $this->mDateFormatsWa;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesWa[$key] ) ) {
			return $this->mMessagesWa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesWa;
	}

	###
	### Dates in Walloon are "1î d' <monthname>" for 1st of the month,
	### "<day> di <monthname>" for months starting by a consoun, and
	### "<day> d' <monthname>" for months starting with a vowel
	###
	function date( $ts, $adj = false, $format = true, $tc = false ) {
		global $wgUser;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $tc ); }
		$datePreference = $this->dateFormat( $format );

		# ISO (YYYY-mm-dd) format
		#
		# we also output this format for YMD (eg: 2001 January 15)
		if ( $datePreference == MW_DATE_ISO ||
		     $datePreference == MW_DATE_YMD ) {
		       $d = substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
		       return $d;
		}
		
		# dd/mm/YYYY format
		if ( $datePreference == MW_DATE_WLN_SHORT ) {
		       $d = substr($ts, 6, 2). '/' . substr($ts, 4, 2). '/' .substr($ts, 0, 4);
		       return $d;
		}
		
		# Walloon format
		#
		# we output this in all other cases
		$m = substr( $ts, 4, 2 );
		$n = substr( $ts, 6, 2 );
		if ($n == 1) {
		    $d = "1î d' " . $this->getMonthName( $m ) .
			" " .  substr( $ts, 0, 4 );
		} else if ($n == 2 || $n == 3 || $n == 20 || $n == 22 || $n == 23) {
		    $d = (0 + $n) . " d' " . $this->getMonthName( $m ) .
			" " .  substr( $ts, 0, 4 );
		} else if ($m == 4 || $m == 8 || $m == 10) {
		    $d = (0 + $n) . " d' " . $this->getMonthName( $m ) .
			" " .  substr( $ts, 0, 4 );
		} else {
		    $d = (0 + $n) . " di " . $this->getMonthName( $m ) .
			" " .  substr( $ts, 0, 4 );
		}
		return $d;
	}

	function timeBeforeDate( ) {
		return false;
	}

	function timeDateSeparator( $format ) {
		return " a ";
	}

	# definixha del cogne po les limeros
	# (number format definition)
	# en: 12,345.67 -> wa: 12 345,67
	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

	function linkTrail() {
		return '/^([a-zåâêîôûçéè]+)(.*)$/sDu';
	}

}

?>
