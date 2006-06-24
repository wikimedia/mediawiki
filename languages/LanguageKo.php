<?php
/** Korean (한국어)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once('LanguageUtf8.php');

if (!$wgCachedMessageArrays) {
	require_once('MessagesKo.php');
}

class LanguageKo extends LanguageUtf8 {
	private $mMessagesKo, $mNamespaceNamesKo = null;

		private $mQuickbarSettingsKo = array(
			'없음', '왼쪽', '오른쪽', '왼쪽 고정', '오른쪽 고정'
		);
		
		private $mSkinNamesKo = array(
			'standard' => '기본값',
			'davinci' => '다빈치',
			'mono' => '모노',
			'monobook' => '모노북',
			'my skin' => '내 스킨',
		);
		
		private $mBookstoreListKo = array(
			'Aladdin.co.kr' => 'http://www.aladdin.co.kr/catalog/book.asp?ISBN=$1'
		);
		
		# (Okay, I think I got it right now. This can be adjusted
		#  in the 'date' function down at the bottom. --Brion)
		#
		# Thanks. And it's usual that the time comes after dates.
		# So I've change the timeanddate function, just exchanged $time and $date
		# But you should check before you install it, 'cause I'm quite stupid about
		# the programming.
		#
		
		private $mWeekdayAbbreviationsKo = array(
			'일', '월', '화', '수', '목', '금', '토'
		);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesKo;
		$this->mMessagesKo =& $wgAllMessagesKo;

		global $wgMetaNamespace;
		$this->mNamespaceNamesKo = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => '특수기능',
			NS_MAIN           => '',
			NS_TALK           => '토론',
			NS_USER           => '사용자',
			NS_USER_TALK      => '사용자토론',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace.'토론',
			NS_IMAGE          => '그림',
			NS_IMAGE_TALK     => '그림토론',
			NS_HELP           => '도움말',
			NS_HELP_TALK      => '도움말토론',
			NS_CATEGORY       => '분류',
			NS_CATEGORY_TALK  => '분류토론',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesKo + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsKo;
	}

	function getSkinNames() {
		return $this->mSkinNamesKo + parent::getSkinNames();
	}

	function getBookstoreList() {
		return $this->mBookstoreListKo + parent::getBookstoreList();
	}

	function getDateFormats() {
		return false;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesKo[$key] ) ) {
			return $this->mMessagesKo[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesKo;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$year = (int)substr( $ts, 0, 4 );
		$month = (int)substr( $ts, 4, 2 );
		$mday = (int)substr( $ts, 6, 2 );
		$hour = (int)substr( $ts, 8, 2 );
		$minute = (int)substr( $ts, 10, 2 );
		$second = (int)substr( $ts, 12, 2 );
		$time = mktime( $hour, $minute, $second, $month, $mday, $year );
		$date = getdate( $time );

		# "xxxx년 xx월 xx일 (월)"
		# timeanddate works "xxxx년 xx월 xx일 (월) xx:xx"
		$d = $year . "년 " .
			$this->getMonthAbbreviation( $month ) . "월 " .
			$mday . "일 ".
			"(" . $this->mWeekdayAbbreviationsKo[ $date['wday'] ]. ")";

		return $d;
	}

	function timeBeforeDate() {
		return false;
	}

	function timeDateSeparator( $format ) {
		return ' ';
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
