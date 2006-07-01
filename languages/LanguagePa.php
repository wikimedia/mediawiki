<?php
/** Punjabi (Gurmukhi)
  * @package MediaWiki
  * @subpackage Language
  */
# This file is dual-licensed under GFDL and GPL.
#
# See: http://bugzilla.wikimedia.org/show_bug.cgi?id=1478

require_once('LanguageUtf8.php');

if (!$wgCachedMessageArrays) {
	require_once('MessagesPa.php');
}

class LanguagePa extends LanguageUtf8 {
	private $mMessagesPa, $mNamespaceNamesPa = null;

	private $mQuickbarSettingsPa = array(
		'ਕੋਈ ਨਹੀਂ', 'ਸਥਿਰ ਖੱਬੇ', 'ਸਥਿਰ ਸੱਜਾ', 'ਤੈਰਦਾ ਖੱਬੇ'
	);
	
	private $mSkinNamesPa = array(
		'standard'      => 'ਮਿਆਰੀ',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesPa;
		$this->mMessagesPa =& $wgAllMessagesPa;

		global $wgMetaNamespace;
		$this->mNamespaceNamesPa = array(
			NS_MEDIA          => 'ਮੀਡੀਆ',
			NS_SPECIAL        => 'ਖਾਸ',
			NS_MAIN           => '',
			NS_TALK           => 'ਚਰਚਾ',
			NS_USER           => 'ਮੈਂਬਰ',
			NS_USER_TALK      => 'ਮੈਂਬਰ_ਚਰਚਾ',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '_ਚਰਚਾ',
			NS_IMAGE          => 'ਤਸਵੀਰ',
			NS_IMAGE_TALK     => 'ਤਸਵੀਰ_ਚਰਚਾ',
			NS_MEDIAWIKI      => 'ਮੀਡੀਆਵਿਕਿ',
			NS_MEDIAWIKI_TALK => 'ਮੀਡੀਆਵਿਕਿ_ਚਰਚਾ',
			NS_TEMPLATE       => 'ਨਮੂਨਾ',
			NS_TEMPLATE_TALK  => 'ਨਮੂਨਾ_ਚਰਚਾ',
			NS_HELP           => 'ਮਦਦ',
			NS_HELP_TALK      => 'ਮਦਦ_ਚਰਚਾ',
			NS_CATEGORY       => 'ਸ਼੍ਰੇਣੀ',
			NS_CATEGORY_TALK  => 'ਸ਼੍ਰੇਣੀ_ਚਰਚਾ'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesPa + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsPa;
	}

	function getSkinNames() {
		return $this->mSkinNamesPa + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesPa[$key] ) ) {
			return $this->mMessagesPa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesPa;
	}

	function digitTransformTable() {
		return array(
			'0' => '੦',
			'1' => '੧',
			'2' => '੨',
			'3' => '੩',
			'4' => '੪',
			'5' => '੫',
			'6' => '੬',
			'7' => '੭',
			'8' => '੮',
			'9' => '੯'
		);
	}

}
?>
