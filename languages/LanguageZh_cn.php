<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesZh_cn = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_talk',
	NS_IMAGE            => 'Image',
	NS_IMAGE_TALK       => 'Image_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsZh_cn = array(
	"无", /* "None" */
	"左侧固定", /* "Fixed left" */
	"右侧固定", /* "Fixed right" */
	"左侧漂移" /* "Floating left" */
);

/* private */ $wgSkinNamesZh_cn = array(
	'standard' => "标准",
	'nostalgia' => "怀旧",
	'cologneblue' => "科隆香水蓝"
) + $wgSkinNamesEn;

/* private */ $wgUserTogglesZh_cn = array(
	'nolangconversion',
) + $wgUserTogglesEn;


if (!$wgCachedMessageArrays) {
	require_once('MessagesZh_cn.php');
}


class LanguageZh_cn extends LanguageUtf8 {

	function getUserToggles() {
		global $wgUserTogglesZh_cn;
		return $wgUserTogglesZh_cn;
	}

	function getNamespaces() {
		global $wgNamespaceNamesZh_cn;
		return $wgNamespaceNamesZh_cn;
	}


	function getNsIndex( $text ) {
		global $wgNamespaceNamesZh_cn;

		foreach ( $wgNamespaceNamesZh_cn as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Aliases
		if ( 0 == strcasecmp( "特殊", $text ) ) { return -1; }
		if ( 0 == strcasecmp( "", $text ) ) { return ; }
		if ( 0 == strcasecmp( "对话", $text ) ) { return 1; }
		if ( 0 == strcasecmp( "用户", $text ) ) { return 2; }
		if ( 0 == strcasecmp( "用户对话", $text ) ) { return 3; }
		if ( 0 == strcasecmp( "{{SITENAME}}_对话", $text ) ) { return 5; }
		if ( 0 == strcasecmp( "图像", $text ) ) { return 6; }
		if ( 0 == strcasecmp( "图像对话", $text ) ) { return 7; }
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsZh_cn;
		return $wgQuickbarSettingsZh_cn;
	}

	function getSkinNames() {
		global $wgSkinNamesZh_cn;
		return $wgSkinNamesZh_cn;
	}

	function getDateFormats() {
		return false;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . "年" .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  (0 + substr( $ts, 6, 2 )) . "日";
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->time( $ts, $adj ) . " " . $this->date( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesZh_cn;
		if( isset( $wgAllMessagesZh_cn[$key] ) )
			return $wgAllMessagesZh_cn[$key];
		else
			return parent::getMessage( $key );
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
