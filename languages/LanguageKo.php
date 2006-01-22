<?php
/** Korean (한국어)
  *
  * @package MediaWiki
  * @subpackage Language
  */
require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesKo = array(
	NS_MEDIA	    => 'Media',
	NS_SPECIAL	  => '특수기능',
	NS_MAIN	     => '',
	NS_TALK	     => '토론',
	NS_USER	     => '사용자',
	NS_USER_TALK	=> '사용자토론',
	NS_PROJECT	  => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace.'토론',
	NS_IMAGE	    => '그림',
	NS_IMAGE_TALK       => '그림토론',
	NS_HELP             => '도움말',
	NS_HELP_TALK        => '도움말토론',
	NS_CATEGORY	 => '분류',
	NS_CATEGORY_TALK    => '분류토론',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsKo = array(
	'없음', '왼쪽 붙박이', '오른쪽 붙박이', '왼쪽 떠다님'

);

/* private */ $wgSkinNamesKo = array(
	'standard' => '보통',
	'nostalgia' => '그리움',
	'cologneblue' => '쾰른 파랑',
	'davinci' => '다빈치',
	'mono' => '모노',
	'monobook' => '모노북(기본값)',
	'my skin' => '내 스킨',
	'chick' => '칙(Chick)',
) + $wgSkinNamesEn;


/* private */ $wgBookstoreListKo = array(
	'Aladdin.co.kr' => 'http://www.aladdin.co.kr/catalog/book.asp?ISBN=$1'
) + $wgBookstoreListEn;



# (Okay, I think I got it right now. This can be adjusted
#  in the 'date' function down at the bottom. --Brion)
#
# Thanks. And it's usual that the time comes after dates.
# So I've change the timeanddate function, just exchanged $time and $date
# But you should check before you install it, 'cause I'm quite stupid about
# the programming.
#

/* private */ $wgWeekdayAbbreviationsKo = array(
	'일', '월', '화', '수', '목',
	'금', '토'
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesKo.php');
}

class LanguageKo extends LanguageUtf8 {

	function getBookstoreList() {
		global $wgBookstoreListKo;
		return $wgBookstoreListKo;
	}

	function getNamespaces() {
		global $wgNamespaceNamesKo;
		return $wgNamespaceNamesKo;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsKo;
		return $wgQuickbarSettingsKo;
	}

	function getSkinNames() {
		global $wgSkinNamesKo;
		return $wgSkinNamesKo;
	}

	function getDateFormats() {
		return false;
	}

	function date( $ts, $adj = false ) {
		global $wgWeekdayAbbreviationsKo;
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		# This is horribly inefficient; I need to rework this
		$x = getdate(mktime(( (int)substr( $ts, 8, 2) ),
			(int)substr( $ts, 10, 2 ), (int)substr( $ts, 12, 2 ),
			(int)substr( $ts, 4, 2 ), (int)substr( $ts, 6, 2 ),
			(int)substr( $ts, 0, 4 )));

		$d = substr( $ts, 0, 4 ) . "년 " .
			$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . "월 " .
			(0 + substr( $ts, 6, 2 )) . "일 " .
			"(" . $wgWeekdayAbbreviationsKo[$x["wday"]] . ")";
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesKo;
		return isset($wgAllMessagesKo[$key]) ? $wgAllMessagesKo[$key] : parent::getMessage($key);
	}

	function firstChar( $s ) {
		preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})/', $s, $matches);

		if ( isset( $matches[1] ) ) {
			if ( strlen( $matches[1] ) != 3 ) {
				return $matches[1];
			}
			$code = (ord($matches[1]{0}) & 0x0f) << 12;
			$code |= (ord($matches[1]{1}) & 0x3f) << 6;
			$code |= (ord($matches[1]{2}) & 0x3f);
			if ( $code < 0xac00 || 0xd7a4 <= $code) {
				return $matches[1];
			} elseif ( $code < 0xb098 ) {
				return "\xe3\x84\xb1";
			} elseif ( $code < 0xb2e4 ) {
				return "\xe3\x84\xb4";
			} elseif ( $code < 0xb77c ) {
				return "\xe3\x84\xb7";
			} elseif ( $code < 0xb9c8 ) {
				return "\xe3\x84\xb9";
			} elseif ( $code < 0xbc14 ) {
				return "\xe3\x85\x81";
			} elseif ( $code < 0xc0ac ) {
				return "\xe3\x85\x82";
			} elseif ( $code < 0xc544 ) {
				return "\xe3\x85\x85";
			} elseif ( $code < 0xc790 ) {
				return "\xe3\x85\x87";
			} elseif ( $code < 0xcc28 ) {
				return "\xe3\x85\x88";
			} elseif ( $code < 0xce74 ) {
				return "\xe3\x85\x8a";
			} elseif ( $code < 0xd0c0 ) {
				return "\xe3\x85\x8b";
			} elseif ( $code < 0xd30c ) {
				return "\xe3\x85\x8c";
			} elseif ( $code < 0xd558 ) {
				return "\xe3\x85\x8d";
			} else {
				return "\xe3\x85\x8e";
			}
		} else {
			return "";
		}
	}
}

?>
