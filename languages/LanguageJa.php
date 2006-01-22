<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
global $IP;
require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesJa = array(
	NS_MEDIA          => "Media", /* Media */
	NS_SPECIAL        => "特別", /* Special */
	NS_MAIN           => "",
	NS_TALK           => "ノート", /* Talk */
	NS_USER           => "利用者", /* User */
	NS_USER_TALK      => "利用者‐会話", /* User_talk */
	NS_PROJECT        => $wgMetaNamespace, /* Wikipedia */
	NS_PROJECT_TALK   => "{$wgMetaNamespace}‐ノート", /* Wikipedia_talk */
	NS_IMAGE          => "画像", /* Image */
	NS_IMAGE_TALK     => "画像‐ノート", /* Image_talk */
	NS_MEDIAWIKI      => "MediaWiki", /* MediaWiki */
	NS_MEDIAWIKI_TALK => "MediaWiki‐ノート", /* MediaWiki_talk */
	NS_TEMPLATE       => "Template", /* Template */
	NS_TEMPLATE_TALK  => "Template‐ノート", /* Template_talk */
	NS_HELP           => "Help", /* Help */
	NS_HELP_TALK      => "Help‐ノート", /* Help_talk */
	NS_CATEGORY       => "Category", /* Category */
	NS_CATEGORY_TALK  => "Category‐ノート" /* Category_talk */
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsJa = array(
	"なし", "左端", "右端", "ウィンドウの左上に固定"
);

/* private */ $wgSkinNamesJa = array(
	'standard' => "標準",
	'nostalgia' => "ノスタルジア",
	'cologneblue' => "ケルンブルー",
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsJa = array(
	MW_DATE_DEFAULT => '2001年1月15日 16:12 (デフォルト)',
	MW_DATE_ISO => '2001-01-15 16:12:34'
);

/* private */ $wgWeekdayAbbreviationsJa = array(
	"日", "月", "火", "水", "木", "金", "土"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesJa.php');
}

class LanguageJa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesJa;
		return $wgNamespaceNamesJa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsJa;
		return $wgQuickbarSettingsJa;
	}

	function getSkinNames() {
		global $wgSkinNamesJa;
		return $wgSkinNamesJa;
	}

	function getDateFormats() {
		global $wgDateFormatsJa;
		return $wgDateFormatsJa;
	}

	function date( $ts, $adj = false, $format = true, $tc = false ) {
		global $wgWeekdayAbbreviationsJa;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $tc ); }
		$datePreference = $this->dateFormat( $format );

		if( $datePreference == MW_DATE_ISO ) {
			$d = substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .
					substr($ts, 6, 2);
			return $d;
		}

		$year = (int)substr( $ts, 0, 4 );
		$month = (int)substr( $ts, 4, 2 );
		$mday = (int)substr( $ts, 6, 2 );
		$hour = (int)substr( $ts, 8, 2 );
		$minute = (int)substr( $ts, 10, 2 );
		$second = (int)substr( $ts, 12, 2 );

		$time = mktime( $hour, $minute, $second, $month, $mday, $year );
		$date = getdate( $time );

		$d = $year . "年" .
				$this->getMonthAbbreviation( $month ) .
				$mday . "日 (" .
				$wgWeekdayAbbreviationsJa[ $date['wday'] ]. ")";
		return $d;
	}

	function time( $ts, $adj = false, $format = true, $tc = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $tc ); }
		$datePreference = $this->dateFormat( $format );

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		if ( $datePreference == MW_DATE_ISO ) {
			$t .= ':' . substr( $ts, 12, 2 );
		}

		return $t;
	}

	function timeanddate( $ts, $adj = false, $format = true, $tc = false ) {
		return $this->date( $ts, $adj, $format, $tc ) . " " . $this->time( $ts, $adj, $format, $tc );
	}

	function getMessage( $key ) {
		global $wgAllMessagesJa;
		if(array_key_exists($key, $wgAllMessagesJa))
			return $wgAllMessagesJa[$key];
		else
			return parent::getMessage($key);
	}

	function stripForSearch( $string ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex
		global $wikiLowerChars;
		$s = $string;

		# Strip known punctuation ?
		#$s = preg_replace( '/\xe3\x80[\x80-\xbf]/', '', $s ); # U3000-303f

		# Space strings of like hiragana/katakana/kanji
		$hiragana = '(?:\xe3(?:\x81[\x80-\xbf]|\x82[\x80-\x9f]))'; # U3040-309f
		$katakana = '(?:\xe3(?:\x82[\xa0-\xbf]|\x83[\x80-\xbf]))'; # U30a0-30ff
		$kanji = '(?:\xe3[\x88-\xbf][\x80-\xbf]'
			. '|[\xe4-\xe8][\x80-\xbf]{2}'
			. '|\xe9[\x80-\xa5][\x80-\xbf]'
			. '|\xe9\xa6[\x80-\x99])';
			# U3200-9999 = \xe3\x88\x80-\xe9\xa6\x99
		$s = preg_replace( "/({$hiragana}+|{$katakana}+|{$kanji}+)/", ' $1 ', $s );

		# Double-width roman characters: ff00-ff5f ~= 0020-007f
		$s = preg_replace( '/\xef\xbc([\x80-\xbf])/e', 'chr((ord("$1") & 0x3f) + 0x20)', $s );
		$s = preg_replace( '/\xef\xbd([\x80-\x99])/e', 'chr((ord("$1") & 0x3f) + 0x60)', $s );

		# Do general case folding and UTF-8 armoring
		return LanguageUtf8::stripForSearch( $s );
	}

	# Italic is not appropriate for Japanese script
	# Unfortunately most browsers do not recognise this, and render <em> as italic
	function emphasize( $text ) {
		return $text;
	}
}

?>
