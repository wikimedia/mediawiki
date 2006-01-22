<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
# Punjabi (Gurmukhi)
# This file is dual-licensed under GFDL and GPL.
#
# See: http://bugzilla.wikimedia.org/show_bug.cgi?id=1478

require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesPa = array(
	NS_MEDIA		=> 'ਮੀਡੀਆ',
	NS_SPECIAL		=> 'ਖਾਸ',
	NS_MAIN			=> '',
	NS_TALK			=> 'ਚਰਚਾ',
	NS_USER			=> 'ਮੈਂਬਰ',
	NS_USER_TALK		=> 'ਮੈਂਬਰ_ਚਰਚਾ',
	NS_PROJECT		=> $wgMetaNamespace, /* Wikipedia?: ਵਿਕਿਪੀਡਿਆ */
	NS_PROJECT_TALK		=> $wgMetaNamespace . '_ਚਰਚਾ',
	NS_IMAGE		=> 'ਤਸਵੀਰ',
	NS_IMAGE_TALK		=> 'ਤਸਵੀਰ_ਚਰਚਾ',
	NS_MEDIAWIKI		=> 'ਮੀਡੀਆਵਿਕਿ',
	NS_MEDIAWIKI_TALK	=> 'ਮੀਡੀਆਵਿਕਿ_ਚਰਚਾ',
	NS_TEMPLATE		=> 'ਨਮੂਨਾ',
	NS_TEMPLATE_TALK	=> 'ਨਮੂਨਾ_ਚਰਚਾ',
	NS_HELP			=> 'ਮਦਦ',
	NS_HELP_TALK		=> 'ਮਦਦ_ਚਰਚਾ',
	NS_CATEGORY		=> 'ਸ਼੍ਰੇਣੀ',
	NS_CATEGORY_TALK	=> 'ਸ਼੍ਰੇਣੀ_ਚਰਚਾ'
);

/* private */ $wgQuickbarSettingsPa = array(
	'ਕੋਈ ਨਹੀਂ', 'ਸਥਿਰ ਖੱਬੇ', 'ਸਥਿਰ ਸੱਜਾ', 'ਤੈਰਦਾ ਖੱਬੇ'
);

/* private */ $wgSkinNamesPa = array(
	'standard'      => 'ਮਿਆਰੀ',
) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesPa.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguagePa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesPa;
		return $wgNamespaceNamesPa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsPa;
		return $wgQuickbarSettingsPa;
	}

	function getSkinNames() {
		global $wgSkinNamesPa;
		return $wgSkinNamesPa;
	}

	function getMessage( $key ) {
		global $wgAllMessagesPa;
		if( isset( $wgAllMessagesPa[$key] ) ) {
			return $wgAllMessagesPa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	var $digitTransTable = array(
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

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		if( $wgTranslateNumerals ) {
			return strtr( $number, $this->digitTransTable );
		} else {
			return $number;
		}
	}
}
?>
