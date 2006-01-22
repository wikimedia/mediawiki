<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

# NOTE: cweri après "NOTE:" po des racsegnes so des ratournaedjes
# k' i gn a.

/* private */ $wgNamespaceNamesWa = array(
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
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsWa = array(
	"Nole bår", "Aclawêye a hintche", "Aclawêye a droete", "Flotante a hintche", "Flotante a droete"
);

/* private */ $wgDateFormatsWa = array( /* cwè fé chal ??? */
#	"Nole preferince",
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesWa.php');
}


#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageWa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesWa;
		return $wgNamespaceNamesWa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsWa;
		return $wgQuickbarSettingsWa;
	}

	function getDateFormats() {
		global $wgDateFormatsWa;
		return $wgDateFormatsWa;
	}


	###
	### Dates in Walloon are "1î d' <monthname>" for 1st of the month,
	### "<day> di <monthname>" for months starting by a consoun, and
	### "<day> d' <monthname>" for months starting with a vowel
	###
	function date( $ts, $adj = false ) {
		global $wgAmericanDates, $wgUser, $wgUseDynamicDates;

		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

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

	function time( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false ) {
		#return $this->time( $ts, $adj ) . " " . $this->date( $ts, $adj );
		return $this->date( $ts, $adj ) . " a " . $this->time( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesWa;

		if(array_key_exists($key, $wgAllMessagesWa))
			return $wgAllMessagesWa[$key];
		else
			return parent::getMessage($key);
	}

	function getAllMessages() {
		global $wgAllMessagesWa;
		return $wgAllMessagesWa;
	}

}

?>
