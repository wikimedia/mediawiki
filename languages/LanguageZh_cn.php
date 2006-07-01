<?php
/** Simplified Chinese (中文(简体))
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesZh_cn.php');
}

class LanguageZh_cn extends LanguageUtf8 {
	private $mMessagesZh_cn, $mNamespaceNamesZh_cn = null;

	private $mQuickbarSettingsZh_cn = array(
		'无', /* 'None' */
		'左侧固定', /* 'Fixed left' */
		'右侧固定', /* 'Fixed right' */
		'左侧漂移' /* 'Floating left' */
	);
	
	private $mSkinNamesZh_cn = array(
		'standard' => '标准',
		'nostalgia' => '怀旧',
		'cologneblue' => '科隆香水蓝'
	);

	
	function __construct() {
		parent::__construct();

		global $wgAllMessagesZh_cn;
		$this->mMessagesZh_cn =& $wgAllMessagesZh_cn;

	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsZh_cn;
	}

	function getSkinNames() {
		return $this->mSkinNamesZh_cn + parent::getSkinNames();
	}

	function getDateFormats() {
		return false;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesZh_cn[$key] ) ) {
			return $this->mMessagesZh_cn[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesZh_cn;
	}

	function getNsIndex( $text ) {
		# Aliases
		if ( 0 == strcasecmp( "特殊", $text ) ) { return -1; }
		if ( 0 == strcasecmp( "对话", $text ) ) { return 1; }
		if ( 0 == strcasecmp( "用户", $text ) ) { return 2; }
		if ( 0 == strcasecmp( "用户对话", $text ) ) { return 3; }
		if ( 0 == strcasecmp( "图像", $text ) ) { return 6; }
		if ( 0 == strcasecmp( "图像对话", $text ) ) { return 7; }
		return false;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . "年" .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  (0 + substr( $ts, 6, 2 )) . "日";
		return $d;
	}

	function timeDateSeparator( $format ) {
		return ' ';
	}

	# inherit default iconv(), ucfirst(), checkTitleEncoding()

	function stripForSearch( $string ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex
		# we also separate characters as "words"
		if( function_exists( 'mb_strtolower' ) ) {
			return preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"' U8' . bin2hex( \"$1\" )",
				mb_strtolower( $string ) );
		} else {
			global $wikiLowerChars;
			return preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"' U8' . bin2hex( strtr( \"\$1\", \$wikiLowerChars ) )",
				$string );
		}
	}
}


?>
