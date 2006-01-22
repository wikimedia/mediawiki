<?php
/**
 * @package MediaWiki
 * @subpackage Language
 */

# Most of this was written by Ævar Arnfjörð Bjarmason <avarab@gmail.com>

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesIs = array(
	NS_MEDIA		=> 'Miðill',
	NS_SPECIAL		=> 'Kerfissíða',
	NS_MAIN			=> '',
	NS_TALK			=> 'Spjall',
	NS_USER			=> 'Notandi',
	NS_USER_TALK		=> 'Notandaspjall',
	NS_PROJECT		=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace . 'spjall',
	NS_IMAGE		=> 'Mynd',
	NS_IMAGE_TALK		=> 'Myndaspjall',
	NS_MEDIAWIKI		=> 'Melding',
	NS_MEDIAWIKI_TALK	=> 'Meldingarspjall',
	NS_TEMPLATE		=> 'Snið',
	NS_TEMPLATE_TALK	=> 'Sniðaspjall',
	NS_HELP			=> 'Hjálp',
	NS_HELP_TALK		=> 'Hjálparspjall',
	NS_CATEGORY		=> 'Flokkur',
	NS_CATEGORY_TALK	=> 'Flokkaspjall'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsIs = array(
	'Sleppa', 'Fast vinstra megin', 'Fast hægra megin', 'Fljótandi til vinstri'
);

/* private */ $wgSkinNamesIs = array(
	'standard'	=> 'Klassískt',
	'nostalgia'	=> 'Gamaldags',
	'cologneblue'	=> 'Kölnarblátt',
	'myskin'	=> 'Mitt þema',
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsIs = array(
	'Sjálfgefið',
	'15. janúar 2001 kl. 16:12',
	'15. jan. 2001 kl. 16:12',
	'16:12, 15. janúar 2001',
	'16:12, 15. jan. 2001',
	'ISO 8601' => '2001-01-15 16:12:34'
);

$wgMagicWordsIs = array(
	MAG_REDIRECT             => array( 0, '#tilvísun', '#TILVÍSUN', '#redirect' ), // MagicWord::initRegex() sucks
) + $wgMagicWordsEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesIs.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageIs extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesIs;
		return $wgNamespaceNamesIs;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsIs;
		return $wgQuickbarSettingsIs;
	}

	function getSkinNames() {
		global $wgSkinNamesIs;
		return $wgSkinNamesIs;
	}

	function getDateFormats() {
		global $wgDateFormatsIs;
		return $wgDateFormatsIs;
	}

	function getMessage( $key ) {
		global $wgAllMessagesIs;
		if( isset( $wgAllMessagesIs[$key] ) ) {
			return $wgAllMessagesIs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		global $wgAllMessagesIs;
		return $wgAllMessagesIs;
	}

	function getMagicWords() {
		global $wgMagicWordsIs;
		return $wgMagicWordsIs;
	}

	function date( $ts, $adj = false, $format = true) {
		global $wgUser;
		if ( $adj ) { $ts = $this->userAdjust( $ts ); } # Adjust based on the timezone setting.
		$format = $this->dateFormat($format);

		switch( $format ) {
			# 15. jan. 2001 kl. 16:12 || 16:12, 15. jan. 2001
			case '2': case '4': return (0 + substr( $ts, 6, 2 )) . '. ' .
				$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . '. ' .
				substr($ts, 0, 4);
			# 2001-01-15 16:12:34
			case 'ISO 8601': return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);

			# 15. janúar 2001 kl. 16:12 || 16:12, 15. janúar 2001
			default: return (0 + substr( $ts, 6, 2 )) . '. ' .
				$this->getMonthName( substr( $ts, 4, 2 ) ) . ' ' .
				substr($ts, 0, 4);
		}

	}

	function time($ts, $adj = false, $format = true) {
		global $wgUser;
		if ( $adj ) { $ts = $this->userAdjust( $ts ); } # Adjust based on the timezone setting.

		$format = $this->dateFormat($format);

		switch( $format ) {
			# 2001-01-15 16:12:34
			case 'ISO 8601': return substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 ) . ':' . substr( $ts, 12, 2 );
			default: return substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
		}

	}

	function timeanddate( $ts, $adj = false, $format = true) {
		global $wgUser;

		$format = $this->dateFormat($format);

		switch ( $format ) {
			# 16:12, 15. janúar 2001 || 16:12, 15. jan. 2001
			case '3': case '4': return $this->time( $ts, $adj, $format ) . ', ' . $this->date( $ts, $adj, $format );
			# 2001-01-15 16:12:34
			case 'ISO 8601': return $this->date( $ts, $adj, $format ) . ' ' . $this->time( $ts, $adj, $format );
			# 15. janúar 2001 kl. 16:12 || 15. jan. 2001 kl. 16:12
			default: return $this->date( $ts, $adj, $format ) . ' kl. ' . $this->time( $ts, $adj, $format );

		}

	}

	/**
	 * The Icelandic number style uses dots where English would use commas
	 * and commas where English would use dots, e.g. 201.511,17 not 201,511.17
	 */
	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}

	function linkPrefixExtension() {
		return true;
	}
}

?>
