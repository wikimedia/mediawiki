<?php
/** Chuvash (Чăвашла)
 *
 * @package MediaWiki
 * @subpackage Language
 */

# Chuvash stub localization; default to Russian instead of English.

# Cyrillic chars:   Ӑӑ Ӗӗ Ҫҫ Ӳӳ
# Latin substitute: Ăă Ĕĕ Çç Ÿÿ
# Where are latin substitute in this file because of font problems.

require_once( "LanguageRu.php" );

if (!$wgCachedMessageArrays) {
	require_once('MessagesCv.php');
}

class LanguageCv extends LanguageRu {
	private $mMessagesCv, $mNamespaceNamesCv = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesCv;
		$this->mMessagesCv =& $wgAllMessagesCv;

		global $wgMetaNamespace;
		$this->mNamespaceNamesCv = array(
			NS_MEDIA            => 'Медиа',
			NS_SPECIAL          => 'Ятарлă',
			NS_MAIN             => '',
			NS_TALK             => 'Сӳтсе явасси',
			NS_USER             => 'Хутшăнакан',
			NS_USER_TALK        => 'Хутшăнаканăн_канашлу_страници',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_сӳтсе_явмалли',
			NS_IMAGE            => 'Ӳкерчĕк',
			NS_IMAGE_TALK       => 'Ӳкерчĕке_сӳтсе_явмалли',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_сӳтсе_явмалли',
			NS_TEMPLATE         => 'Шаблон',
			NS_TEMPLATE_TALK    => 'Шаблона_сӳтсе_явмалли',
			NS_HELP             => 'Пулăшу',
			NS_HELP_TALK        => 'Пулăшăва_сӳтсе_явмалли',
			NS_CATEGORY         => 'Категори',
			NS_CATEGORY_TALK    => 'Категорине_сӳтсе_явмалли',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesCv + parent::getNamespaces();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesCv[$key] ) ) {
			return $this->mMessagesCv[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesCv;
	}

	function getFallbackLanguage() {
		return 'ru';
	}

	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$datePreference = $this->dateFormat( $format );
		if( $datePreference == MW_DATE_DEFAULT ) {
			$datePreference = MW_DATE_YMD;
		}

		$month = $this->formatMonth( substr( $ts, 4, 2 ), $datePreference );
		$day = $this->formatDay( substr( $ts, 6, 2 ), $datePreference );
		$year = $this->formatNum( substr( $ts, 0, 4 ), true );

		switch( $datePreference ) {
			case MW_DATE_DMY: return "$day $month $year";
			case MW_DATE_YMD: return "$year, $month, $day";
			case MW_DATE_ISO: return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
			default: return "$year, $month, $day";
		}


	}

	//only for quotation mark
	function linkPrefixExtension() { return true; }
}
?>
